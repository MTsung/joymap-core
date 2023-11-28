<?php

namespace Mtsung\JoymapCore\Facades\Payment;

use Illuminate\Support\Facades\Facade;
use Mtsung\JoymapCore\Models\Store;


/**
 * @method static \Mtsung\JoymapCore\Helpers\Payment\SpGateway setStore(Store $store)
 * @method static int getAmountMultiplicand()
 * @method static mixed bindCard(array $params)
 * @method static mixed pay(array $params)
 * @method static mixed cancel(array $params)
 * @method static mixed close(array $params)
 * @method static mixed query(array $params)
 * @method static mixed chargeInstruct(array $params)
 *
 * @see \Mtsung\JoymapCore\Helpers\Payment\SpGateway
 */
class SpGateway extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Payment\SpGateway::class;
    }
}
