<?php

namespace App\Facades;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Carbon applyTimezone(Carbon|bool $time)
 * @method static Carbon applySavingTimezone(Carbon|bool $time)
 */
class Time extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'time';
    }
}
