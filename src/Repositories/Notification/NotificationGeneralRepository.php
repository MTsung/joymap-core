<?php

namespace Mtsung\JoymapCore\Repositories\Notification;


use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\NotificationGeneral;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class NotificationGeneralRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(NotificationGeneral::class);
    }

    /**
     * 建立訂單通知 並 建立對應的 Notification
     *
     * @param int $memberId
     * @param string $title
     * @param string $body
     * @param string $action
     * @param mixed|array $data
     * @return mixed
     */
    public function createWithNotification(int $memberId, string $title, string $body, string $action = '', $data = []): mixed
    {
        return $this->model()
            ->query()
            ->create([
                'member_id' => $memberId,
                'title' => $title,
                'body' => $body,
                'action' => $action,
                'data' => $data,
            ])
            ->notify()
            ->create();
    }
}
