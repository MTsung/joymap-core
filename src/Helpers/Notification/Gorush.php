<?php

namespace Mtsung\JoymapCore\Helpers\Notification;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
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
    private array $request;
    private Collection $responses;

    public function __construct()
    {
        $this->host = config('joymap.notification.channels.gorush.host');
        $this->port = config('joymap.notification.channels.gorush.port');
        $this->path = config('joymap.notification.channels.gorush.path');

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

        $this->responses = collect();
    }

    public function topic(string $topic): Gorush
    {
        $this->topic = $topic;
        return $this;
    }

    public function formatToken(Collection $tokens): array
    {
        $res[self::PLATFORM_IOS] = $tokens
            ->where('platform', self::PLATFORM_IOS)
            ->pluck('device_token')
            ->unique()
            ->toArray();

        $res[self::PLATFORM_ANDROID] = $tokens
            ->where('platform', self::PLATFORM_ANDROID)
            ->pluck('device_token')
            ->unique()
            ->toArray();

        return $res;
    }

    /**
     * @throws Exception
     */
    public function send(array $tokens, string $title, string $body, int $badge, array $data): bool
    {
        $this->responses = collect();
        try {
            if (!is_array($tokens[self::PLATFORM_IOS]) || !is_array($tokens[self::PLATFORM_ANDROID])) {
                throw new Exception('tokens 必須為陣列 [ 1 => [ios tokens], 2 => [android tokens]] 的形式');
            }
            $postData = $this->request = [
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

            $contents = $res->getBody()->getContents();

            $this->responses->add($contents);

            $this->log->info('gorush res', [$contents]);

            return $res->getStatusCode() === 200;
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' Error: ', [$e]);
        }

        return false;
    }

    public function getRequest(): array
    {
        return $this->request ?? [];
    }

    public function getResponses(): Collection
    {
        return $this->responses ?? collect();
    }
}
