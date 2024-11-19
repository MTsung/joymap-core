<?php

namespace Mtsung\JoymapCore\Helpers\Sms;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Mtsung\JoymapCore\Events\Notify\SendNotifyEvent;
use Mtsung\JoymapCore\Facades\Notification\LineNotification;
use Propaganistas\LaravelPhone\PhoneNumber;
use Throwable;

/**
 * API Docs
 */
class Mitake implements SmsInterface
{
    private Client $client;
    private string $baseUrl;
    private string $username;
    private string $password;
    protected mixed $log;

    public function __construct()
    {
        $this->baseUrl = config('joymap.sms.channels.mitake.url');
        $this->username = config('joymap.sms.channels.mitake.username');
        $this->password = config('joymap.sms.channels.mitake.password');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
        ]);

        $this->log = Log::stack([
            config('logging.default'),
            'mitake',
        ]);
    }


    public function send(array $phones, string $body, ?Carbon $sendAt = null): bool
    {
        try {
            foreach ($phones as $phone) {
                try {
                    $phoneNumber = new PhoneNumber($phone);
                    if (!$phoneNumber->getCountry()) {
                        $phoneNumber = new PhoneNumber($phone, 'TW');
                    }

                    $phoneE164 = $phoneNumber->formatE164();

                    $this->log->info('phone formatE164', ['phone_raw' => $phone, 'phone_e164' => $phoneE164]);

                    $postData = [
                        'form_params' => [
                            'username' => $this->username,
                            'password' => $this->password,
                            'dstaddr' => $phoneE164,
                            'smbody' => $body,
                            'dlvtime' => $sendAt?->format('YmdHis'), // YYYYMMDDHHMMSS
                        ],
                    ];

                    $this->log->info('mitake send', $postData);

                    $res = $this->client->request('POST', $this->baseUrl, $postData);

                    $this->log->info('mitake res', [$res->getBody()->getContents()]);
                } catch (Throwable $e) {
                    $this->log->error('該手機號碼無效', [
                        'phone' => $phone,
                        'msg' => $e->getMessage(),
                    ]);
                }
            }

            return true;
        } catch (Throwable $e) {
            $this->log->error(__METHOD__ . ' Error: ', [$e]);

            $message = LineNotification::getMsgText($e);
            event(new SendNotifyEvent($message));
        }

        return false;
    }
}
