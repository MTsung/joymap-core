<?php

namespace Mtsung\JoymapCore\Repositories\Notification;


use Mtsung\JoymapCore\Models\Model;
use Mtsung\JoymapCore\Models\NotificationOrder;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class NotificationOrderRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(NotificationOrder::class);
    }

    /**
     * 建立訂單通知 並 建立對應的 Notification
     *
     * @param int $orderId
     * @param int $memberId
     * @param int $status
     * @return mixed
     */
    public function createWithNotification(int $orderId, int $memberId, int $status)
    {
        return $this->model()
            ->query()
            ->create([
                'order_id' => $orderId,
                'member_id' => $memberId,
                'status' => $status,
            ])
            ->notify()
            ->create();
    }
}
