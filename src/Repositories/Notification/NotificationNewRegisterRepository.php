<?php

namespace Mtsung\JoymapCore\Repositories\Notification;


use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\NotificationNewRegister;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class NotificationNewRegisterRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(NotificationNewRegister::class);
    }

    /**
     * 建立訂單通知 並 建立對應的 Notification
     *
     * @param int $memberId
     * @param int $coin
     * @return mixed
     */
    public function createWithNotification(int $memberId, int $coin): mixed
    {
        return $this->model()
            ->query()
            ->create([
                'member_id' => $memberId,
                'coin' => $coin,
            ])
            ->notify()
            ->create();
    }
}
