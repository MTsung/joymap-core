<?php

namespace Mtsung\JoymapCore\Services\Store;

use Exception;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\Order;
use Mtsung\JoymapCore\Models\ServiceActivity;

/**
 * @method static self make()
 */
class CheckFirstTimeService
{
    use AsObject;

    private ?ServiceActivity $serviceActivity = null;

    private ?string $phone = null;

    private ?int $ignoreOrderId = null;

    public function serviceActivity(ServiceActivity $serviceActivity): CheckFirstTimeService
    {
        $this->serviceActivity = $serviceActivity;

        return $this;
    }

    public function phone(string $phone): CheckFirstTimeService
    {
        $this->phone = $phone;

        return $this;
    }

    public function ignoreOrderId(int $orderId): CheckFirstTimeService
    {
        $this->ignoreOrderId = $orderId;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function handle(): bool
    {
        if (!$this->serviceActivity) {
            throw new Exception('請呼叫 serviceActivity()', 500);
        }

        if (!$this->phone) {
            throw new Exception('請呼叫 phone()', 500);
        }

        $query = Order::query()
            ->where('store_id', $this->serviceActivity->store_id)
            ->whereHas('member', fn($q) => $q->where('phone', $this->phone))
            ->whereNotIn('status', Order::CANCEL);

        if ($this->ignoreOrderId) {
            $query->where('id', '!=', $this->ignoreOrderId);
        }

        return !$query->exists();
    }
}
