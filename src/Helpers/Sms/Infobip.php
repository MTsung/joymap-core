<?php

namespace Mtsung\JoymapCore\Helpers\Sms;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Propaganistas\LaravelPhone\PhoneNumber;
use Throwable;

/**
 * API Docs
 * https://www.infobip.com/docs/sms/api
 * https://www.infobip.com/docs/api/channels/sms/sms-messaging/outbound-sms/send-sms-message
 */
class Infobip implements SmsInterface
{
    private Client $client;
    private string $baseUrl;
    private string $apiKey;
    private string $from;
    protected mixed $log;

    public function __construct()
    {
        $this->baseUrl = config('joymap.sms.channels.infobip.url');
        $this->apiKey = config('joymap.sms.channels.infobip.api_key');
        $this->from = config('joymap.sms.channels.infobip.from');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'App ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'timeout' => 30,
        ]);

        $this->log = Log::stack([
            config('logging.default'),
            'infobip',
        ]);
    }


    public function send(array $phones, string $body, ?Carbon $sendAt = null): bool
    {
        try {
            $destinations = [];
            foreach ($phones as $phone) {
                // string [ 0 .. 50 ] characters
                // Message destination address. Addresses must be in international format (Example: 41793026727).
                try {
                    $phoneNumber = new PhoneNumber($phone);
                    if (!$phoneNumber->getCountry()) {
                        $phoneNumber = new PhoneNumber($phone, 'TW');
                    }

                    $phoneE164 = $phoneNumber->formatE164();
                    $destinations[] = ['to' => $phoneE164];

                    $this->log->info('phone formatE164', ['phone_raw' => $phone, 'phone_e164' => $phoneE164]);
                } catch (Throwable $e) {
                    $this->log->error('該手機號碼無效', [
                        'phone' => $phone,
                        'msg' => $e->getMessage(),
                    ]);
                }
            }

            $postData = [
                'json' => [
                    'messages' => [
                        array_filter([
                            'from' => $this->from,
                            'destinations' => $destinations,
                            'text' => $body,
                            // Date and time when the message is to be sent. Used for scheduled SMS. Has the following
                            // format: yyyy-MM-dd'T'HH:mm:ss.SSSZ, and can only be scheduled for no later than 180 days in advance.
                            'sendAt' => $sendAt?->format('Y-m-d\TH:i:s.vO'),
                        ]),
                    ],
                ],
            ];

            $this->log->info('infobip send', $postData);

            $res = $this->client->request('POST', $this->baseUrl, $postData);

            $this->log->info('infobip res', [$res->getBody()->getContents()]);

            return $res->getStatusCode() === 200;
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' Error: ', [$e]);
        }

        return false;
    }
}
