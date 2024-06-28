<?php

namespace Mtsung\JoymapCore\Services\PushNotification\Store\Pay;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Enums\PushNotificationToTypeEnum;
use Mtsung\JoymapCore\Events\Pay\PaySuccessEvent;
use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Services\PushNotification\PushNotificationAbstract;


/**
 * @method static dispatch(Store $to, PayLog $comment)
 * @method static bool run(Store $to, PayLog $comment)
 */
class SendPaySuccessPushNotificationService extends PushNotificationAbstract
{
    public function toType(): PushNotificationToTypeEnum
    {
        return PushNotificationToTypeEnum::store;
    }

    public function title(): string
    {
        return __('joymap::notification.pay_to_store.title');
    }

    public function body(): string
    {
        $payLog = $this->arguments;

        return __('joymap::notification.pay_to_store.body', [
            'pay_no' => $payLog->pay_no,
            'amount' => $payLog->amount,
        ]);
    }

    public function action(): string
    {
        return 'notification.list';
    }

    public function data(): array
    {
        $payLog = $this->arguments;

        $store = $payLog->store;

        return [
            'pay_log_id' => $payLog->id,
            'pay_no' => $payLog->pay_no,
            'amount' => $payLog->amount,
            'member_id' => $payLog->member_id,
            'pay_remind_enable_status' => $store->storePayRemindSetting?->is_enable,
            'pay_remind_type' => $store->storePayRemindSetting?->type,
            'pay_at' => Carbon::parse($payLog->created_at)->toDateTimeString(),
        ];
    }

    /**
     * @throws Exception
     */
    public function asListener(PaySuccessEvent $event): bool
    {
        return self::run($event->payLog->store, $event->payLog);
    }
}
