<?php

namespace Mtsung\JoymapCore\Helpers\Payment;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Throwable;

class SpGatewayStore
{
    protected string $key;
    protected string $iv;
    protected string $partnerId;
    protected string $merchantPrefix;
    protected mixed $log;

    public function __construct()
    {
        $this->key = config('joymap.spgateway.store.merchant_hash_key');
        $this->iv = config('joymap.spgateway.store.merchant_iv_key');
        $this->partnerId = config('joymap.spgateway.store.partner_id');
        $this->merchantPrefix = config('joymap.spgateway.store.merchant_prefix');
        $this->log = Log::stack([
            config('logging.default'),
            'spgateway-store',
        ]);
    }

    private function addpadding($string, $blocksize = 32): string
    {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);

        return $string;
    }

    public function createStorePreSetData(array $postData = []): array
    {
        $postData['Version'] = config('joymap.spgateway.store.create.version');
        $postData['TimeStamp'] = time();
        $postData['AgreedFee'] = 'CREDIT:' . config('joymap.spgateway.store.agreed_fee');
        $postData['AgreedDay'] = 'CREDIT:' . config('joymap.spgateway.store.agreed_day');
        $postData['PaymentType'] = 'CREDIT:1';
        $postData['Withdraw'] = 3;
        $postData['WithdrawSetting'] = 3;
        $postData['MerchantID'] = $this->merchantPrefix . $postData['MerchantID'];

        return $postData;
    }

    public function updateStorePreSetData(array $postData = []): array
    {
        $postData['Version'] = config('joymap.spgateway.store.update.version');
        $postData['TimeStamp'] = time();
        $postData['AgreedFee'] = 'CREDIT:' . config('joymap.spgateway.store.agreed_fee');
        $postData['AgreedDay'] = 'CREDIT:' . config('joymap.spgateway.store.agreed_day');
        $postData['PaymentType'] = 'CREDIT:1';

        return $postData;
    }

    public function createStore(array $postDataArr = [])
    {
        $client = new Client();
        $url = config('joymap.spgateway.store.create.url');

        $postData = http_build_query($postDataArr);

        try {
            $res = $client->request('POST', $url, [
                'form_params' => [
                    'PartnerID_' => $this->partnerId,
                    'PostData_' => trim(bin2hex(openssl_encrypt(
                        $this->addpadding($postData),
                        'aes-256-cbc',
                        $this->key,
                        OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
                        $this->iv
                    )))
                ]
            ]);

            $resBody = json_decode($res->getBody()->getContents(), true);

            $this->log->info('createStore Response', [
                'response' => $resBody,
                'postData' => $postDataArr
            ]);

            return $resBody;
        } catch (ClientException $e) {
            $this->log->error('createStore ClientException', [$e]);
        } catch (Throwable $th) {
            $this->log->error('createStore Error', [$th]);
        }

        return false;
    }

    public function updateStore(array $postDataArr = [])
    {
        $client = new Client();
        $url = config('joymap.spgateway.store.update.url');

        $postData = http_build_query($postDataArr);

        try {
            $res = $client->request('POST', $url, [
                'form_params' => [
                    'PartnerID_' => $this->partnerId,
                    'PostData_' => trim(bin2hex(openssl_encrypt(
                        $this->addpadding($postData),
                        'aes-256-cbc',
                        $this->key,
                        OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
                        $this->iv
                    )))
                ]
            ]);

            $resBody = json_decode($res->getBody()->getContents(), true);

            $this->log->info('updateStore Response', [
                'response' => $resBody,
                'postData' => $postDataArr
            ]);

            return $resBody;
        } catch (ClientException $e) {
            $this->log->error('updateStore ClientException', [$e]);
        } catch (Throwable $th) {
            $this->log->error('updateStore Error', [$th]);
        }

        return false;
    }

    /**
     * 驗證藍新商店審核Call Back 是不是真的藍新Call的
     *
     * @param array $reqData
     * @return bool
     */
    public function verifyCallback(array $reqData): bool
    {
        $check_code = [
            "MerchantID" => $reqData['MerchantID'],
            "Date" => $reqData['Date'],
            "UseInfo" => $reqData['UseInfo'],
            "CreditInst" => $reqData['CreditInst'],
            "CreditRed" => $reqData['CreditRed'],
        ];

        ksort($check_code);
        $check_str = http_build_query($check_code);
        $CheckCode = "HashIV=" . $this->iv . "&$check_str&HashKey=" . $this->key;
        $CheckCode = strtoupper(hash("sha256", $CheckCode));

        return $reqData['CheckCode'] == $CheckCode;
    }
}
