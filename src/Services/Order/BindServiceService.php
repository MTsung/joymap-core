<?php

namespace Mtsung\JoymapCore\Services\Order;

use Exception;
use Illuminate\Support\Facades\DB;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\OrderServiceItem;
use Mtsung\JoymapCore\Models\ServiceActivity;
use Mtsung\JoymapCore\Models\ServiceItem;
use Mtsung\JoymapCore\Models\ServiceItemType;
use Mtsung\JoymapCore\Models\ServiceType;
use Mtsung\JoymapCore\Services\Store\CheckFirstTimeService;

/**
 * @method static self make()
 */
class BindServiceService
{
    use AsObject;

    public ?Order $order = null;

    public int $amount = 0;

    public int $limitMinute = 0;

    private ?string $licensePlate = null;

    private bool $needDesignatedDriver = false;

    private ?ServiceType $serviceType = null;

    private ?ServiceItem $serviceItem = null;

    private array $addons = [];

    private ?ServiceItemType $serviceItemType = null;

    private ?ServiceActivity $serviceActivity = null;

    public function order(Order $order): BindServiceService
    {
        $this->order = $order;

        $this->serviceActivity = $this->order->store->serviceActivity;

        return $this;
    }

    public function licensePlate(string $licensePlate): BindServiceService
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public function designatedDriver(bool $need): BindServiceService
    {
        $this->needDesignatedDriver = $need;

        return $this;
    }

    /**
     * @param ServiceType $serviceType
     * @param ServiceItem $serviceItem
     * @param ServiceItem[] $addons
     * @return BindServiceService
     */
    public function service(ServiceType $serviceType, ServiceItem $serviceItem, array $addons): BindServiceService
    {
        $this->serviceType = $serviceType;

        $this->serviceItem = $serviceItem;

        $this->serviceItemType = ServiceItemType::query()
            ->where('service_item_id', $this->serviceItem->id)
            ->where('service_type_id', $this->serviceType->id)
            ->first();

        foreach ($addons as $addonServiceItem) {
            $this->addons[] = ServiceItemType::query()
                ->where('service_item_id', $addonServiceItem->id)
                ->where('service_type_id', $this->serviceType->id)
                ->first();
        }

        return $this;
    }

    public function calc(): BindServiceService
    {
        if (!$this->serviceItemType) {
            return $this;
        }

        $this->amount = (int)$this->serviceItemType->amount;
        $this->limitMinute = (int)$this->serviceItemType->limit_minute;

        /** @var ServiceItemType $addonServiceItemType */
        foreach ($this->addons as $addonServiceItemType) {
            $this->amount += (int)$addonServiceItemType->amount;
            $this->limitMinute += (int)$addonServiceItemType->limit_minute;
        }

        if ($this->needDesignatedDriver) {
            $isFirst = CheckFirstTimeService::make()
                ->ignoreOrderId($this->order->id)
                ->phone($this->order->member->phone)
                ->serviceActivity($this->serviceActivity)
                ->handle();

            if (
                ($isFirst && $this->amount < $this->serviceActivity->first_free_amount) ||
                (!$isFirst && $this->amount < $this->serviceActivity->regular_free_amount)
            ) {
                $this->amount += $this->serviceActivity->amount;
            }
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function handle(): OrderServiceItem
    {
        if (!$this->order) {
            throw new Exception('請呼叫 order()', 500);
        }

        if (!$this->serviceItemType) {
            throw new Exception('請呼叫 service()', 500);
        }

        $this->calc();

        return DB::transaction(function () {
            /** @var OrderServiceItem $orderServiceItem */
            $orderServiceItem = OrderServiceItem::query()->create([
                'store_id' => $this->order->store_id,
                'member_id' => $this->order->member_id,
                'order_id' => $this->order->id,
                'service_item_id' => $this->serviceItem->id,
                'service_type_id' => $this->serviceType->id,
                'service_item_type_id' => $this->serviceItemType->id,
                'service_activity_id' => $this->serviceActivity?->id,
                'amount' => $this->amount,
                'comment' => $this->licensePlate,
                'delivery_type' => $this->needDesignatedDriver ? OrderServiceItem::DELIVERY_TYPE_DELIVERY : OrderServiceItem::DELIVERY_TYPE_PICKUP,
                'service_item_data' => $this->serviceItem->toArray(),
                'service_activity_data' => $this->serviceActivity?->toArray(),
            ]);

            /** @var ServiceItemType $addonServiceItemType */
            foreach ($this->addons as $addonServiceItemType) {
                $orderServiceItem->orderServiceItemAddons()->create([
                    'order_service_item_id' => $orderServiceItem->id,
                    'service_item_id' => $addonServiceItemType->service_item_id,
                    'service_type_id' => $this->serviceType->id,
                    'service_item_type_id' => $addonServiceItemType->id,
                    'service_item_type_data' => $addonServiceItemType->toArray(),
                ]);
            }

            return $orderServiceItem;
        });
    }
}
