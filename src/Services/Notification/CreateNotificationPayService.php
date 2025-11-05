<?php

namespace Mtsung\JoymapCore\Services\Notification;

use Exception;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\NotificationStorePay;
use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Repositories\Notification\NotificationStorePayRepository;
use Mtsung\JoymapCore\Services\Pay\PayLogService;

/**
 * @method static void run(PayLog $payLog)
 */
class CreateNotificationPayService
{
    use AsObject;

    public function __construct(
        private PayLogService                  $payLogService,
        private NotificationStorePayRepository $notificationStorePayRepository,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function handle(PayLog $payLog): void
    {
        // 檢查 N 小時內有沒有來過(入座/離席的預約，成功支付)，有的話就不顯示按鈕。
        if ($this->payLogService->canComment($payLog)) {
            $status = NotificationStorePay::STATUS_SHOW_BUTTON;
        } else {
            $status = NotificationStorePay::STATUS_HIDE_BUTTON;
        }

        $this->notificationStorePayRepository->createWithNotification(
            $payLog->store_id,
            $payLog->member_id,
            $payLog->id,
            $status
        );
    }

    /**
     * @throws Exception
     */
    public function asListener(object $event): void
    {
        $payLog = $event->payLog;

        self::run($payLog);
    }
}
