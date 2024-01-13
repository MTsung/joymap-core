<?php

namespace Mtsung\JoymapCore\Services\Jcoin;

use Carbon\Carbon;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Helpers\Jcoin\JcoinApi;
use Mtsung\JoymapCore\Models\CoinLog;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Models\PayLog;
use Mtsung\JoymapCore\Models\Store;
use Mtsung\JoymapCore\Params\Jcoin\AddJcoinParams;
use Mtsung\JoymapCore\Repositories\Jcoin\JcUserRepository;


/**
 * @method static self make()
 * @method static Member run(Member $member)
 */
class AddJcoinService
{
    use AsObject;

    private ?PayLog $payLog = null;

    private ?Store $store = null;

    private int $fromSource = CoinLog::FROM_SOURCE_JOYMAP;

    public function __construct(
        private JcoinApi         $jcoinApi,
        private JcUserRepository $jcUserRepository
    )
    {
        $this->jcoinApi->byJoymap();
    }

    public function byJoymap(): self
    {
        $this->fromSource = CoinLog::FROM_SOURCE_JOYMAP;
        $this->jcoinApi->byJoymap();

        return $this;
    }

    public function byTwdd(): self
    {
        $this->fromSource = CoinLog::FROM_SOURCE_TWDD;
        $this->jcoinApi->byTwdd();

        return $this;
    }

    public function store(Store $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function payLog(PayLog $payLog): self
    {
        $this->payLog = $payLog;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function handle(
        Member $member,
        string $title,
        int    $type,
        int    $amount,
        Carbon $expiredAt,
        string $comment = '',
        int    $systemTaskId = null,
    ): self
    {
        $coinLog = $member->coinLogs()->create([
            'store_id' => $this->store?->id,
            'pay_log_id' => $this->payLog?->id,
            'from_source' => $this->fromSource,
            'type' => $type,
            'coin' => $amount,
            'body' => $title,
            'coin_deadline' => $expiredAt,
            'system_task_id' => $systemTaskId,
        ]);

        if(isset($systemTaskId)){
            $member->systemTaskLogs()->create([
                'system_task_id' => $systemTaskId,
            ]);
        }

        $params = AddJcoinParams::make([
            'title' => $title,
            'order_id' => $this->payLog?->pay_no,
            'use_member_id' => $member->id,
            'use_mobile' => $member->phone,
            'user_id' => $member->coin_user_id,
            'token' => $member->coin_token,
            'transaction_type' => $type,
            'coins' => $amount,
            'comment' => $comment,
            'expired_at' => $expiredAt->toDateString(),
        ]);
        if ($coinRes = $this->jcoinApi->add($params)) {
            $coinLog->update([
                'coin_id' => $coinRes['result']['transaction_id'],
            ]);
        }

        return $this;
    }
}
