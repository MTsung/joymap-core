<?php

namespace Mtsung\JoymapCore\Helpers\Sms;


use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Repositories\Member\MemberRepository;

class Sms
{
    private SmsInterface $service;
    private array $phones = [];
    private string $body = '';
    private ?Carbon $sendAt = null;

    /**
     * @throws Exception
     */
    public function __construct(
        private MemberRepository $memberRepository,
    )
    {
        if (config()->has('joymap.sms.default')) {
            $defaultChannel = config('joymap.sms.default');
            $this->service = match ($defaultChannel) {
                'infobip' => app(Infobip::class),
                default => throw new Exception('不支援的 SMS Channel(joymap.sms.default)：' . $defaultChannel),
            };
        }
    }

    public function byInfobip(): Sms
    {
        $this->service = app(Infobip::class);
        return $this;
    }

    public function members(array $memberIds): Sms
    {
        $phones = $this->memberRepository->getPhones($memberIds);

        $this->phones = $phones->pluck('full_phone')->toArray();

        return $this;
    }

    public function member(int $memberId): Sms
    {
        $this->members([$memberId]);

        return $this;
    }

    public function phones(array $phones): Sms
    {
        $this->phones = $phones;
        return $this;
    }

    public function phone(array $phone): Sms
    {
        $this->phones = [$phone];
        return $this;
    }

    public function body(string $body): Sms
    {
        $this->body = $body;
        return $this;
    }

    public function sendAt(Carbon $sendAt): Sms
    {
        $this->sendAt = $sendAt;
        return $this;
    }

    private function reset(): void
    {
        $this->phones = [];
        $this->body = '';
        $this->sendAt = null;
    }

    /**
     * @throws Exception
     */
    public function send(): bool
    {
        if (count($this->phones) == 0) {
            throw new Exception('Phones Empty.', 422);
        }
        if (!$this->body) {
            throw new Exception('請呼叫 body()', 422);
        }

        $res = $this->service->send(
            $this->phones,
            $this->body,
            $this->sendAt
        );

        $this->reset();

        return $res;
    }

}
