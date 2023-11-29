<?php

namespace Mtsung\JoymapCore\Helpers\Notification;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Throwable;

class Line
{
    public static function sendMsg(string $message, bool $notificationDisabled = false): void
    {
        if (!$token = config('joymap.notification.line_notify.token')) {
            return;
        }

        try {
            $client = new Client(['timeout' => 30]);
            $url = config('joymap.notification.line_notify.url');
            $params = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'form_params' => [
                    'message' =>
                        sprintf("%s\n【%s】\n%s",
                            env('APP_ENV'),
                            env('APP_NAME'),
                            $message
                        ),
                    'notificationDisabled' => $notificationDisabled,
                ],
            ];
            $client->post($url, $params);
        } catch (Throwable $clientE) {
            Log::error($clientE->getMessage(), [$clientE]);
        }
    }

    public static function getMsgText(Throwable $e, $uuid = ''): string
    {
        return sprintf("%s\n%s\n%s:%s",
            $uuid,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
    }
}
