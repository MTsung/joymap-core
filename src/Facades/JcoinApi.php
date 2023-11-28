<?php

namespace Mtsung\JoymapCore\Facades;

use Illuminate\Support\Facades\Facade;
use Mtsung\JoymapCore\Params\Jcoin\AddBonusParams;
use Mtsung\JoymapCore\Params\Jcoin\AddJcoinParams;
use Mtsung\JoymapCore\Params\Jcoin\CoinLogsParams;
use Mtsung\JoymapCore\Params\Jcoin\CreateUserParams;
use Mtsung\JoymapCore\Params\Jcoin\ExpiredParams;
use Mtsung\JoymapCore\Params\Jcoin\GetUserInfoParams;
use Mtsung\JoymapCore\Params\Jcoin\SubBonusParams;
use Mtsung\JoymapCore\Params\Jcoin\SubJcoinParams;

/**
 * @method static \Mtsung\JoymapCore\Helpers\Jcoin\JcoinApi byJoymap()
 * @method static \Mtsung\JoymapCore\Helpers\Jcoin\JcoinApi byTwdd()
 * @method static array|bool add(AddJcoinParams $data)
 * @method static array|bool sub(SubJcoinParams $data)
 * @method static array|bool addBonus(AddBonusParams $data)
 * @method static array|bool subBonus(SubBonusParams $data)
 * @method static array|bool createUser(CreateUserParams $data)
 * @method static array|bool getUserInfo(GetUserInfoParams $phone)
 * @method static array|bool coinLogs(CoinLogsParams $data)
 * @method static array|bool expired(ExpiredParams $data)
 *
 * @see \Mtsung\JoymapCore\Helpers\Jcoin\JcoinApi
 */
class JcoinApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Mtsung\JoymapCore\Helpers\Jcoin\JcoinApi::class;
    }
}

