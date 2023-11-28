<?php

namespace Mtsung\JoymapCore\Helpers;

use Faker\Generator as Faker;
use Faker\Provider\Base as FakerBase;
use Illuminate\Support\Carbon;
use Mtsung\JoymapCore\Repositories\Member\MemberRepository;
use Mtsung\JoymapCore\Repositories\MemberDealer\MemberDealerRepository;
use Mtsung\JoymapCore\Repositories\Order\OrderRepository;
use Mtsung\JoymapCore\Repositories\Pay\PayLogRepository;

class Rand
{
    public function __construct(
        private MemberRepository       $memberRepository,
        private MemberDealerRepository $memberDealerRepository,
        private OrderRepository        $orderRepository,
        private PayLogRepository       $payLogRepository,
    )
    {
    }

    public function memberNo(): string
    {
        $memberNo = $this->numberString(prefix: 'J-');
        if ($this->memberRepository->hasByMemberNo($memberNo)) {
            return $this->memberNo();
        }

        return $memberNo;
    }

    public function phoneVerifyCode(): string
    {
        return $this->numberString(6, 'JM-');
    }

    public function inviteCode(): string
    {
        $inviteCode = $this->englishNumberString();
        if ($this->memberRepository->hasByInviteCode($inviteCode)) {
            return $this->inviteCode();
        }

        return $inviteCode;
    }

    public function dealerNo(): string
    {
        $dealerNo = $this->numberString(9);
        if ($this->memberDealerRepository->hasByDealerNo($dealerNo)) {
            return $this->dealerNo();
        }

        return $dealerNo;
    }

    public function creditNo(): string
    {
        $dateString = Carbon::now()->format('ymd');
        return 'JMC' . $dateString . $this->numberString();
    }

    public function payNo(): string
    {
        $dateString = Carbon::now()->format('ymd');
        $payNo = $this->numberString(5, 'JP-' . $dateString);
        if ($this->payLogRepository->hasByPayNo($payNo)) {
            return $this->payNo();
        }

        return $payNo;
    }

    public function orderNo(): string
    {
        $orderNo = $this->numberString(7, 'BK-');
        if ($this->orderRepository->hasByOrderNo($orderNo)) {
            return $this->orderNo();
        }

        return $orderNo;
    }

    // 取得長度為 $length，前綴為 $prefix 的數字字串(不會有4)
    public function numberString(int $length = 8, string $prefix = ''): string
    {
        $temp = [];

        while (count($temp) < $length) {
            $n = mt_rand(0, 9);
            // 不要出現 4
            if ($n != 4) {
                $temp[] = $n;
            }
        }

        return $prefix . implode('', $temp);
    }

    // 取得長度為 $length，前綴為 $prefix 的英文數字字串(不會有4)
    public function englishNumberString(int $length = 6, string $prefix = ''): string
    {
        $faker = new Faker;
        $faker = new FakerBase($faker);
        $regex = '[ABCDEFGHJKLMNPQRSTUVWXYZ2356789]{' . $length . '}';

        return $prefix . $faker->regexify($regex);
    }
}
