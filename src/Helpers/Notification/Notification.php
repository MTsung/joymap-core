<?php

namespace Mtsung\JoymapCore\Helpers\Notification;

use Exception;
use Mtsung\JoymapCore\Repositories\Member\MemberPushRepository;
use Mtsung\JoymapCore\Repositories\Notification\NotificationRepository;
use Mtsung\JoymapCore\Repositories\Store\StoreUserPushRepository;
use Mtsung\JoymapCore\Repositories\Store\StoreNotificationRepository;

class Notification
{
    private NotificationInterface $service;
    private array $tokens = [];
    private string $title = '';
    private string $body = '';
    private int $badge = 1;
    private array $data = [];

    public function __construct(
        private MemberPushRepository        $memberPushRepository,
        private NotificationRepository      $notificationRepository,
        private StoreUserPushRepository     $storeUserPushRepository,
        private StoreNotificationRepository $storeNotificationRepository,
    )
    {
    }

    public function byFcm(): Notification
    {
        $this->service = app(Fcm::class);
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

        if ($this->service instanceof Fcm) {
            $this->tokens = $tokens->pluck('device_token')->toArray();
        } elseif ($this->service instanceof Gorush) {
            $this->service->topic(config('joymap.notification.gorush.topic.member'));

            $this->tokens[Gorush::PLATFORM_IOS] = $tokens
                ->where('platform', Gorush::PLATFORM_IOS)
                ->toArray();

            $this->tokens[Gorush::PLATFORM_ANDROID] = $tokens
                ->where('platform', Gorush::PLATFORM_ANDROID)
                ->toArray();
        }

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

        if ($this->service instanceof Fcm) {
            $this->tokens = $tokens->pluck('device_token')->toArray();
        } elseif ($this->service instanceof Gorush) {
            $this->service->topic(config('joymap.notification.gorush.topic.store'));

            $this->tokens[Gorush::PLATFORM_IOS] = $tokens
                ->where('platform', Gorush::PLATFORM_IOS)
                ->toArray();

            $this->tokens[Gorush::PLATFORM_ANDROID] = $tokens
                ->where('platform', Gorush::PLATFORM_ANDROID)
                ->toArray();
        }

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
        $this->data = array_merge($this->data, ['action' => $action]);
        return $this;
    }

    public function data(array $data): Notification
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(): bool
    {
        if (count($this->tokens) === 0) {
            throw new Exception('Tokens Empty.', 422);
        }
        if (!$this->title) {
            throw new Exception('請呼叫 title()', 422);
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
}
