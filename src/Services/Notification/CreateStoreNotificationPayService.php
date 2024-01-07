<?php

namespace Mtsung\JoymapCore\Services\Notification;

use Exception;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Pay\PaySuccessEvent;
use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Models\StoreNotification;
use Mtsung\JoymapCore\Repositories\Store\StoreNotificationRepository;

/**
 * @method static void run(PayLog $payLog)
 */
class CreateStoreNotificationPayService
{
    use AsObject;

    public function __construct(
        private StoreNotificationRepository $storeNotificationRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function handle(PayLog $payLog): void
    {
        $data = [
            'store_id' => $payLog->store_id,
            'title' => __('joymap::notification.pay_to_store.title'),
            'status' => StoreNotification::STATUS_PAY,
            'pay_log_id' => $payLog->id,
        ];

        $this->storeNotificationRepository->create($data);
    }

    /**
     * @throws Exception
     */
    public function asListener(PaySuccessEvent $event): void
    {
        self::run($event->payLog);
    }
}
