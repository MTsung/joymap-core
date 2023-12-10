<?php

namespace Mtsung\JoymapCore\Services\Member;

use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Facades\Jcoin\JcoinApi;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Params\Jcoin\CreateUserParams;
use Mtsung\JoymapCore\Params\Jcoin\GetUserInfoParams;
use Mtsung\JoymapCore\Repositories\Member\JcUserRepository;
use Mtsung\JoymapCore\Action\AsObject;


/**
 * @method static Member run(Member $member)
 */
class JcoinUserGetOrCreateService
{
    use AsObject;

    private string $phone;
    private array $phoneInfo;

    public function __construct(
        private JcoinApi         $jcoinApi,
        private JcUserRepository $jcUserRepository
    )
    {
    }

    public function byJoymap(): self
    {
        $this->jcoinApi->byJoymap();

        return $this;
    }

    public function byTwdd(): self
    {
        $this->jcoinApi->byTwdd();

        return $this;
    }

    /*
     * e.g. response
     *  {
     *      "user_id": "JCM00000001",
     *      "nickname": "david",
     *      "mobile": "0936173312",
     *      "coins_balance": 100,
     *      "coins_nums": 4,
     *      "coins_use_nums": 2,
     *      "token": "asdfasfasfasfasfasdff",
     *      "last_transaction_id": "61650eb4c29b4",
     *      "partner_name": "joymap",
     *      "coins_first_expired": {
     *          "expired_at": "2021-12-12 23:59:59",
     *          "coins": 100
     *      }
     *  }
     */
    public function handle(Member $member): array|bool
    {
        // 不處理停權跟幽靈
        if ($member->is_active != 1 || $member->status != Member::STATUS_NORMAL) {
            return false;
        }

        // 取得資訊
        $params = GetUserInfoParams::make([
            'mobile' => $member->phone
        ]);
        $coinInfo = $this->jcoinApi->getUserInfo($params);

        // 不存在就建立一個
        if ($coinInfo === false) {
            $params = CreateUserParams::make([
                'mobile' => $member->phone,
                'nickname' => $member->name ?: $member->phone,
            ]);
            $coinInfo = $this->jcoinApi->createUser($params);
            if ($coinInfo === false) {
                return false;
            }
        }

        if (!isset($member->coin_token, $member->coin_user_id, $member->jc_user_id)) {
            $updateData = [
                'coin_token' => $coinInfo['token'],
                'coin_user_id' => $coinInfo['user_id'],
                // 原本 jc_ 系列的 table 不是同一個資料庫，後來整合多了 jc_user_id 這個欄位方便關聯
                'jc_user_id' => $this->jcUserRepository->getByUserId($coinInfo['user_id'])?->id,
            ];
            if (!$member->update($updateData)) {
                Log::error(__METHOD__ . ' Error: ', [
                    'member_id' => $member->id,
                    'data' => $updateData,
                ]);
            }
        }

        return $coinInfo;
    }
}
