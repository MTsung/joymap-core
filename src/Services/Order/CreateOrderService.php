<?php

namespace Mtsung\JoymapCore\Services\Order;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Facades\Rand;
use Mtsung\JoymapCore\Models\CanOrderTime;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Models\StoreTableCombination;
use Mtsung\JoymapCore\Params\Member\MemberGetOrCreateParams;
use Mtsung\JoymapCore\Repositories\Order\OrderRepository;
use Mtsung\JoymapCore\Repositories\Store\CanOrderTimeRepository;
use Mtsung\JoymapCore\Repositories\Store\StoreTableCombinationRepository;
use Mtsung\JoymapCore\Services\Member\MemberGetOrCreateService;
use Mtsung\JoymapCore\Services\Order\CreateBy\ByMember;
use Mtsung\JoymapCore\Services\Order\CreateBy\ByStore;
use Mtsung\JoymapCore\Services\Order\CreateBy\CreateOrderInterface;

/**
 * @method static self make()
 */
class CreateOrderService
{
    use AsObject;

    private ?Store $store = null;

    private ?int $type = null;

    private ?int $fromSource = null;

    private ?CreateOrderInterface $byService = null;

    private ?Member $member = null;

    private ?Carbon $reservationDatetime = null;

    private string $reservationDate;

    private string $reservationTime;

    private Carbon $beginTime;

    private Carbon $endTime;

    private int $peopleNum = 0;

    private int $adultNum = 0;

    private int $childNum = 0;

    private int $childSeatNum = 0;

    private ?StoreTableCombination $storeTableCombination = null;

    private ?int $waitNumber = null;

    public function __construct(
        private OrderRepository                 $orderRepository,
        private CanOrderTimeRepository          $canOrderTimeRepository,
        private StoreTableCombinationRepository $storeTableCombinationRepository,
    )
    {
    }

    public function store(Store $store): CreateOrderService
    {
        $this->store = $store;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function type(int $type): CreateOrderService
    {
        $this->type = $type;

        return $this;
    }


    /**
     * @throws Exception
     */
    public function fromSource(int $fromSource): CreateOrderService
    {
        if (is_null($this->store)) {
            throw new Exception('請呼叫 store()', 500);
        }

        $this->fromSource = $fromSource;

        $this->byService = match ($fromSource) {
            Order::FROM_SOURCE_RESTAURANT_BOOKING => app(ByStore::class),
            default => app(ByMember::class),
        };

        $this->byService->store($this->store)->type($this->type);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function member(string $phone, string $name, int $gender, string $email = null): CreateOrderService
    {
        if (is_null($this->fromSource)) {
            throw new Exception('請呼叫 fromSource()', 500);
        }

        $memberGetOrCreateParams = MemberGetOrCreateParams::make([
            'full_phone' => $phone,
            'name' => $name,
            'from_source' => $this->fromSource,
            'gender' => $gender,
            'email' => $email,
        ]);

        $this->member = MemberGetOrCreateService::run($memberGetOrCreateParams);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function reservationDatetime(Carbon $reservationDatetime): CreateOrderService
    {
        if (is_null($this->store)) {
            throw new Exception('請呼叫 store()', 500);
        }

        if (is_null($this->type)) {
            throw new Exception('請呼叫 type()', 500);
        }

        $this->byService->checkReservationDatetime($reservationDatetime);

        $this->reservationDatetime = $reservationDatetime;

        $this->reservationDate = $reservationDatetime->toDateString();

        $this->reservationTime = $reservationDatetime->toTimeString();

        $this->orderTimeRang($reservationDatetime);

        return $this;
    }

    public function orderTimeRang(Carbon $startTime): CreateOrderService
    {
        $this->beginTime = $startTime->copy();

        $this->endTime = $this->beginTime->copy()->addMinutes($this->store->limit_minute);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function people(int $adultNum, int $childNum = 0, int $childSeatNum = 0): CreateOrderService
    {
        if (is_null($this->store)) {
            throw new Exception('請呼叫 store()', 500);
        }

        if (is_null($this->fromSource)) {
            throw new Exception('請呼叫 fromSource()', 500);
        }

        $this->peopleNum = $adultNum + $childNum;

        $this->byService->checkPeople($this->peopleNum);

        $this->adultNum = $adultNum;

        $this->childNum = $childNum;

        $this->childSeatNum = $childSeatNum;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function tables(array $tableIds): CreateOrderService
    {
        if (is_null($this->store)) {
            throw new Exception('請呼叫 store()', 500);
        }

        if (is_null($this->byService)) {
            throw new Exception('請呼叫 fromSource()', 500);
        }

        if (is_null($this->reservationDatetime)) {
            throw new Exception('請呼叫 reservationDatetime()', 500);
        }

        if (is_null($this->type)) {
            throw new Exception('請呼叫 type()', 500);
        }

        if ($this->peopleNum === 0) {
            throw new Exception('請呼叫 people()', 500);
        }

        if ($this->type == Order::TYPE_ONSITE_WAIT) {
            if (count($tableIds) === 0) {
                return $this;
            }

            // 現場候位有指定位置就要算可用時間塞入
            if (!$combination = $this->storeTableCombinationRepository->getByTableIds($tableIds)) {
                throw new Exception('桌位異常', 500);
            }

            $canOrderTime = $this->canOrderTimeRepository->getCanOrderTimesAndTables(
                $this->store,
                [Carbon::now(), Carbon::now()->addDay()],
                $this->fromSource != Order::FROM_SOURCE_RESTAURANT_BOOKING,
                $combination->id,
                false,
            )->first(function (CanOrderTime $value) {
                return in_array($this->peopleNum, Arr::collapse($value->people_array));
            });

            if (!$canOrderTime) {
                throw new Exception('該桌位於24小時內已無可用時間。', 422);
            }

            $this->orderTimeRang(Carbon::parse($canOrderTime['begin_time']));

            $this->storeTableCombination = $combination;

            return $this;
        }

        $this->storeTableCombination = $this->byService->getTableCombination(
            $this->reservationDatetime,
            $this->peopleNum,
            $tableIds,
        );

        return $this;
    }

    /**
     * @throws Exception
     */
    public function handle(
        string $name,
        int    $gender,
        string $email = null,
        int    $goalId = null,
        string $comment = null,
        string $storeComment = null,
        array  $tagIds = [],
    ): Order
    {
        if (is_null($this->member)) {
            throw new Exception('請呼叫 member()', 500);
        }

        if (is_null($this->store)) {
            throw new Exception('請呼叫 store()', 500);
        }

        if ($this->adultNum < 1) {
            throw new Exception('請呼叫 people()', 500);
        }

        if (is_null($this->reservationDatetime)) {
            throw new Exception('請呼叫 reservationDatetime()', 500);
        }

        if (is_null($this->type)) {
            throw new Exception('請呼叫 type()', 500);
        }

        if ($this->type != Order::TYPE_ONSITE_WAIT) {
            // 自動排桌
            if (is_null($this->storeTableCombination)) {
                $this->tables([]);
            }
        } else {
            // 計算候位號碼
            $this->waitNumber = $this->orderRepository->getWaitNumber($this->store, $this->reservationDatetime);
        }

        $data = [
            'order_no' => Rand::orderNo(),
            'member_id' => $this->member->id,
            'store_id' => $this->store->id,
            'type' => $this->type,
            'wait_number' => $this->waitNumber,
            'from_source' => $this->fromSource,
            'name' => $name,
            'gender' => $gender,
            'email' => $email,
            'status' => $this->byService->getStatus(),
            'adult_num' => $this->adultNum,
            'child_num' => $this->childNum,
            'child_seat_num' => $this->childSeatNum,
            'reservation_date' => $this->reservationDate,
            'reservation_time' => $this->reservationTime,
            'store_table_combination_id' => $this->storeTableCombination?->id,
            'store_table_combination_name' => $this->storeTableCombination?->combination_name,
            'begin_time' => $this->beginTime,
            'end_time' => $this->endTime,
            'goal_id' => $goalId ?? 1,
            'comment' => $comment,
            'store_comment' => $storeComment,
            'app_version' => request()->header('version'),
        ];

        $order = DB::transaction(function () use ($data, $tagIds) {
            $order = $this->orderRepository->create($data);

            $order->timeLog()->create([
                'order_time' => $this->fromSource != Order::FROM_SOURCE_RESTAURANT_BOOKING ? Carbon::now() : null,
                'store_order_time' => $this->fromSource == Order::FROM_SOURCE_RESTAURANT_BOOKING ? Carbon::now() : null,
                'seat_time' => $this->type == Order::TYPE_ONSITE_SEAT ? Carbon::now() : null,
            ]);

            $order->orderTags()->sync($tagIds);

            return $order;
        });

        event(new OrderSuccessEvent($order->refresh()));

        return $order;
    }
}