<?php

namespace Mtsung\JoymapCore\Services\Order\CreateBy;

use Carbon\Carbon;
use Exception;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreOrderBlacklist;
use Mtsung\JoymapCore\Models\StoreTableCombination;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;

class ByMember implements CreateOrderInterface
{
    private Store $store;

    private int $type;

    /**
     * @throws Exception
     */
    public function store(Store $store): CreateOrderInterface
    {
        $this->store = $store;

        if ($store->can_order != Store::CAN_ORDER_ENABLED) {
            throw new Exception('該店家訂位功能尚未啟用', 422);
        }

        if (!$store->orderSettings) {
            throw new Exception('order_setting 設定異常: ' . $store->id, 500);
        }

        return $this;
    }

    public function type(int $type): CreateOrderInterface
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): int
    {
        return Order::STATUS_SUCCESS_BOOKING_BY_USER;
    }

    /**
     * @throws Exception
     */
    public function checkPeople(int $people): void
    {

    }

    /**
     * @throws Exception
     */
    public function checkReservationDatetime(Carbon $reservationDatetime): void
    {
        if ($reservationDatetime < Carbon::now()) {
            throw new Exception('訂位時間小於當前時間，無法訂位', 422);
        }

        if ($this->type == Order::TYPE_RESERVE) {
            $finalOrderMinute = $this->store->orderSettings->final_order_minute;
            if ($reservationDatetime < Carbon::now()->addMinutes($finalOrderMinute)) {
                throw new Exception('該時間已不可預約', 422);
            }

            $canOrderDay = $this->store->orderSettings->can_order_day;
            if ($reservationDatetime > Carbon::now()->addDays($canOrderDay - 1)) {
                throw new Exception('該時間尚未開放預約', 422);
            }
        }
    }

    public function check(Store $store, Member $member): CreateOrderInterface
    {
        // 黑名單判斷
        $query = StoreOrderBlacklist::query();
        $checkCols = ['store_id', 'member_id'];
        $checkValues = [$store->id, $member->id];

        if ($query->whereRowValues($checkCols, '=', $checkValues)->exists()) {
            throw new Exception('因訂位記錄多次缺席，如欲訂位請致電餐廳', 422100);
        }

        return $this;
    }
}