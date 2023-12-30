<?php

namespace Mtsung\JoymapCore\Services\Notification;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\NotificationStorePay;
use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Repositories\Notification\NotificationStorePayRepository;
use Mtsung\JoymapCore\Repositories\Order\OrderRepository;
use Mtsung\JoymapCore\Repositories\Pay\PayLogRepository;

/**
 * @method static void run(PayLog $payLog)
 */
class CreateNotificationPayService
{
    use AsObject;

    public function __construct(
        private NotificationStorePayRepository $notificationStorePayRepository,
        private OrderRepository                $orderRepository,
        private PayLogRepository               $payLogRepository,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function handle(PayLog $payLog): void
    {
        // 檢查 N 小時內有沒有來過(入座/離席的訂位，成功支付)，有的話就不顯示按鈕。
        if ($this->hasVisitedInLastHours($payLog)) {
            $status = NotificationStorePay::STATUS_SHOW_BUTTON;
        } else {
            $status = NotificationStorePay::STATUS_HIDE_BUTTON;
        }

        $this->notificationStorePayRepository->createWithNotification(
            $payLog->storeId,
            $payLog->memberId,
            $payLog->paylogId,
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

    private function hasVisitedInLastHours(PayLog $payLog, int $hour = 6): bool
    {
        $now = Carbon::now();
        $checkDateRange = [
            $now->copy()->subHours($hour),
            $now,
        ];

        $storeId = $payLog->store_id;
        $memberId = $payLog->member_id;

        if ($this->orderRepository->getSuccessLog($storeId, $memberId, $checkDateRange)->exists()) {
            return true;
        }

        if ($this->payLogRepository->getSuccessLog($storeId, $memberId, $checkDateRange)->count() > 1) {
            return true;
        }

        return false;
    }
}
