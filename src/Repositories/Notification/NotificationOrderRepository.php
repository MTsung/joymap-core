<?php

namespace Mtsung\JoymapCore\Repositories\Notification;


use Illuminate\Database\Eloquent\Model;
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
    public function createWithNotification(int $orderId, int $memberId, int $status): mixed
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

    public function hideCommentButton(int $orderId): int
    {
        return $this->model()
            ->query()
            ->where('order_id', $orderId)
            ->where('status', NotificationOrder::STATUS_SEATED)
            ->update(['status' => NotificationOrder::STATUS_SEATED_NO_BUTTON]);
    }
}
