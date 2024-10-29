<?php

namespace Mtsung\JoymapCore\Helpers\DesignatedDriver;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Events\Notify\SendNotifyEvent;
use Mtsung\JoymapCore\Facades\Notification\LineNotification;
use Throwable;

class Twdd
{
    private Client $client;
    private string $baseUrl;
    private string $callbackUrl;
    private string $user;
    private string $pw;
    protected mixed $log;

    public function __construct()
    {
        $this->baseUrl = config('joymap.designated_driver.channels.twdd.url');
        $this->callbackUrl = config('joymap.designated_driver.channels.twdd.callback_url');
        $this->user = config('joymap.designated_driver.channels.twdd.user');
        $this->pw = config('joymap.designated_driver.channels.twdd.pw');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'user' => $this->user,
                'pw' => $this->pw,
            ],
            'timeout' => 30,
        ]);

        $this->log = Log::stack([
            config('logging.default'),
            'twdd',
        ]);
    }

    // 喜好標籤
    public function habits(): array
    {
        try {
            $res = $this->client->request('GET', 'member/habits')->getBody()->getContents();

            return json_decode($res, true);
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' Error: ', [$e]);

            $message = LineNotification::getMsgText($e);
            event(new SendNotifyEvent($message));
        }

        return [];
    }

    // 取消原因列表
    public function cancelReasons(): array
    {
        try {
            $res = $this->client->request('GET', 'task/cancel-reasons')->getBody()->getContents();

            return json_decode($res, true);
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' Error: ', [$e]);

            $message = LineNotification::getMsgText($e);
            event(new SendNotifyEvent($message));
        }

        return [];
    }

    // 取消任務
    public function cancel(int $taskId, int $cancelId): array
    {
        try {
            $postData = [
                'json' => array_filter([
                    'cancel_id' => $cancelId,
                ]),
            ];

            $this->log->info('twdd send ' . __METHOD__, $postData);

            $res = $this->client->request('PUT', 'tasking/' . $taskId . '/cancel', $postData)->getBody()->getContents();

            $this->log->info('twdd res ' . __METHOD__, [$res]);

            return json_decode($res, true);
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' Error: ', [$e]);

            $message = LineNotification::getMsgText($e);
            event(new SendNotifyEvent($message));
        }

        return [];
    }

    public function match(string $phone, string $startAddress, float $lat, float $lng, array $habits, string $remark = null): array
    {
        try {
            $postData = [
                'json' => array_filter([
                    'member_phone' => $phone,
                    'start_address' => $startAddress,
                    'call_lat' => $lat,
                    'call_lng' => $lng,
                    'payment_type' => 1,
                    'payment_id' => 0,
                    'member_habits' => $habits,
                    'member_remark' => $remark ?? '',
                    'is_markup' => 0,
                    'callback_url' => $this->callbackUrl,
                ]),
            ];

            $this->log->info('twdd send ' . __METHOD__, $postData);

            $res = $this->client->request('POST', '', $postData)->getBody()->getContents();

            $this->log->info('twdd res ' . __METHOD__, [$res]);

            return json_decode($res, true);
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' Error: ', [$e]);

            $message = LineNotification::getMsgText($e);
            event(new SendNotifyEvent($message));
        }

        return [];
    }
}
