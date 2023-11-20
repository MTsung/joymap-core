<?php

namespace Mtsung\JoymapCore;

use Illuminate\Support\ServiceProvider;

class JoymapCoreServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'mtsung');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'mtsung');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/joymap-core.php', 'joymap-core');
        $this->mergeConfigFrom(__DIR__ . '/../config/image.php', 'image');
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

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/joymap-core.php' => config_path('joymap-core.php'),
        ], 'joymap-core.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/mtsung'),
        ], 'joymap-core.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/mtsung'),
        ], 'joymap-core.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/mtsung'),
        ], 'joymap-core.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
