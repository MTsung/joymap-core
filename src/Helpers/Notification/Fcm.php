<?php

namespace Mtsung\JoymapCore\Helpers\Notification;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use stdClass;
use Throwable;

class Fcm implements NotificationInterface
{
    private Client $client;
    private string $baseUrl;
    private string $apiKey;
    protected mixed $log;

    public function __construct()
    {
        $this->baseUrl = config('joymap.notification.fcm.url');
        $this->apiKey = config('joymap.notification.fcm.token');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'key=' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
        ]);

        $this->log = Log::stack([
            config('logging.default'),
            'fcm',
        ]);
    }

    /**
     * @throws Exception
     */
    public function send(array $tokens, string $title, string $body, int $badge, array $data): bool
    {
        $res = false;
        $temp = collect($tokens)->chunk(10);
        foreach ($temp as $sendTokens) {
            $t = $sendTokens->values()->toArray();
            $res |= $this->callApi($t, $title, $body, $badge, $data);
        }
        return $res;
    }

    /**
     * @throws Exception
     */
    protected function callApi(array $tokens, string $title, string $body, int $badge, array $data): bool
    {
        try {
            $postData = [
                'json' => [
                    'registration_ids' => $tokens,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'badge' => $badge,
                    ],
                    'data' => $data ?: new stdClass(),
                ],
            ];

            $this->log->info('fcm send', $postData);

            $res = $this->client->request('POST', $this->baseUrl, $postData);

            $this->log->info('fcm res', [$res->getBody()->getContents()]);

            return $res->getStatusCode() === 200;
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' error: ', [$e->getMessage(), $e]);

            return false;
        }
    }
}
