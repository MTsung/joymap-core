<?php

namespace Mtsung\JoymapCore;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Mtsung\JoymapCore\Models\AdminUser;
use Mtsung\JoymapCore\Models\NotificationMemberWithdraw;
use Mtsung\JoymapCore\Models\NotificationNewRegister;
use Mtsung\JoymapCore\Models\NotificationOrder;
use Mtsung\JoymapCore\Models\NotificationPlatform;
use Mtsung\JoymapCore\Models\NotificationStorePay;
use Mtsung\JoymapCore\Providers\EventServiceProvider;
use Symfony\Component\Finder\Finder;

class JoymapCoreServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        Relation::morphMap([
            'notification_order' => NotificationOrder::class,
            'notification_platform' => NotificationPlatform::class,
            'notification_store_pay' => NotificationStorePay::class,
            'notification_member_withdraw' => NotificationMemberWithdraw::class,
            'notification_new_register' => NotificationNewRegister::class,
            'App\Models\AdminUser' => AdminUser::class,
        ]);


        // 預設 config merge
        $mergeConfigs = [
            'logging.channels.hitrust-pay',
            'logging.channels.spgateway-pay',
            'logging.channels.spgateway-store',
            'logging.channels.fcm',
            'logging.channels.gorush',
            'logging.channels.infobip',
            'logging.channels.jcoin',
        ];
        foreach ($mergeConfigs as $config) {
            if (!config()->has($config)) {
                config([$config => config('joymap.' . $config)]);
            }
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        // Load All Config
        $configPath = __DIR__ . '/../config/';
        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $path = $file->getRealPath();
            $name = 'joymap.' . basename($path, '.php');
            $this->mergeConfigFrom($path, $name);
        }

        // Load All Lang
        // e.g. __('joymap::xxxxxxxx')
        $this->loadTranslationsFrom(__DIR__ . '/../lang/', 'joymap');


        $functionPath = __DIR__ . '/Function/';
        foreach (Finder::create()->files()->name('*.php')->in($functionPath) as $file) {
            require_once($file->getRealPath());
        }

        // Register Event
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [];
    }
}
