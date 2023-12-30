<?php

namespace Mtsung\JoymapCore\Services\PushNotification;


use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mtsung\JoymapCore\Action\AsJob;
use Mtsung\JoymapCore\Action\AsObject;
use Mtsung\JoymapCore\Facades\Notification\Notification;

/**
 * @method static dispatch(mixed $to, $arguments = null)
 * @method static bool run(mixed $to, $arguments = null)
 */
abstract class PushNotificationAbstract implements PushNotificationInterface, ShouldQueue
{
    use AsObject, AsJob;

    protected mixed $arguments;

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

        return Notification::{$method}($to)
            ->title($this->title())
            ->body($this->body())
            ->action($this->action())
            ->data($this->data())
            ->send();
    }
}
