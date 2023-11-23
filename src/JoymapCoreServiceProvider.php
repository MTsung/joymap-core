<?php

namespace Mtsung\JoymapCore;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Mtsung\JoymapCore\Exceptions\Handler;
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
