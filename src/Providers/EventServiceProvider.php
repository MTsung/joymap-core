<?php

namespace Mtsung\JoymapCore\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Mtsung\JoymapCore\Events\Notify\SendNotifyEvent;
use Mtsung\JoymapCore\Events\Order\OrderCancelEvent;
use Mtsung\JoymapCore\Events\Order\OrderCommentRemindEvent;
use Mtsung\JoymapCore\Events\Order\OrderRemindEvent;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Events\Order\OrderUpdateEvent;
use Mtsung\JoymapCore\Events\Order\PaySuccessEvent;
use Mtsung\JoymapCore\Listeners\ErrorNotify\LineListener;
use Mtsung\JoymapCore\Services\Mail\Order\SendOrderCancelMailService;
use Mtsung\JoymapCore\Services\Mail\Order\SendOrderCommentRemindMailService;
use Mtsung\JoymapCore\Services\Mail\Order\SendOrderRemindMailService;
use Mtsung\JoymapCore\Services\Mail\Order\SendOrderSuccessMailService;
use Mtsung\JoymapCore\Services\Mail\Order\SendOrderUpdateMailService;
use Mtsung\JoymapCore\Services\Notification\CreateNotificationOrderService;
use Mtsung\JoymapCore\Services\Notification\CreateNotificationPayService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderCancelPushNotificationService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderCommentRemindPushNotificationService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderCommentRemindSmsService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderRemindPushNotificationService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderRemindSmsService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderSuccessPushNotificationService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderSuccessSmsService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderUpdatePushNotificationService;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // 系統提醒
        SendNotifyEvent::class => [
            LineListener::class,
        ],
        // 訂位成功
        OrderSuccessEvent::class => [
            [CreateNotificationOrderService::class, 'asListener'],
            [SendOrderSuccessSmsService::class, 'asListener'],
            [SendOrderSuccessPushNotificationService::class, 'asListener'],
            [SendOrderSuccessMailService::class, 'asListener'],
        ],
        // 訂位修改
        OrderUpdateEvent::class => [
            [CreateNotificationOrderService::class, 'asListener'],
            [SendOrderUpdatePushNotificationService::class, 'asListener'],
            [SendOrderUpdateMailService::class, 'asListener'],
        ],
        // 訂位取消
        OrderCancelEvent::class => [
            [CreateNotificationOrderService::class, 'asListener'],
            [SendOrderCancelPushNotificationService::class, 'asListener'],
            [SendOrderCancelMailService::class, 'asListener'],
        ],
        // 訂位提醒
        OrderRemindEvent::class => [
            [CreateNotificationOrderService::class, 'asListener'],
            [SendOrderRemindSmsService::class, 'asListener'],
            [SendOrderRemindPushNotificationService::class, 'asListener'],
            [SendOrderRemindMailService::class, 'asListener'],
        ],
        // 評論提醒
        OrderCommentRemindEvent::class => [
            [CreateNotificationOrderService::class, 'asListener'],
            [SendOrderCommentRemindSmsService::class, 'asListener'],
            [SendOrderCommentRemindPushNotificationService::class, 'asListener'],
            [SendOrderCommentRemindMailService::class, 'asListener'],
        ],
        // 支付成功
        PaySuccessEvent::class => [
            [CreateNotificationPayService::class, 'asListener'],
        ]
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
