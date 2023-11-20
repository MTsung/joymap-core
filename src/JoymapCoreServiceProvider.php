<?php

namespace Mtsung\JoymapCore;

use Illuminate\Support\ServiceProvider;
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
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'joymap');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['joymap-core'];
    }
}
