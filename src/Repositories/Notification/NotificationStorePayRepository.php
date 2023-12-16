<?php

namespace App\Repositories;

use Mtsung\JoymapCore\Models\Model;
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
     * @param int $paylogId
     * @param int $status
     * @return mixed
     */
    public function createWithNotification(int $storeId, int $memberId, int $paylogId, int $status = 0): mixed
    {
        return $this->model()
            ->query()
            ->create([
                'store_id' => $storeId,
                'member_id' => $memberId,
                'pay_log_id' => $paylogId,
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
