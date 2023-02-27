<?php

namespace App\Providers;


use App\Services\TimeService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class TimeFacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        App::bind(
            'time', function () {
                return new TimeService();
            }
        );
    }
}
