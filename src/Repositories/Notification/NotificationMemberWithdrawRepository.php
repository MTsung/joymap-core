<?php

namespace Mtsung\JoymapCore\Repositories\Notification;


use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\NotificationMemberWithdraw;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class NotificationMemberWithdrawRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(NotificationMemberWithdraw::class);
    }

    /**
     * 建立訂單通知 並 建立對應的 Notification
     *
     * @param int $memberId
     * @param int $status
     * @param int $coin
     * @param int $coinLogId
     * @return mixed
     */
    public function createWithNotification(int $memberId, int $status, int $coin, int $coinLogId): mixed
    {
        return $this->model()
            ->query()
            ->create([
                'member_id' => $memberId,
                'status' => $status,
                'coin' => $coin,
                'coin_log_id' => $coinLogId,
            ])
            ->notify()
            ->create();
    }
}
