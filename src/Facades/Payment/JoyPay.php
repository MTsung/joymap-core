<?php

namespace Mtsung\JoymapCore\Facades\Payment;

use Illuminate\Support\Facades\Facade;
use Mtsung\JoymapCore\Models\Store;

/**
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay bySpGateway()
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay byHiTrustPay()
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay storeId(int $storeId)
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay store(Store $store)
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay token(string $token)
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay money(float $money)
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay orderNo(string $orderNo)
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay expiry(string $expiry)
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay cardNo(string $cardNo)
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay cvc(string $cvc)
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay phone(string $phone)
 * @method static \Mtsung\JoymapCore\Helpers\Payment\JoyPay email(string $email)
 * @method static mixed pay()
 * @method static mixed bindCard()
 * @method static mixed cancel()
 * @method static mixed close()
 * @method static mixed query()
 *
 * @see \Mtsung\JoymapCore\Helpers\Payment\JoyPay
 */
class JoyPay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Payment\JoyPay::class;
    }
}
