<?php

namespace Mtsung\JoymapCore\Helpers\Jcoin;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mtsung\JoymapCore\Params\Jcoin\AddBonusParams;
use Mtsung\JoymapCore\Params\Jcoin\AddJcoinParams;
use Mtsung\JoymapCore\Params\Jcoin\CoinLogsParams;
use Mtsung\JoymapCore\Params\Jcoin\CreateUserParams;
use Mtsung\JoymapCore\Params\Jcoin\ExpiredParams;
use Mtsung\JoymapCore\Params\Jcoin\GetUserInfoParams;
use Mtsung\JoymapCore\Params\Jcoin\SubBonusParams;
use Mtsung\JoymapCore\Params\Jcoin\SubJcoinParams;
use Throwable;

class JcoinApi
{
    protected string $baseUrl;
    private string $user;
    private string $password;
    protected mixed $log;

    public function __construct()
    {
        $this->baseUrl = config('joymap.jcoin.domain');

        $this->log = Log::stack([
            config('logging.default'),
            'jcoin',
        ]);
    }

    public function byJoymap(): JcoinApi
    {
        $this->user = config('joymap.jcoin.joymap_user');
        $this->password = config('joymap.jcoin.joymap_pwd');

        return $this;
    }

    public function byTwdd(): JcoinApi
    {
        $this->user = config('joymap.jcoin.twdd_user');
        $this->password = config('joymap.jcoin.twdd_pwd');

        return $this;
    }

    // 增加 JCoin
    public function add(AddJcoinParams $data): array|bool
    {
        return $this->callApi('POST', '/coin-deposit', $data);
    }

    // 減少 JCoin
    public function sub(SubJcoinParams $data): bool|array
    {
        return $this->callApi('POST', '/coin-use', $data);
    }

    // 增加分潤
    public function addBonus(AddBonusParams $data): bool|array
    {
        return $this->callApi('POST', '/coin-deposit-profit-share', $data);
    }

    // 提領分潤
    public function subBonus(SubBonusParams $data): bool|array
    {
        return $this->callApi('POST', '/coin-withdrawn', $data);
    }

    // 建立 JCoin 帳戶
    public function createUser(CreateUserParams $data): bool|array
    {
        return $this->callApi('POST', '/user/create', $data);
    }

    // 取得用戶 JCoin 帳戶資料
    public function getUserInfo(GetUserInfoParams $data): bool|array
    {
        return $this->callApi('POST', '/user/userid', $data);
    }

    // 取得 JCoin 使用紀錄
    public function coinLogs(CoinLogsParams $data): bool|array
    {
        return $this->callApi('POST', '/logs', $data);
    }

    // 取得即將過期的用戶和點數
    public function expired(ExpiredParams $data): bool|array
    {
        $urlPath = '/expired/' . $data->start_ts . ',' . $data->end_ts;

        return $this->callApi('GET', $urlPath, $data);
    }

    private function callApi(string $type, string $urlPath, array|Collection $data = []): array|bool
    {
        $client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'user' => $this->user,
                'pw' => $this->password,
            ],
        ]);

        try {
            if ($writeLog = Str::upper($type) == 'POST') {
                $this->log->info('jcoin send', [$data]);
            }

            $res = $client->request(
                $type,
                $urlPath,
                array_filter(['json' => $data])
            )->getBody()->getContents();

            if ($writeLog) {
                $this->log->info('jcoin res', [$res]);
            }

            $resBody = json_decode($res, true);

            if ($resBody['code'] == 1) {
                return $resBody['return'];
            }

            throw new Exception($resBody['msg'] ?? '');
        } catch (ClientException $e) {
            $this->log->error(__METHOD__ . ' ClientException: ', [$e]);
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' error: ', [$e]);
        }

        return false;
    }
}
