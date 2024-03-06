<?php

namespace Mtsung\JoymapCore\Helpers\Notification;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Repositories\Member\MemberPushRepository;
use Mtsung\JoymapCore\Repositories\Notification\NotificationRepository;
use Mtsung\JoymapCore\Repositories\Store\StoreNotificationRepository;
use Mtsung\JoymapCore\Repositories\Store\StoreUserPushRepository;

class Notification
{
    private NotificationInterface $service;
    private array $tokens = [];
    private string $title = '';
    private string $body = '';
    private ?string $action = null;
    private int $badge = 1;
    private array $data = [];

    public function __construct(
        private MemberPushRepository        $memberPushRepository,
        private NotificationRepository      $notificationRepository,
        private StoreUserPushRepository     $storeUserPushRepository,
        private StoreNotificationRepository $storeNotificationRepository,
    )
    {
        if (config()->has('joymap.notification.default')) {
            $defaultChannel = config('joymap.notification.default');
            $this->service = match ($defaultChannel) {
                'fcm' => app(Fcm::class),
                'fcm_v1' => app(FcmV1::class),
                'gorush' => app(Gorush::class),
                default => throw new Exception('不支援的 Notification Channel(joymap.notification.default)：' . $defaultChannel),
            };
        }
    }

    public function byFcm(): Notification
    {
        $this->service = app(Fcm::class);
        return $this;
    }

    public function byFcmV1(): Notification
    {
        $this->service = app(FcmV1::class);
        return $this;
    }

    public function byGorush(): Notification
    {
        $this->service = app(Gorush::class);
        return $this;
    }

    public function members(array $memberIds): Notification
    {
        $tokens = $this->memberPushRepository->getTokens($memberIds);
        $this->tokens = $this->service->formatToken($tokens);

        $this->topic(config('joymap.notification.channels.gorush.topic.member'));

        return $this;
    }

    public function member(int $memberId): Notification
    {
        $this->members([$memberId]);

        // 單獨時直接幫他算 badge
        $this->badge = $this->notificationRepository->getMemberUnreadWithInMonthCount($memberId);
        return $this;
    }

    public function stores(array $storeIds): Notification
    {
        $tokens = $this->storeUserPushRepository->getTokens($storeIds);
        $this->tokens = $this->service->formatToken($tokens);

        $this->topic(config('joymap.notification.channels.gorush.topic.store'));

        return $this;
    }

    public function store(int $storeId): Notification
    {
        $this->stores([$storeId]);

        // 單獨時直接幫他算 badge
        $this->badge = $this->storeNotificationRepository->getUnreadNotificationCountByStoreId($storeId);
        return $this;
    }

    public function tokens(array $tokens): Notification
    {
        $this->tokens = $tokens;
        return $this;
    }

    public function token(string $token): Notification
    {
        $this->tokens = [$token];
        return $this;
    }

    public function title(string $title): Notification
    {
        $this->title = $title;
        return $this;
    }

    public function body(string $body): Notification
    {
        $this->body = $body;
        return $this;
    }

    public function badge(int $badge): Notification
    {
        $this->badge = $badge;
        return $this;
    }

    public function action(string $action): Notification
    {
        $this->action = $action;
        return $this;
    }

    public function data(array $data): Notification
    {
        $this->data = $data;
        return $this;
    }

    public function topic(string $topic): Notification
    {
        $this->service->topic($topic);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(): bool
    {
        if (count($this->tokens) === 0) {
            Log::info('Tokens Empty.');
            return true;
        }
        if (!$this->title) {
            throw new Exception('請呼叫 title()', 500);
        }

        if (!is_null($this->action)) {
            $this->data = array_merge($this->data, ['action' => $this->action]);
        }

        $res = $this->service->send(
            $this->tokens,
            $this->title,
            $this->body,
            $this->badge,
            $this->data,
        );
        $this->reset();

        return $res;
    }

    private function reset(): void
    {
        $this->tokens = [];
        $this->title = '';
        $this->body = '';
        $this->badge = 1;
        $this->data = [];
    }

    public function getRequest(): array
    {
        return $this->service->getRequest();
    }

    public function getResponses(): Collection
    {
        return $this->service->getResponses();
    }
}
