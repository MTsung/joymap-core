<?php

namespace Mtsung\JoymapCore\Services\Pay;

use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Repositories\Order\OrderRepository;
use Mtsung\JoymapCore\Repositories\Pay\PayLogRepository;


class PayLogService
{
    public function __construct(
        private OrderRepository  $orderRepository,
        private PayLogRepository $payLogRepository,
    )
    {
    }

    public function canComment(PayLog $payLog, int $hour = 6): bool
    {
        if ($payLog->store->can_comment == Store::CAN_COMMENT_DISABLED) {
            return false;
        }

        if ($payLog->memberComment) {
            return false;
        }

        $checkDateRange = [
            $payLog->created_at->copy()->subHours($hour),
            $payLog->created_at->copy()->subSecond(),
        ];

        $storeId = $payLog->store_id;
        $memberId = $payLog->member_id;

        if ($this->orderRepository->getSuccessLog($storeId, $memberId, $checkDateRange)->exists()) {
            return false;
        }

        if ($this->payLogRepository->getSuccessLog($storeId, $memberId, $checkDateRange)->exists()) {
            return false;
        }

        return true;
    }
}
