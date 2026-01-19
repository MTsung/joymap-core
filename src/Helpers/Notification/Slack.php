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
                    'text' => sprintf(
                        "```%s```\n> # %s\n> 【%s】",
                        $message,
                        env('APP_ENV'),
                        env('APP_NAME')
                    ),
                ],
            ];
            $client->post($webhookUrl, $params);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), [$e]);
        }
    }
}
