<?php

namespace Mtsung\JoymapCore\Services\Member;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Action\AsObject;

/**
 * @method static void run(Member $data)
 */
class MemberFreezeService
{
    use AsObject;

    public function __construct()
    {
        Member::getToken();
    }

    /**
     * @throws Exception
     */
    public function handle(Member $member): void
    {
        DB::transaction(function () use ($member) {
            // 凍結天數
            $deleteDays = (int)env('MEMBER_DELETE_DAYS', 31);
            $deleteDateTime = Carbon::now()->addDays($deleteDays)->format('Y-m-d');

            $createLogData = [
                'delete_at' => $deleteDateTime,
            ];
            $member->deleteLogs()->create($createLogData);

            $updateData = [
                'status' => Member::STATUS_FREEZE,
            ];
            if (!$member->update($updateData)) {
                Log::error('MemberFreezeService Error', [
                    'member_id' => $member->id,
                    'data' => $updateData,
                ]);
                throw new Exception('System Error');
            }
        });
    }
}
