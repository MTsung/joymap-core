<?php

namespace Mtsung\JoymapCore\Services\Member;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Mtsung\JoymapCore\Facades\Rand;
use Mtsung\JoymapCore\Models\MemberPhoneValidate;
use Mtsung\JoymapCore\Repositories\Member\MemberPhoneValidateRepository;

class MemberPhoneValidateService
{
    public function __construct(
        private MemberPhoneValidateRepository $memberPhoneValidateRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function checkCode(string $phone, string $code): void
    {
        if (!$lastCode = $this->memberPhoneValidateRepository->getLastByPhone($phone)) {
            throw new Exception('驗證碼錯誤', 422);
        }

        if ($lastCode->code != $code) {
            throw new Exception('驗證碼錯誤', 422);
        }

        $now = Carbon::now();
        $lastCodeExpired = Carbon::create($lastCode->created_at)
            ->addMinutes(env('PHONE_VERIFY_CODE_EXPIRED_MIN', 10));

        if ($now > $lastCodeExpired) {
            throw new Exception('驗證碼過期', 422);
        }
    }

    public function buildCode(string $phone): MemberPhoneValidate|Builder
    {
        $createData = [
            'phone' => $phone,
            'code' => Rand::phoneVerifyCode(),
        ];

        return $this->memberPhoneValidateRepository->create($createData);
    }
}
