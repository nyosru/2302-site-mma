<?php

namespace App\Providers;

use App\Services\PermissionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro(
            'sortByDate', function (string $column = 'created_at', bool $descending = true) {
                /* @var $this Collection */
                return $this->sortBy(
                    function ($datum) use ($column) {
                        return strtotime(((object)$datum)->$column);
                    }, SORT_REGULAR, $descending
                );
            }
        );

        Blade::if(
            'allowed', function ($permission, $action) {
                return Auth::check() && PermissionService::allowed(Auth::user()->id, $permission, $action);
            }
        );
    }
}
