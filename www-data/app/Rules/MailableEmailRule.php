<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MailableEmailRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (null == $value) {
            return false;
        }

        $domain = explode('@', $value);
        if (!isset($domain[1])) {
            return false;
        }
        $domain = $domain[1];
        try {
            $arr = dns_get_record($domain, DNS_MX);
            if (isset($arr[0]) && $arr[0]['host'] == $domain && !empty($arr[0]['target'])) {
                return true;
            }
        } catch (\Throwable) {
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please check email, it is incorrect';
    }
}
