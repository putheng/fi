<?php

namespace App\Providers;

use App\ViewComposers\ClinicsComposer;
use App\ViewComposers\InfoComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('map', ClinicsComposer::class);
        View::composer(['template', 'admin.question.partials._recommend'], InfoComposer::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
