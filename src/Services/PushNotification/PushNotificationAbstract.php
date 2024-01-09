<?php

namespace Mtsung\JoymapCore\Services\PushNotification;


use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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

    public function success(array $request, Collection $responses, mixed $to): void
    {
        // 推播成功執行
    }

    public function fail(array $request, Collection $responses, mixed $to): void
    {
        // 推播失敗執行
    }

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

        if (is_subclass_of($to, Model::class)) {
            $to = $to->id;
        } else if ($to instanceof Collection && is_subclass_of($to->first(), Model::class)) {
            $to = $to->pluck('id')->toArray();
        }

        $service = Notification::{$method}($to)
            ->title($this->title())
            ->body($this->body())
            ->action($this->action())
            ->data($this->data());

        if ($res = $service->send()) {
            $this->success($service->getRequest(), $service->getResponses(), $to);
        } else {
            $this->fail($service->getRequest(), $service->getResponses(), $to);
        }

        return $res;
    }
}
