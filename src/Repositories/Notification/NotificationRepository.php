<?php

namespace Mtsung\JoymapCore\Repositories\Notification;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\Notification;
use Mtsung\JoymapCore\Models\NotificationMemberWithdraw;
use Mtsung\JoymapCore\Models\NotificationOrder;
use Mtsung\JoymapCore\Models\NotificationPlatform;
use Mtsung\JoymapCore\Models\NotificationStorePay;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class NotificationRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(Notification::class);
    }

    /**
     * 取得會員未讀訊息數量
     * @param int $memberId
     * @param int $month
     * @return int
     */
    public function getMemberUnreadWithInMonthCount(int $memberId, int $month = 3): int
    {
        return $this->getMemberWithInMonth($memberId, $month)
            ->doesntHave('notificationMemberRead')
            ->count();
    }

    public function getMemberWithInMonth(int $memberId, int $month = 3): Builder
    {
        $createdAt = Carbon::now()->subMonths($month);

        return $this->model()
            ->query()
            ->withExists('notificationMemberRead')
            ->whereHasMorph(
                'notify',
                [NotificationOrder::class, NotificationPlatform::class, NotificationStorePay::class],
                function ($query, $type) use ($memberId) {
                    if ($type == NotificationOrder::class) {
                        $query->where('member_id', $memberId);
                        $query->where('status', '!=', 999);
                    }
                    if ($type == NotificationPlatform::class) {
                        $query->notifiable(); //NotificationPlatform scopeNotifiable
                    }
                    if ($type == NotificationStorePay::class || $type == NotificationMemberWithdraw::class) {
                        $query->where('member_id', $memberId);
                    }
                }
            )
            ->where('created_at', '>=', $createdAt);
    }
}
