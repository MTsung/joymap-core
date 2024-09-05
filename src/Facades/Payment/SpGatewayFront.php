<?php

namespace Mtsung\JoymapCore\Facades\Payment;

use Illuminate\Support\Facades\Facade;


/**
 * @method static array getFromInfo(array $params, int $instFlag)
 * @method static bool checkTradeSha(string $tradeSha, string $tradeInfo)
 * @method static array decodeTradeInfo(string $tradeInfo)
 *
 * @see \Mtsung\JoymapCore\Helpers\Payment\SpGatewayFront
 */
class SpGatewayFront extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Payment\SpGatewayFront::class;
    }
}
