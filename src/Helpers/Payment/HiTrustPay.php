<?php

namespace Mtsung\JoymapCore\Helpers\Payment;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Models\Store;
use Throwable;

class HiTrustPay implements PayInterface
{
    private string $url;
    private string $refererUrl;
    private string $callbackUrl;
    private Store $store;
    protected mixed $log;

    public function __construct()
    {
        $this->url = config('joymap.pay.hitrustpay.url');
        $this->refererUrl = config('joymap.pay.hitrustpay.referer_url');
        $this->callbackUrl = config('joymap.pay.hitrustpay.callback_url');

        $this->log = Log::stack([
            config('logging.default'),
            'hitrust-pay',
        ]);
    }

    public function getAmountMultiplicand(): int
    {
        return 100;
    }

    /**
     * @throws Exception
     */
    public function store(Store $store): HiTrustPay
    {
        $this->store = $store;
        if (!$this->store->qrcode_no) {
            throw new Exception('商店沒有 store_id', 422);
        }
        return $this;
    }

    public function bindCard(array $params)
    {
        $params = [
            'Type' => 'Auth',
            'storeid' => $this->store->qrcode_no ?? $params['storeId'],
            'ordernumber' => $params['orderNumber'],
            'amount' => $params['amount'],
            'orderdesc' => $params['orderDesc'],
            'depositflag' => $params['depositFlag'] ?? 0,
            'queryflag' => $params['queryFlag'] ?? 1,
            'returnURL' => $params['returnUrl'] ?? $this->callbackUrl,
            'merUpdateURL' => $params['callbackUrl'] ?? $this->callbackUrl,
            'pan' => $params['cardNo'],
            'expiry' => $params['expiry'],
            'e01' => $params['cvc'],
            'e55' => 1,
        ];

        return $this->post($params, true);
    }

    public function authRe($params)
    {
        $params = [
            'Type' => 'AuthRe',
            'storeid' => $this->store->qrcode_no ?? $params['storeId'],
            'ordernumber' => $params['orderNumber'],
            'queryflag' => $params['queryFlag'] ?? 1,
            'merUpdateURL' => $params['callbackUrl'] ?? $this->callbackUrl,
        ];

        return $this->post($params);
    }

    public function cancel(array $params)
    {
        $params = [
            'Type' => 'Refund',
            'storeid' => $this->store->qrcode_no ?? $params['storeId'],
            'ordernumber' => $params['orderNumber'],
            'amount' => $params['amount'],
            'queryflag' => $params['queryFlag'] ?? 1,
            'merUpdateURL' => $params['callbackUrl'] ?? $this->callbackUrl,
        ];

        return $this->post($params);
    }

    public function close(array $params)
    {
        // TODO: Implement close() method.
        return false;
    }

    public function pay(array $params)
    {
        $params = [
            'Type' => 'AUTH_TRXTOKEN',
            'storeid' => $this->store->qrcode_no ?? $params['storeId'],
            'ordernumber' => $params['orderNumber'],
            'amount' => $params['amount'],
            'orderdesc' => $params['orderDesc'],
            'depositflag' => $params['depositFlag'] ?? 1,
            'queryflag' => $params['queryFlag'] ?? 1,
            'returnURL' => $params['returnUrl'] ?? $this->callbackUrl,
            'merUpdateURL' => $params['callbackUrl'] ?? $this->callbackUrl,
            'trxToken' => $params['token'],
            'expiry' => $params['expiry'],
        ];

        return $this->post($params);
    }

    public function query(array $params)
    {
        // TODO: Implement query() method.
        return false;
    }

    private function post($params, $getUrl = false)
    {
        try {
            $redirectUrl = '';
            $client = new Client(['timeout' => 30]);

            $this->log->info('hitrust send', [$params]);

            $res = $client->post(
                $this->url,
                [
                    'form_params' => $params,
                    'headers' => [
                        'Referer' => $this->refererUrl,
                    ],
                    'on_stats' => function (TransferStats $stats) use (&$redirectUrl) {
                        $redirectUrl = $stats->getHandlerStats()['redirect_url'];
                    },
                    'allow_redirects' => false,
                ]
            )->getBody()->getContents();

            $this->log->info('hitrust res', [
                'getUrl' => $getUrl,
                'redirectUrl' => $redirectUrl,
                'res' => $res,
            ]);

            if ($redirectUrl) {
                if ($getUrl) {
                    return $redirectUrl;
                }
                $partsQuery = $this->mb_parse_url($redirectUrl, PHP_URL_QUERY);
                parse_str($partsQuery, $query);
                return $query;
            }

            return json_decode($res, true);
        } catch (ClientException $e) {
            $this->log->error(__METHOD__ . ' ClientException: ', [$e]);
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' error: ', [$e]);
        }

        return false;
    }

    /**
     * 解析網址中文問題
     * @param $url
     * @param int $component
     * @return array|false|int|mixed|string|null
     */
    private function mb_parse_url($url, $component = -1)
    {
        $encodedUrl = preg_replace_callback('%[^:/?#=\.]+%usD', function ($matches) {
            return urlencode($matches[0]);
        }, $url);
        $components = parse_url($encodedUrl, $component);
        if (is_array($components)) {
            foreach ($components as &$part) {
                if (is_string($part)) {
                    $part = urldecode($part);
                }
            }
        } else if (is_string($components)) {
            $components = urldecode($components);
        }
        return $components;
    }
}
