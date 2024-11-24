<?php

namespace Mtsung\JoymapCore\Services\Sms;


use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mtsung\JoymapCore\Action\AsJob;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Facades\Sms\Sms;

/**
 * @method static dispatch(mixed $to, $arguments = null)
 * @method static bool run(mixed $to, $arguments = null)
 */
abstract class SmsAbstract implements SmsInterface, ShouldQueue
{
    use AsObject, AsJob;

    // 是否只在正式機發送，測試機也想發的話繼承時覆蓋即可
    protected bool $onlyProdSend = true;

    protected mixed $arguments;

    // 要用三竹的話繼承時改 byMitake
    protected mixed $service = 'byInfobip';

    /**
     * @throws Exception
     */
    public function handle(mixed $to, $arguments = null): bool
    {
        return $this->send($to, $arguments);
    }

    /**
     * @throws Exception
     */
    public function send(mixed $to, $arguments = null): bool
    {
        $this->arguments = $arguments;

        $method = $this->toType()->value;

        $sms = Sms::{$method}($to)->{$this->service}()->body($this->body());

        if ($this->onlyProdSend) {
            return $sms->onlyProdSend();
        }

        return $sms->send();
    }
}
