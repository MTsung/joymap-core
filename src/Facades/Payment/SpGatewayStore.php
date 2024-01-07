<?php

namespace Mtsung\JoymapCore\Facades\Payment;

use Illuminate\Support\Facades\Facade;


/**
 * @method static array createStorePreSetData(array $postData)
 * @method static array updateStorePreSetData(array $postData)
 * @method static mixed createStore(array $postDataArr)
 * @method static mixed updateStore(array $postDataArr)
 * @method static bool verifyCallback(array $reqData)
 *
 * @see \Mtsung\JoymapCore\Helpers\Payment\SpGatewayStore
 */
class SpGatewayStore extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Payment\SpGatewayStore::class;
    }
}
