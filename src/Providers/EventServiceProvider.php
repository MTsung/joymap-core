<?php

namespace Mtsung\JoymapCore\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Mtsung\JoymapCore\Events\ErrorNotify\SendErrorNotifyEvent;
use Mtsung\JoymapCore\Events\Order\OrderCancelEvent;
use Mtsung\JoymapCore\Events\Order\OrderCommentRemindEvent;
use Mtsung\JoymapCore\Events\Order\OrderRemindEvent;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Events\Order\OrderUpdateEvent;
use Mtsung\JoymapCore\Listeners\ErrorNotify\LineListener;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderCommentRemindSmsService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderRemindSmsService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderSuccessSmsService;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SendErrorNotifyEvent::class => [
            LineListener::class,
        ],
        // 訂位成功 Event
        OrderSuccessEvent::class => [
            [SendOrderSuccessSmsService::class, 'asListener'],
        ],
        // 訂位修改 Event
        OrderUpdateEvent::class => [
        ],
        // 訂位取消 Event
        OrderCancelEvent::class => [
        ],
        // 訂位提醒 Event
        OrderRemindEvent::class => [
            [SendOrderRemindSmsService::class, 'asListener'],
        ],
        // 評論提醒 Event
        OrderCommentRemindEvent::class => [
            [SendOrderCommentRemindSmsService::class, 'asListener'],
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
