<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Carbon;

class TimeService
{
    private static $cache = [];

    /**
     * @param Carbon|bool $time
     * @return Carbon|\Carbon\Carbon
     */
    public function applyTimezone(Carbon|bool $time): Carbon|\Carbon\Carbon
    {
        $timezoneShift = $this->getTimezoneShift($time);

        return (clone $time)->setTimezone('GMT' . ($timezoneShift >= 0 ? '+' : '') . $timezoneShift);
    }

    /**
     * @param \Carbon\Carbon|Carbon $time
     * @return int
     */
    public function getTimezoneShift(\Carbon\Carbon|Carbon $time): int
    {
        $timezoneShift = self::$cache['timezone'] ?? 3;
        if (!isset(self::$cache['timezone'])) {
            $tz = Setting::where('key', 'timezone')->first();
            if (null !== $tz) {
                $timezoneShift = $tz->value;
                self::$cache['timezone'] = $timezoneShift;
            }
        }
        return $timezoneShift;
    }

    /**
     * @param Carbon|\Carbon\Carbon|bool|null $time
     * @return Carbon|\Carbon\Carbon
     */
    public function applySavingTimezone(Carbon|\Carbon\Carbon|bool|null $time): Carbon|\Carbon\Carbon
    {
        if (null === $time) {
            return (clone $time)->setTimezone('GMT');
        }
        $timezoneShift = $this->getTimezoneShift($time);

        return (clone $time)->setTimezone('GMT' . ($timezoneShift >= 0 ? -$timezoneShift : abs($timezoneShift)));
    }
}
