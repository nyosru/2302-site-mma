<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IpSubnetRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (
            filter_var($value, FILTER_VALIDATE_IP) !== false ||
            preg_match('~^(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)(/\d{2}(\d\.\d{1,3}\.\d{1,3}\.\d{1,3})?)?(-\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})?~', $value) == 1
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid IP address or subnet';
    }
}
