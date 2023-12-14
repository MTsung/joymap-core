<?php

namespace Mtsung\JoymapCore\Services\Sms;


use Exception;
use Illuminate\Support\Collection;
use Mtsung\JoymapCore\Action\AsJob;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Facades\Sms\Sms;

/**
 * @method static bool dispatch(mixed $to, array|Collection $bodyArguments = [])
 * @method static bool run(mixed $to, array|Collection $bodyArguments = [])
 */
abstract class SmsAbstract implements SmsInterface
{
    use AsObject, AsJob;

    // 是否只在正式機發送，測試機也想發的話繼承時覆蓋即可
    protected bool $onlyProdSend = true;

    /**
     * @throws Exception
     */
    public function handle(mixed $to, array|Collection $bodyArguments = []): bool
    {
        return $this->send($to, $bodyArguments);
    }

    /**
     * @throws Exception
     */
    public function send(mixed $to, array|Collection $bodyArguments = []): bool
    {
        $method = $this->toType()->value;
        $sms = Sms::{$method}($to)->body($this->body($bodyArguments));

        if ($this->onlyProdSend) {
            return $sms->onlyProdSend();
        }

        return $sms->send();
    }
}
