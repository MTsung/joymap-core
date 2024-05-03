<?php

namespace Mtsung\JoymapCore\Services\Member;


use Exception;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Params\Member\MemberGetOrCreateParams;
use Mtsung\JoymapCore\Params\Member\MemberRegisterParams;
use Mtsung\JoymapCore\Repositories\Member\MemberRepository;

/**
 * @method static Member run(MemberRegisterParams $data)
 */
class MemberRegisterService
{
    use AsObject;

    public function __construct(private MemberRepository $memberRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(MemberRegisterParams $data): Member
    {
        return DB::transaction(function () use ($data) {
            $now = Carbon::now();

            // 目前只有台灣手機
            $data['phone_prefix'] = '886';
            $data['full_phone'] = '+' . $data['phone_prefix'] . $data['phone'];

            // 找有沒有從免登入訂位管道建立過的會員
            if ($member = $this->memberRepository->getByPhone($data['phone'], $data['phone_prefix'])) {
                $this->checkMember($member);
            }

            $data['is_active'] = 1;
            $data['register_at'] = $now;

            // 密碼 hash
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // 邀請人 id
            if (!empty($data['from_invite_code'])) {
                $fromInviteMember = $this->memberRepository->getByInviteCode($data['from_invite_code']);
                if(is_null($fromInviteMember)) {
                    throw new Exception(__('joymap::member.message.invite.not_existed'), 403);
                }

                if(!$fromInviteMember->is_joy_dealer) {
                    throw new Exception(__('joymap::member.message.invite.not_member_dealer'), 403);
                }

                $data['from_invite_id'] = $fromInviteMember?->id ?? null;
            }

            if ($member) {
                $updateData = $this->formatUpdateData($data);
                if (!$member->update($updateData)) {
                    Log::error('MemberRegisterService Error', [
                        'member_id' => $member->id,
                        'data' => $updateData,
                    ]);
                    throw new Exception('System Error');
                }

                $member->refresh();
            } else {
                $params = $this->formatCreateData($data);
                $member = MemberGetOrCreateService::run($params);
            }

            $createGradeLogData = [
                'member_grade_id' => Member::GRADE_NORMAL,
                'start_at' => $now,
            ];
            $member->memberGradeChangeLogs()->create($createGradeLogData);

            return $member;
        });
    }


    /**
     * @throws Exception
     */
    private function checkMember(Member $member): void
    {
        // 凍結時手機號碼還不會變，不給註冊
        if ($member->status == Member::STATUS_FREEZE) {
            throw new Exception(__('joymap::member.message.register.account_is_freeze'), 403);
        }

        // 手機號碼已註冊過
        if ($member->is_active) {
            throw new Exception(__('joymap::member.message.register.phone_existed'), 403);
        }
    }

    private function formatUpdateData(Collection $data): array
    {
        return $data->only([
            'name',
            'gender',
            'password',
            'is_active',
            'register_at',
            'register_device',
            'from_invite_id',
            'avatar',
            'apple_id',
            'facebook_id',
            'google_id',
        ])->toArray();
    }

    /**
     * @throws Exception
     */
    private function formatCreateData(Collection $data): MemberGetOrCreateParams
    {
        return MemberGetOrCreateParams::make(
            $data->only([
                'full_phone',
                'name',
                'gender',
                'password',
                'is_active',
                'register_at',
                'from_source',
                'register_device',
                'from_invite_id',
                'avatar',
                'apple_id',
                'facebook_id',
                'google_id',
            ])->toArray()
        );
    }
}
