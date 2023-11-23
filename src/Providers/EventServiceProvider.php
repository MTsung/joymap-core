<?php

namespace Mtsung\JoymapCore\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Mtsung\JoymapCore\Events\ErrorNotify\SendErrorNotifyEvent;
use Mtsung\JoymapCore\Listeners\ErrorNotify\LineListener;

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
