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
 * @method static void runIf(bool $boolean, PayLog $payLog)
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
        $this->notificationStorePayRepository->createWithNotification(
            $payLog->storeId,
            $payLog->memberId,
            $payLog->paylogId,
            NotificationStorePay::STATUS_SHOW_BUTTON
        );
    }

    /**
     * @throws Exception
     */
    public function asListener(object $event): void
    {
        $payLog = $event->payLog;

        // 檢查 N 小時內有沒有來過(入座/離席的訂位，成功支付)，有的話就不跑。
        self::runIf($this->hasVisitedInLastHours($payLog), $payLog);
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

        if ($this->payLogRepository->getSuccessLog($storeId, $memberId, $checkDateRange)->exists()) {
            return true;
        }

        return false;
    }
}
