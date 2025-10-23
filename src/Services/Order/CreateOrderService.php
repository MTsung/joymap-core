<?php

namespace Mtsung\JoymapCore\Services\Order;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Facades\Rand;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\Store;
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

    public ?Store $store = null;

    public ?int $type = null;

    public ?int $fromSource = null;

    private ?CreateOrderInterface $byService = null;

    public ?Member $member = null;

    public ?Carbon $reservationDatetime = null;

    public string $reservationDate;

    public string $reservationTime;

    public Carbon $beginTime;

    public Carbon $endTime;

    public ?int $limitMinute = null;

    public int $peopleNum = 0;

    public int $adultNum = 0;

    public int $childNum = 0;

    public int $childSeatNum = 0;

    public ?int $storeTableCombinationId = null;

    public ?string $storeTableCombinationName = null;

    public ?int $waitNumber = null;

    public function __construct(
        public OrderRepository                 $orderRepository,
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
    public function reservationDatetime(Carbon $reservationDatetime, ?int $limitMinute = null): CreateOrderService
    {
        if (is_null($this->store)) {
            throw new Exception('請呼叫 store()', 500);
        }

        if (is_null($this->type)) {
            throw new Exception('請呼叫 type()', 500);
        }

        $this->limitMinute = $limitMinute ?? $this->store->limit_minute;

        $this->byService->checkReservationDatetime($reservationDatetime);

        $this->reservationDatetime = $reservationDatetime;

        $this->reservationDate = $reservationDatetime->toDateString();

        $this->reservationTime = $reservationDatetime->toTimeString();

        $this->beginTime = $reservationDatetime->copy();

        $this->endTime = $this->beginTime->copy()->addMinutes($this->limitMinute);

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

        return FillTableService::run($this, $tableIds);
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

        // 最終檢查，黑名單、重複訂位之類的
        $this->byService->check($this);

        if ($this->type != Order::TYPE_ONSITE_WAIT) {
            // 自動排桌
            if (is_null($this->storeTableCombinationId)) {
                $this->tables([]);
            }
        } else {
            // 計算候位號碼
            $this->waitNumber = $this->orderRepository->getWaitNumber($this->store, $this->reservationDatetime);
        }

        $data = [
            'uuid' => Str::orderedUuid(),
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
            'store_table_combination_id' => $this->storeTableCombinationId,
            'store_table_combination_name' => $this->storeTableCombinationName,
            'begin_time' => $this->beginTime,
            'end_time' => $this->endTime,
            'limit_minute' => $this->limitMinute,
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

    /**
     * 讓 $this->fromSource 也能用 $this->from_source 賦予值
     * @throws Exception
     */
    public function __set($key, $value): void
    {
        if (property_exists($this, $key)) {
            $this->{$key} = $value;
            return;
        }

        $camelKey = Str::camel($key);
        if (property_exists($this, $camelKey)) {
            $this->{$camelKey} = $value;
            return;
        }

        throw new Exception('Undefined property $' . $key . ' or $' . $camelKey);
    }

    // 讓 $this->fromSource 可以用 $this->from_source 也拿到值
    public function __get($key)
    {
        if (!property_exists($this, $key)) {
            return $this->{Str::camel($key)};
        }

        return $this->{$key};
    }
}
