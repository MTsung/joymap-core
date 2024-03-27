<?php

namespace Mtsung\JoymapCore\Helpers\Notification;

use Exception;
use Google\Auth\ApplicationDefaultCredentials;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use stdClass;
use Throwable;

/**
 * 舊的 FCM 於 2023 年 6 月 20 日棄用，並將於 2024 年 6 月刪除。
 * 所以必須串這隻
 * https://firebase.google.com/docs/cloud-messaging/migrate-v1?hl=zh-tw
 */
class FcmV1 implements NotificationInterface
{
    private Client $client;
    private string $sendUrl;
    private string $topicUrl;
    private string $fcmTopicName;
    private string $apiKey;
    protected mixed $log;
    private array $request;
    private Collection $responses;

    public function __construct()
    {
        $this->sendUrl = config('joymap.notification.channels.fcm_v1.url');
        $this->topicUrl = config('joymap.notification.channels.fcm_v1.topic_url');
        $this->apiKey = $this->getToken();

        // 建立一個用完即棄用的 Topic
        $this->fcmTopicName = Str::uuid();

        $this->client = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'access_token_auth' => 'true',
            ],
            'timeout' => 30,
        ]);

        $this->log = Log::stack([
            config('logging.default'),
            'fcm-v1',
        ]);

        $this->responses = collect();
    }

    public function topic(string $topic): FcmV1
    {
        // Do Nothing.
        return $this;
    }

    public function formatToken(Collection $tokens): array
    {
        return $tokens->pluck('device_token')->unique()->toArray();
    }

    /**
     * @throws Exception
     */
    public function send(array $tokens, string $title, string $body, int $badge, array $data): bool
    {
        $this->responses = collect();

        $temp = collect($tokens)->chunk(1000);
        foreach ($temp as $sendTokens) {
            $this->callTopicApi($sendTokens->values()->toArray());
        }

        return $this->callSendApi($title, $body, $badge, $data);
    }


    /**
     * 一次限制 1000 個 Token
     * "error": "Number of entries exceeded the limit of 1000"
     * https://developers.google.com/instance-id/reference/server?hl=zh-tw
     */
    protected function callTopicApi(array $tokens): void
    {
        try {
            $postData = $this->request = [
                'json' => [
                    'to' => '/topics/' . $this->fcmTopicName,
                    'registration_tokens' => $tokens,
                ],
            ];

            $this->log->info('fcm-v1 callTopicApi send', $postData);

            $res = $this->client->request('POST', $this->topicUrl, $postData);

            $contents = $res->getBody()->getContents();

            $this->log->info('fcm-v1 callTopicApi res', [$contents]);
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' Error: ', [$e->getMessage(), $e]);
        }
    }

    /**
     *
     * @throws Exception
     */
    protected function callSendApi(string $title, string $body, int $badge, array $data): bool
    {
        try {
            $postData = $this->request = [
                'json' => [
                    'message' => [
                        'topic' => $this->fcmTopicName,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'data' => [
                            'action' => $data['action'] ?? 'notification.list',
                            'data' => json_encode($data ?: new stdClass()),
                        ],
                        // Android
                        'android' => [
                            'notification' => [
                                'notification_count' => $badge,
                            ]
                        ],
                        // iOS
                        'apns' => [
                            'payload' => [
                                'aps' => [
                                    'badge' => $badge,
                                ],
                            ],
                        ],
                    ],
                ],
            ];

            $this->log->info('fcm-v1 send', $postData);

            $res = $this->client->request('POST', $this->sendUrl, $postData);

            $contents = $res->getBody()->getContents();

            $this->responses->add($contents);

            $this->log->info('fcm-v1 res', [$contents]);

            return $res->getStatusCode() === 200;
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' Error: ', [$e->getMessage(), $e]);
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

    // 取得一次性 OAuth2 Token
    private function getToken(): string
    {
        $scope = [
            'https://www.googleapis.com/auth/firebase.messaging',
        ];
        $credentials = ApplicationDefaultCredentials::getCredentials($scope);
        $authToken = $credentials->fetchAuthToken();
        return $authToken['access_token'] ?? '';
    }
}
