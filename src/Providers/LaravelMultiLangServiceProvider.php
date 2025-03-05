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
            __DIR__.'/../../config/multilang.php' => config_path('multilang.php'),
            __DIR__.'/../../database/migrations' => database_path('migrations'),
             __DIR__.'/../Models' => app_path('Models'),
            __DIR__.'/../../database/seeders' => database_path('seeders'),
            __DIR__.'/../Http/Controllers' => app_path('Http/Controllers'),
             __DIR__.'/../Traits' => app_path('Traits'),
            __DIR__.'/../../resources/views' => resource_path('views'),
            __DIR__.'/../View/Components' => app_path('View/Components'),
        ], 'multilang-resources');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/multilang.php', 'multilang');
    }
}
