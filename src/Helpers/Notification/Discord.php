<?php

namespace Mtsung\JoymapCore\Helpers\Notification;

use Illuminate\Support\Facades\Log;
use Throwable;

class Discord
{
    public static function sendMsg(string $message, string $channelId = '', string $tagId = ''): void
    {
        if (!config('joymap.discord_notify.enable')) {
            return;
        }

        if (!$token = config('joymap.discord_notify.token')) {
            return;
        }

        try {
            $url = '/channels/' . ($channelId ?: config('joymap.discord_notify.channel_id')) . '/messages';
            $header = ['Content-Type: multipart/form-data'];
            if ($tagId) {
                $data['content'] = sprintf(">>> # %s\n【%s】\n<@&%s>\n```%s```",
                    env('APP_ENV'),
                    env('APP_NAME'),
                    $tagId,
                    $message
                );
            } else {
                $data['content'] = sprintf(">>> # %s\n【%s】\n```%s```",
                    env('APP_ENV'),
                    env('APP_NAME'),
                    $message
                );
            }
            self::curl($token, $url, 'POST', $data, [], $header);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), [$e]);
        }
    }

    /**
     * curl
     * @param string $token
     * @param string $url 網址
     * @param string $type GET or POST
     * @param array $data 資料
     * @param array $options curl 設定
     * @param array $header header
     * @return string
     */
    private static function curl(string $token, string $url, string $type = 'GET', array $data = [], array $options = [], array $header = []): string
    {
        $input = [
            'url' => $url,
            'type' => $type,
            'data' => $data,
            'options' => $options,
            'header' => $header,
        ];
        if (!$header) {
            $header[] = 'Content-Type: application/json';
        }
        $header[] = 'Authorization:	Bot ' . $token;
        $ch = curl_init();

        if (strtoupper($type) == 'GET') {
            $url = $url . '?' . http_build_query($data);
        } else { //POST
            if (in_array('Content-Type: multipart/form-data', $header)) {
                $options = [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $data
                ];
            } else {
                $options = [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($data)
                ];
            }
        }

        $defaultOptions = [
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_URL => 'https://discord.com/api/v8' . $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HEADER => true,
        ];
        $options = $options + $defaultOptions;
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        if (curl_error($ch)) {
            error_log('curl_error:' . $url . curl_error($ch));
            throw new Exception(curl_error($ch), curl_errno($ch));
        }

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $response = substr($response, $headerSize);

        $res = json_decode($response, true);
        if (isset($res['retry_after'])) {
            sleep((int)ceil(json_decode($response, true)['retry_after']));
            return self::curl($input['url'], $input['type'], $input['data'], $input['options'], $input['header']);
        }
        return $response;
    }
}
