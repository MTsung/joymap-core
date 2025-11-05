<?php

namespace Mtsung\JoymapCore\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Mtsung\JoymapCore\Events\Comment\CommentSuccessEvent;
use Mtsung\JoymapCore\Events\Model\StoreFloor\StoreFloorDeletingEvent;
use Mtsung\JoymapCore\Events\Model\StoreFloor\StoreFloorUpdatedEvent;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableCreatedEvent;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableDeletedEvent;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableDeletingEvent;
use Mtsung\JoymapCore\Events\Model\StoreTable\StoreTableUpdatedEvent;
use Mtsung\JoymapCore\Events\Notify\SendNotifyEvent;
use Mtsung\JoymapCore\Events\Order\OrderCancelEvent;
use Mtsung\JoymapCore\Events\Order\OrderCommentRemindEvent;
use Mtsung\JoymapCore\Events\Order\OrderRemindEvent;
use Mtsung\JoymapCore\Events\Order\OrderSuccessEvent;
use Mtsung\JoymapCore\Events\Order\OrderUpdateEvent;
use Mtsung\JoymapCore\Events\Pay\PaySuccessEvent;
use Mtsung\JoymapCore\Listeners\ErrorNotify\DiscordListener;
use Mtsung\JoymapCore\Listeners\ErrorNotify\LineListener;
use Mtsung\JoymapCore\Listeners\Model\StoreFloor\StoreFloorDeletingListener;
use Mtsung\JoymapCore\Listeners\Model\StoreTable\StoreTableCreatedListener;
use Mtsung\JoymapCore\Listeners\Model\StoreTable\StoreTableDeletingListener;
use Mtsung\JoymapCore\Services\Mail\Order\SendOrderCancelMailService;
use Mtsung\JoymapCore\Services\Mail\Order\SendOrderCommentRemindMailService;
use Mtsung\JoymapCore\Services\Mail\Order\SendOrderRemindMailService;
use Mtsung\JoymapCore\Services\Mail\Order\SendOrderSuccessMailService;
use Mtsung\JoymapCore\Services\Mail\Order\SendOrderUpdateMailService;
use Mtsung\JoymapCore\Services\Notification\CreateNotificationOrderService;
use Mtsung\JoymapCore\Services\Notification\CreateNotificationPayService;
use Mtsung\JoymapCore\Services\Notification\CreateStoreNotificationCommentService;
use Mtsung\JoymapCore\Services\Notification\CreateStoreNotificationOrderService;
use Mtsung\JoymapCore\Services\Notification\CreateStoreNotificationPayService;
use Mtsung\JoymapCore\Services\PushNotification\Member\Order\SendOrderCancelPushNotificationService;
use Mtsung\JoymapCore\Services\PushNotification\Member\Order\SendOrderCommentRemindPushNotificationService;
use Mtsung\JoymapCore\Services\PushNotification\Member\Order\SendOrderRemindPushNotificationService;
use Mtsung\JoymapCore\Services\PushNotification\Member\Order\SendOrderSuccessPushNotificationService;
use Mtsung\JoymapCore\Services\PushNotification\Member\Order\SendOrderUpdatePushNotificationService;
use Mtsung\JoymapCore\Services\PushNotification\Member\Pay\SendPaySuccessPushNotificationService;
use Mtsung\JoymapCore\Services\PushNotification\Store\Comment\SendCommentSuccessPushNotificationService;
use Mtsung\JoymapCore\Services\PushNotification\Store\Order\SendOrderCancelPushNotificationService as StoreSendOrderCancelPushNotificationService;
use Mtsung\JoymapCore\Services\PushNotification\Store\Order\SendOrderSuccessPushNotificationService as StoreSendOrderSuccessPushNotificationService;
use Mtsung\JoymapCore\Services\PushNotification\Store\Pay\SendPaySuccessPushNotificationService as StoreSendPaySuccessPushNotificationService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderCommentRemindSmsService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderRemindSmsService;
use Mtsung\JoymapCore\Services\Sms\Order\SendOrderSuccessSmsService;
use Mtsung\JoymapCore\Services\Store\StoreTable\WriteStoreFloorTableCombinationService;

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
            DiscordListener::class,
        ],
        // 預約成功
        OrderSuccessEvent::class => [
            [CreateNotificationOrderService::class, 'asListener'],
            [CreateStoreNotificationOrderService::class, 'asListener'],
            [SendOrderSuccessSmsService::class, 'asListener'],
            [SendOrderSuccessPushNotificationService::class, 'asListener'],
            [StoreSendOrderSuccessPushNotificationService::class, 'asListener'],
            [SendOrderSuccessMailService::class, 'asListener'],
        ],
        // 預約修改
        OrderUpdateEvent::class => [
            [CreateNotificationOrderService::class, 'asListener'],
            [SendOrderUpdatePushNotificationService::class, 'asListener'],
            [SendOrderUpdateMailService::class, 'asListener'],
        ],
        // 預約取消
        OrderCancelEvent::class => [
            [CreateNotificationOrderService::class, 'asListener'],
            [CreateStoreNotificationOrderService::class, 'asListener'],
            [SendOrderCancelPushNotificationService::class, 'asListener'],
            [StoreSendOrderCancelPushNotificationService::class, 'asListener'],
            [SendOrderCancelMailService::class, 'asListener'],
        ],
        // 預約提醒
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
            [CreateStoreNotificationPayService::class, 'asListener'],
            [SendPaySuccessPushNotificationService::class, 'asListener'],
            [StoreSendPaySuccessPushNotificationService::class, 'asListener'],
        ],
        // 評論成功
        CommentSuccessEvent::class => [
            [CreateStoreNotificationCommentService::class, 'asListener'],
            [SendCommentSuccessPushNotificationService::class, 'asListener'],
        ],

        /** ---- Model Dispatches Events ----*/
        StoreFloorUpdatedEvent::class => [
            [WriteStoreFloorTableCombinationService::class, 'asListener'],
        ],
        StoreFloorDeletingEvent::class => [
            StoreFloorDeletingListener::class,
        ],
        StoreTableCreatedEvent::class => [
            StoreTableCreatedListener::class,
        ],
        StoreTableUpdatedEvent::class => [
            [WriteStoreFloorTableCombinationService::class, 'asListener'],
        ],
        StoreTableDeletingEvent::class => [
            StoreTableDeletingListener::class,
        ],
        StoreTableDeletedEvent::class => [
            [WriteStoreFloorTableCombinationService::class, 'asListener'],
        ],
        /** ---- Model Dispatches Events ----*/
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
