<?php

namespace Mtsung\JoymapCore\Helpers\Payment;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Events\Notify\SendNotifyEvent;
use Mtsung\JoymapCore\Facades\Notification\LineNotification;
use Mtsung\JoymapCore\Models\Store;
use Throwable;

class SpGateway implements PayInterface
{
    private string $url;
    private Store $store;
    protected mixed $log;

    public function __construct()
    {
        $this->log = Log::stack([
            config('logging.default'),
            'spgateway-pay',
        ]);
    }

    public function getAmountMultiplicand(): int
    {
        return 1;
    }

    /**
     * @throws Exception
     */
    public function store(Store $store): SpGateway
    {
        $this->store = $store;
        if (
            empty($this->store->storeSpgateway->merchant_id) ||
            empty($this->store->storeSpgateway->merchant_iv_key) ||
            empty($this->store->storeSpgateway->merchant_hash_key)
        ) {
            throw new Exception('店家尚未啟用', 500);
        }
        return $this;
    }

    // 綁卡
    public function bindCard(array $params)
    {
        $this->url = config('joymap.pay.channels.spgateway.credit_card_url');
        $params = [
            'TimeStamp' => time(),
            'Version' => '2.1',
            'P3D' => 1,
            'UseFor' => 1,// 1=APP
            'NotifyURL' => config('joymap.pay.channels.spgateway.bind_notify_url'),
            'ReturnURL' => config('joymap.pay.channels.spgateway.bind_return_url'),
            'MerchantOrderNo' => $params['orderNumber'],
            'Amt' => $params['amount'],
            'ProdDesc' => $params['orderDesc'],
            'PayerEmail' => $params['email'],
            'CardNo' => $params['cardNo'],
            'Exp' => $params['expiry'],
            'CVC' => $params['cvc'],
            'TokenSwitch' => 'get',// 拿 token 使用 get
            'TokenTerm' => 'JOYMAP',
            'TokenLife' => $params['expiry'],
        ];

        $params = $this->preparePostData($params);
        return $this->post($params);
    }

    // 刷卡
    public function pay(array $params)
    {
        $this->url = config('joymap.pay.channels.spgateway.credit_card_url');
        $params = [
            'TimeStamp' => time(),
            'Version' => '2.1',
            'UseFor' => 1,// 1=APP
            'NotifyURL' => config('joymap.pay.channels.spgateway.pay_notify_url'),
            'ReturnURL' => config('joymap.pay.channels.spgateway.pay_return_url'),
            'Inst' => $params['inst'] ?? 0,
            'MerchantOrderNo' => $params['orderNumber'],
            'Amt' => $params['amount'],
            'ProdDesc' => $params['orderDesc'],
            'PayerEmail' => $params['email'],
            'TokenValue' => $params['token'],
            'TokenTerm' => 'JOYMAP',
            'TokenSwitch' => 'on',// 刷卡使用 on
        ];
        $params = $this->preparePostData($params);
        return $this->post($params);
    }

    // 取消授權
    public function cancel($params)
    {
        $this->url = config('joymap.pay.channels.spgateway.cancel_url');
        $params = [
            'RespondType' => 'JSON',
            'Version' => '1.0',
            'Amt' => $params['amount'],
            'MerchantOrderNo' => $params['orderNumber'],
            'TimeStamp' => time(),
            'IndexType' => 1,
        ];
        $params = $this->preparePostData($params);
        return $this->post($params);
    }

    // 退刷
    public function close($params)
    {
        $this->url = config('joymap.pay.channels.spgateway.close_url');
        $params = [
            'RespondType' => 'JSON',
            'Version' => '1.0',
            'Amt' => $params['amount'],
            'MerchantOrderNo' => $params['orderNumber'],
            'TimeStamp' => time(),
            'IndexType' => 1,
            'CloseType' => 2,
        ];
        $params = $this->preparePostData($params);
        return $this->post($params);
    }

    // 查詢訂單
    public function query($params)
    {
        $this->url = config('joymap.pay.channels.spgateway.query_url');
        $params = [
            'MerchantID' => $this->store->storeSpgateway->merchant_id,
            'MerchantOrderNo' => $params['orderNumber'],
            'Amt' => $params['amount'],
        ];
        $params = $this->preparePostDataHasCkcekValue($params);
        return $this->post($params);
    }

    // 跟 store 抽費用到 金流合作推廣商 (SPGATEWAY_STORE_PARTNER_ID)
    public function chargeInstruct($params)
    {
        $this->url = config('joymap.pay.channels.spgateway.charge_instruct_url');
        $params = [
            'Version' => '1.0',
            'TimeStamp' => time(),
            'MerchantID' => $this->store->storeSpgateway->merchant_id,
            'Amount' => $params['amount'],
            'FeeType' => $params['fee_type'] ?? 4, // 4 其他費用
            'BalanceType' => $params['balance_type'] ?? 0, // 0為正向，1為負向。 例:平台商向合作商店收取費用時，則 填入0;平台商退還費用給合作商店 時，則填入1。
        ];
        $params = $this->preparePostDataUsePartnerId($params);
        return $this->post($params);
    }

    private function preparePostDataUsePartnerId(array $data): array
    {
        $this->log->info('preparePostDataUsePartnerId data：', $data);

        $postDataStr = http_build_query($data);
        $encryptData = $this->encrypt(
            $postDataStr,
            config('joymap.pay.channels.spgateway.store.merchant_hash_key'),
            config('joymap.pay.channels.spgateway.store.merchant_iv_key'),
        );

        $postData = [
            'PartnerID_' => config('joymap.pay.channels.spgateway.store.partner_id'),
            'PostData_' => $encryptData,
        ];
        $this->log->info('preparePostDataUsePartnerId 最後送出的資料：', $postData);

        return $postData;
    }

    private function preparePostDataHasCkcekValue(array $data): array
    {
        $this->log->info('preparePostDataHasCkcekValue data：', $data);

        ksort($data);
        $CheckValue = sprintf(
            'IV=%s&%s&Key=%s',
            $this->store->storeSpgateway->merchant_iv_key,
            http_build_query($data),
            $this->store->storeSpgateway->merchant_hash_key
        );
        $CheckValue = strtoupper(hash("sha256", $CheckValue));
        $data['Version'] = '1.1';
        $data['RespondType'] = 'JSON';
        $data['TimeStamp'] = time();
        $data['CheckValue'] = $CheckValue;
        $this->log->info('preparePostDataHasCkcekValue 最後送出的資料：', $data);

        return $data;
    }

    private function preparePostData(array $data): array
    {
        $this->log->info('preparePostData data：', $data);

        $postDataStr = http_build_query($data);
        $encryptData = $this->encrypt($postDataStr);

        $postData = [
            'MerchantID_' => $this->store->storeSpgateway->merchant_id,
            'Pos_' => 'JSON',
            'PostData_' => $encryptData,
        ];

        $this->log->info('preparePostData 最後送出的資料：', $postData);

        return $postData;
    }

    private function encrypt($str = '', $merchantHashKey = '', $merchantIvKey = ''): string
    {
        $str = $this->addPadding($str);
        $str = openssl_encrypt(
            $str,
            'aes-256-cbc',
            $merchantHashKey ?: $this->store->storeSpgateway->merchant_hash_key,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $merchantIvKey ?: $this->store->storeSpgateway->merchant_iv_key
        );
        return trim(bin2hex($str));
    }

    private function addPadding($string = '', $blockSize = 32): string
    {
        $len = strlen($string);
        $pad = $blockSize - ($len % $blockSize);
        $string .= str_repeat(chr($pad), $pad);

        return $string;
    }

    private function post($params)
    {
        try {
            $client = new Client(['timeout' => 30]);

            $this->log->info('Spgateway send', [
                'url' => $this->url,
                'data' => $params,
            ]);

            $res = $client->post(
                $this->url, [
                    'form_params' => $params,
                ]
            )->getBody()->getContents();

            $this->log->info('Spgateway res', [
                'url' => $this->url,
                'res' => $res,
            ]);

            return json_decode($res, true);
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' Error: ', [$e]);

            $message = LineNotification::getMsgText($e);
            event(new SendNotifyEvent($message));
        }

        return false;
    }
}
