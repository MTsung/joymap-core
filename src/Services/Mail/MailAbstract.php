<?php

namespace Mtsung\JoymapCore\Services\Mail;


use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Mtsung\JoymapCore\Action\AsJob;
use Mtsung\JoymapCore\Action\AsObject;
use Throwable;

abstract class MailAbstract implements ShouldQueue
{
    use AsObject, AsJob;

    /**
     * @throws Exception
     */
    public function send(mixed $to, $sendClass): bool
    {
        $log = Log::stack([
            config('logging.default'),
            'mail',
        ]);

        $logData = [get_class($sendClass), $to,];

        if (empty($to)) {
            $log->info('Email Empty', $logData);

            return false;
        }

        try {
            $log->info('Send Start', $logData);

            Mail::to($to)->send($sendClass);

            $log->info('Send Success', $logData);

            return true;
        } catch (Throwable $e) {
            $log->error('Send Fail', [...$logData, $e]);

            return false;
        }
    }
}
