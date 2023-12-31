<?php

namespace Mtsung\JoymapCore\Repositories\Pay;

use Illuminate\Database\Eloquent\Model;
use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Repositories\RepositoryInterface;

class PayLogRepository implements RepositoryInterface
{
    public function model(): Model
    {
        return app(PayLog::class);
    }

    public function hasByPayNo(string $payNo): bool
    {
        return $this->model()->query()
            ->where('pay_no', $payNo)
            ->exists();
    }

    public function getByStoreAndMember(int $storeId, int $memberId = 0)
    {
        return $this->model()
            ->query()
            ->select('pay_logs.*')
            ->where('pay_logs.store_id', $storeId)
            ->when($memberId > 0, function ($query) use ($memberId) {
                $query->where('pay_logs.member_id', $memberId);
            });
    }

    // 取得來店的資料(成功刷卡、全額折抵)
    public function getSuccessLog(int $storeId, int $memberId, array $dateRange = [])
    {
        return $this->getByStoreAndMember($storeId, $memberId)
            ->whereIn('pay_logs.status', PayLog::EFFECTIVE_USER_PAY_STATUS)
            ->when(count($dateRange) == 2, function ($query) use ($dateRange) {
                $query->whereBetween('pay_logs.created_at', $dateRange);
            });
    }
}
