<?php

namespace Mtsung\JoymapCore\Facades;

use Illuminate\Support\Facades\Facade;
use Mtsung\JoymapCore\Models\Store;


/**
 * @method static \Mtsung\JoymapCore\Helpers\Payment\HiTrustPay setStore(Store $store)
 * @method static int getAmountMultiplicand()
 * @method static mixed bindCard(array $params)
 * @method static mixed authRe(array $params)
 * @method static mixed cancel(array $params)
 * @method static mixed close(array $params)
 * @method static mixed pay(array $params)
 * @method static mixed query(array $params)
 *
 * @see \Mtsung\JoymapCore\Helpers\Payment\HiTrustPay
 */
class HiTrustPay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Payment\HiTrustPay::class;
    }
}
