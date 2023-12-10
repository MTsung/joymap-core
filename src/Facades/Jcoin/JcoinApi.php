<?php

namespace Mtsung\JoymapCore\Facades\Jcoin;

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
 * @method static mixed add(AddJcoinParams $data)
 * @method static mixed sub(SubJcoinParams $data)
 * @method static mixed addBonus(AddBonusParams $data)
 * @method static mixed subBonus(SubBonusParams $data)
 * @method static mixed createUser(CreateUserParams $data)
 * @method static mixed getUserInfo(GetUserInfoParams $phone)
 * @method static mixed coinLogs(CoinLogsParams $data)
 * @method static mixed expired(ExpiredParams $data)
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

