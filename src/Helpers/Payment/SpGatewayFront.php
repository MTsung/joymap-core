<?php

namespace Mtsung\JoymapCore\Helpers\Payment;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SpGatewayFront
{
    protected mixed $log;

    private string $cipherMethod = 'AES-256-CBC';
    private string $version = '1.5';
    private string $respondType = 'JSON';

    private string $merchantId;
    private string $hashKey;
    private string $hashIv;

    function __construct()
    {
        $this->log = Log::stack([
            config('logging.default'),
            'spgateway-mpg',
        ]);

        $this->merchantId = config('joymap.pay.channels.spgateway.mpg.merchant_id');
        $this->hashKey = config('joymap.pay.channels.spgateway.mpg.hash_key');
        $this->hashIv = config('joymap.pay.channels.spgateway.mpg.hash_iv');
    }

    /**
     * 給前端的資訊
     * @param array $params
     * @param int $instFlag 幾期
     * @return array
     */
    public function getFromInfo(array $params, int $instFlag): array
    {
        $data = [
            'MerchantID' => $this->merchantId,
            'Version' => $this->version,
            'RespondType' => $this->respondType,
            'TimeStamp' => Carbon::now()->timestamp,
            'MerchantOrderNo' => $params['credit_no'],
            'Amt' => $params['amount'],
            'ItemDesc' => $params['item_desc'] ?? 'Joymap享樂地圖',
            'ClientBackURL' => $params['client_back_url'] ?? config('joymap.pay.channels.spgateway.mpg.client_back_url'),
            'ReturnURL' => $params['return_url'] ?? config('joymap.pay.channels.spgateway.mpg.return_url'),
            'NotifyURL' => $params['notify_url'] ?? config('joymap.pay.channels.spgateway.mpg.notify_url'),
            'Email' => $params['email'],
            'CREDIT' => $instFlag === 1 ? 1 : 0,
            'InstFlag' => $instFlag > 1 ? $instFlag : 0,
        ];

        $this->log->info('data', $data);
        $tradeInfo = $this->getTradeInfo($data);
        $tradeSha = $this->getTradeSha($tradeInfo);

        return [
            'url' => config('joymap.pay.channels.spgateway.mpg.credit_card_url'),
            'from_data' => [
                'MerchantID' => $this->merchantId,
                'TradeInfo' => $tradeInfo,
                'TradeSha' => $tradeSha,
                'Version' => $this->version,
            ],
        ];
    }

    /**
     * 檢查回傳資訊是否正確
     * @param string $tradeSha
     * @param string $tradeInfo
     * @return bool
     */
    public function checkTradeSha(string $tradeSha, string $tradeInfo): bool
    {
        if ($tradeSha != $this->getTradeSha($tradeInfo)) {
            return false;
        }
        return true;
    }

    /**
     * 1. 將交易資料的 AES 加密字串前後加上商店專屬加密 HashKey 與商店專屬加密 HashIV。
     * 2. 將串聯後的字串用 SHA256 壓碼後轉大寫。
     * @param string $tradeInfo
     * @return string
     */
    private function getTradeSha(string $tradeInfo): string
    {
        $tempString = 'HashKey=' . $this->hashKey . '&' . $tradeInfo . '&HashIV=' . $this->hashIv;
        return strtoupper(hash('sha256', $tempString));
    }

    /**
     * 將交易資料透過商店專屬加密 HashKey 與商店專屬加密 HashIV，產生 AES 256 加密交易資料。
     * 文件 AES 256 加密語法範例
     * @param $data
     * @return string
     */
    private function getTradeInfo($data): string
    {
        $tempString = http_build_query($data);
        $tempString = $this->addPadding($tempString);
        if ($this->hashKey && $this->hashIv) {
            $tempString = openssl_encrypt(
                $tempString,
                $this->cipherMethod,
                $this->hashKey,
                OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
                $this->hashIv
            );
        }
        $tempString = bin2hex($tempString);
        return trim($tempString);
    }

    private function addPadding($string, $blockSize = 32): string
    {
        $len = strlen($string);
        $pad = $blockSize - ($len % $blockSize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }

    /**
     * 文件 AES 256 解密語法範例
     * @param string $tradeInfo
     * @return array
     */
    public function decodeTradeInfo(string $tradeInfo): array
    {
        $tempString = hex2bin($tradeInfo);
        if ($this->hashKey && $this->hashIv) {
            $tempString = openssl_decrypt(
                $tempString,
                $this->cipherMethod,
                $this->hashKey,
                OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
                $this->hashIv
            );
        }
        $tempString = $this->stripPadding($tempString);
        return json_decode($tempString, true);
    }

    private function stripPadding($string): bool|string
    {
        $slaSt = ord(substr($string, -1));
        $slaStc = chr($slaSt);
        if (preg_match("/$slaStc{" . $slaSt . "}/", $string)) {
            return substr($string, 0, strlen($string) - $slaSt);
        }
        return false;
    }
}
