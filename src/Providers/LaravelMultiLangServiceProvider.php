<?php

namespace DilbrinAzad\LaravelMultiLang\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelMultiLangServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

       $this->publishes([
            __DIR__.'/../../config/multilanguage.php' => config_path('multilang.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders'),
        ], 'seeders');

        $this->publishes([
            __DIR__.'/../src/Models' => app_path('Models'),
        ], 'models');

        $this->publishes([
            __DIR__.'/../src/Http/Controllers' => app_path('Http/Controllers'),
        ], 'controllers');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../src/View/Components' => app_path('View/Components'),
        ], 'components');

        $this->publishes([
            __DIR__.'/../src/Traits' => app_path('Traits'),
        ], 'traits');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/multilang.php', 'multilang');
    }
}
