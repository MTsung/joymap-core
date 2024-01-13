<?php

namespace Mtsung\JoymapCore\Services\Member;

use Illuminate\Support\Str;
use Mtsung\JoymapCore\Facades\Rand;
use Mtsung\JoymapCore\Models\Member;
use Mtsung\JoymapCore\Params\Member\MemberGetOrCreateParams;
use Mtsung\JoymapCore\Repositories\Member\MemberRepository;
use Mtsung\JoymapCore\Action\AsObject;
use Propaganistas\LaravelPhone\PhoneNumber;
use Throwable;


/**
 * @method static Member run(MemberGetOrCreateParams $data)
 */
class MemberGetOrCreateService
{
    use AsObject;

    private string $phone;
    private array $phoneInfo;

    public function __construct(private MemberRepository $memberRepository)
    {
    }

    public function handle(MemberGetOrCreateParams $data): Member
    {
        $mainData = [];
        if (isset($data['apple_id'])) {
            if ($member = $this->memberRepository->getByAppleId($data['apple_id'])) {
                return $member;
            }
        }

        if (isset($data['full_phone'])) {
            $phoneInfo = $this->formatPhone($data['full_phone']);
            if ($member = $this->memberRepository->getByPhone($phoneInfo['phone'], $phoneInfo['prefix'])) {
                return $member;
            }

            $mainData += [
                'phone_prefix' => $phoneInfo['prefix'],
                'phone' => $phoneInfo['phone'],
            ];
        }

        $mainData += [
            'is_active' => $data['is_active'] ?? 0,
            'gender' => $data['gender'] ?? Member::GENDER_UNKNOWN,
            'member_grade_id' => $data['member_grade_id'] ?? Member::GRADE_NORMAL,
            'avatar' => ($data['avatar'] ?? '') ?:
                $this->defaultAvatar($data['gender'] ?? Member::GENDER_UNKNOWN),
            'member_no' => Rand::memberNo(),
            'invite_code' => Rand::inviteCode(),
        ];
        $data = array_merge($data->toArray(), $mainData);
        unset($data['full_phone']);

        return $this->memberRepository->create($data)->refresh();
    }

    private function defaultAvatar(int $gender): string
    {
        $url = 'https://storage.googleapis.com/joymap-store/default_avatar/default_';
        $mf = $gender == 0 ? 'f' : 'm';
        $randImgIndex = '0' . mt_rand(1, 5);

        return $url . $mf . '_' . $randImgIndex . '.png';
    }

    private function formatPhone(string $fullPhone): array
    {
        // 現場入座貴賓
        if ($fullPhone == Member::GUEST_PHONE) {
            return [
                'prefix' => null,
                'phone' => $fullPhone,
            ];
        }

        try {
            $phoneNumber = new PhoneNumber($fullPhone);
            $libPhone = $phoneNumber->toLibPhoneObject();
        } catch (Throwable $e) {
            // '0987086921'
            return [
                'prefix' => '886',
                'phone' => $fullPhone,
            ];
        }

        // '+886 987-086-921', '+886 987086921', '+886 987 086 921', '+886987086921'
        return [
            'prefix' => $libPhone->getCountryCode(),
            'phone' => Str::remove([' ', '-'], match ($phoneNumber->getCountry()) {
                // 只有台灣要存 09 開頭
                'TW' => $phoneNumber->formatNational(),
                default => $libPhone->getNationalNumber(),
            }),
        ];
    }
}
