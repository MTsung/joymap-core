<?php

namespace Mtsung\JoymapCore\Repositories\Notification;

use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\NotificationStorePay;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class NotificationStorePayRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(NotificationStorePay::class);
    }

    /**
     * 建立付款通知 並 建立對應的 Notification
     *
     * @param int $storeId
     * @param int $memberId
     * @param int $payLogId
     * @param int $status
     * @return mixed
     */
    public function createWithNotification(int $storeId, int $memberId, int $payLogId, int $status = 0): mixed
    {
        return $this->model()
            ->query()
            ->create([
                'store_id' => $storeId,
                'member_id' => $memberId,
                'pay_log_id' => $payLogId,
                'status' => $status,
            ])
            ->notify()
            ->create();
    }

    public function payLogHideButton(int $payLogId): int
    {
        return $this->model()
            ->query()
            ->where('pay_log_id', $payLogId)
            ->update([
                'status' => NotificationStorePay::STATUS_HIDE_BUTTON,
            ]);
    }
}
