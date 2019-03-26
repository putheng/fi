<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \URL::forceScheme('https');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Migration generator code
//        if ($this->app->environment() == 'local') {
//            $this->app->register(\Way\Generators\GeneratorsServiceProvider::class);
//            $this->app->register(\Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
//            $this->app->register(\Krlove\EloquentModelGenerator\Provider\GeneratorServiceProvider::class);
//            //$this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
//        }
    }
}
