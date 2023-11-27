<?php

namespace Mtsung\JoymapCore\Helpers\Notification;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Throwable;

class Gorush implements NotificationInterface
{
    private Client $client;
    private string $baseUrl;
    private string $host;
    private string $port;
    private string $path;
    private string $topic;

    public const PLATFORM_IOS = 1;
    public const PLATFORM_ANDROID = 2;
    protected mixed $log;

    public function __construct()
    {
        $this->host = config('joymap.notification.gorush.host');
        $this->port = config('joymap.notification.gorush.port');
        $this->path = config('joymap.notification.gorush.path');

        $this->baseUrl = "{$this->host}:{$this->port}{$this->path}";

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'timeout' => 30,
        ]);

        $this->log = Log::stack([
            config('logging.default'),
            'gorush',
        ]);
    }

    public function topic(string $topic): Gorush
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(array $tokens, string $title, string $body, int $badge, array $data): bool
    {
        try {
            if (!is_array($tokens[self::PLATFORM_IOS]) || !is_array($tokens[self::PLATFORM_ANDROID])) {
                throw new Exception('tokens 必須為陣列 [ 1 => [ios tokens], 2 => [android tokens]] 的形式');
            }
            $postData = [
                'json' => [
                    'notifications' => [
                        [
                            'platform' => self::PLATFORM_IOS,
                            'tokens' => $tokens[self::PLATFORM_IOS],
                            'alert' => [
                                'title' => $title,
                                'body' => $body,
                            ],
                            'badge' => $badge,
                            'sound' => 'default',
                            'data' => $data,
                        ],
                        [
                            'platform' => self::PLATFORM_ANDROID,
                            'tokens' => $tokens[self::PLATFORM_ANDROID],
                            'title' => $title,
                            'message' => $body,
                            'topic' => $this->topic,
                            'badge' => $badge,
                            'sound' => 'default',
                            'data' => $data,
                        ],
                    ],
                ],
            ];

            $this->log->info('gorush send', $postData);

            $res = $this->client->request('POST', $this->baseUrl, $postData);

            $this->log->info('gorush res', [$res->getBody()->getContents()]);

            return $res->getStatusCode() === 200;
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' error: ', [$e]);
        }

        return false;
    }
}
