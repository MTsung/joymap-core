<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Member\Pay;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Events\Pay\PaySuccessEvent;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Services\Pay\PayLogService;
use Mtsung\JoymapCore\Services\PushNotification\PushNotificationAbstract;


/**
 * @method static dispatch(Member $to, PayLog $payLog)
 * @method static bool run(Member $to, PayLog $payLog)
 */
class SendPaySuccessPushNotificationService extends PushNotificationAbstract
{
    public function __construct(private PayLogService $payLogService)
    {
    }

    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::member;
    }

    public function title(): string
    {
        $payLog = $this->arguments;

        $key = $this->payLogService->canComment($payLog) ?
            'joymap::notification.pay.title' :
            'joymap::notification.pay.title_no_comment';

        return __($key, ['store_name' => $payLog->store->name]);
    }

    public function body(): string
    {
        $payLog = $this->arguments;

        $datetime = Carbon::parse($payLog->created_at)->translatedFormat('Y/m/d D H:i');


        return __('joymap::notification.pay.body', [
            'datetime' => $datetime,
            'amount' => $payLog->amount
        ]);
    }

    public function action(): string
    {
        return 'notification.list';
    }

    public function data(): array
    {
        return [];
    }

    /**
     * @throws Exception
     */
    public function asListener(PaySuccessEvent $event): bool
    {
        return self::run($event->payLog->member, $event->payLog);
    }
}