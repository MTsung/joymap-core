<?php

namespace Mtsung\JoymapCore\Helpers\Notification;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Throwable;

class Slack
{
    public static function sendMsg(string $message): void
    {
        if (!config('joymap.slack_notify.enable')) {
            return;
        }

        if (!$webhookUrl = config('joymap.slack_notify.webhook_url')) {
            return;
        }

        try {
            $client = new Client(['timeout' => 30]);
            $params = [
                'json' => [
                    'attachments' => [
                        [
                            'color' => isProd() ? '#FF0000' : '#858585',
                            'title' => sprintf('%s 【%s】', env('APP_ENV'), env('APP_NAME')),
                            'text' => '```' . $message . '```',
                            'mrkdwn_in' => ['text', 'pretext', 'fields'],
                            'ts' => time(),
                        ],
                    ],
                ],
            ];
            $client->post($webhookUrl, $params);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), [$e]);
        }
    }
}
