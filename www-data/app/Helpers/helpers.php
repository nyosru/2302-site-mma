<?php // Code within app\Helpers\Helper.php
namespace App\Helpers;

use Config;

class Helper
{
    public static function applClasses()
    {
        // default data value
        $dataDefault = [
            'mainLayoutType' => 'vertical-menu',
            'theme' => 'light',
            'isContentSidebar' => false,
            'pageHeader' => false,
            'bodyCustomClass' => '',
            'navbarBgColor' => 'bg-white',
            'navbarType' => 'fixed',
            'isMenuCollapsed' => false,
            'footerType' => 'static',
            'templateTitle' => '',
            'isCustomizer' => true,
            'isCardShadow' => true,
            'isScrollTop' => true,
            'defaultLanguage' => 'en',
            'direction' => env('MIX_CONTENT_DIRECTION', 'ltr'),
        ];

        //if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
        $data = array_merge($dataDefault, config('custom.custom'));

        // all available option of materialize template
        $allOptions = [
            'mainLayoutType' => array('vertical-menu', 'horizontal-menu', 'vertical-menu-boxicons'),
            'theme' => array('light' => 'light', 'dark' => 'dark', 'semi-dark' => 'semi-dark'),
            'isContentSidebar' => array(false, true),
            'pageHeader' => array(false, true),
            'bodyCustomClass' => '',
            'navbarBgColor' => array('bg-white', 'bg-primary', 'bg-success', 'bg-danger', 'bg-info', 'bg-warning', 'bg-dark'),
            'navbarType' => array('fixed' => 'fixed', 'static' => 'static', 'hidden' => 'hidden'),
            'isMenuCollapsed' => array(false, true),
            'footerType' => array('fixed' => 'fixed', 'static' => 'static', 'hidden' => 'hidden'),
            'templateTitle' => '',
            'isCustomizer' => array(true, false),
            'isCardShadow' => array(true, false),
            'isScrollTop' => array(true, false),
            'defaultLanguage' => array('en' => 'en', 'pt' => 'pt', 'fr' => 'fr', 'de' => 'de'),
            'direction' => array('ltr' => 'ltr', 'rtl' => 'rtl'),
        ];
        // navbar body class array
        $navbarBodyClass = [
            'fixed' => 'navbar-sticky',
            'static' => 'navbar-static',
            'hidden' => 'navbar-hidden',
        ];
        $navbarClass = [
            'fixed' => 'fixed-top',
            'static' => 'navbar-static-top',
            'hidden' => 'd-none',
        ];

        // footer class
        $footerBodyClass = [
            'fixed' => 'fixed-footer',
            'static' => 'footer-static',
            'hidden' => 'footer-hidden',
        ];
        $footerClass = [
            'fixed' => 'footer-sticky',
            'static' => 'footer-static',
            'hidden' => 'd-none',
        ];

        //if any options value empty or wrong in custom.php config file then set a default value
        foreach ($allOptions as $key => $value) {
            if (gettype($data[$key]) === gettype($dataDefault[$key])) {
                if (is_string($data[$key])) {
                    if (is_array($value)) {

                        $result = array_search($data[$key], $value);
                        if (empty($result)) {
                            $data[$key] = $dataDefault[$key];
                        }
                    }
                }
            } else {
                if (is_string($dataDefault[$key])) {
                    $data[$key] = $dataDefault[$key];
                } elseif (is_bool($dataDefault[$key])) {
                    $data[$key] = $dataDefault[$key];
                } elseif (is_null($dataDefault[$key])) {
                    is_string($data[$key]) ? $data[$key] = $dataDefault[$key] : '';
                }
            }
        }

        //  above arrary override through dynamic data
        $layoutClasses = [
            'mainLayoutType' => $data['mainLayoutType'],
            'theme' => $data['theme'],
            'isContentSidebar' => $data['isContentSidebar'],
            'pageHeader' => $data['pageHeader'],
            'bodyCustomClass' => $data['bodyCustomClass'],
            'navbarBgColor' => $data['navbarBgColor'],
            'navbarType' => $navbarBodyClass[$data['navbarType']],
            'navbarClass' => $navbarClass[$data['navbarType']],
            'isMenuCollapsed' => $data['isMenuCollapsed'],
            'footerType' => $footerBodyClass[$data['footerType']],
            'footerClass' => $footerClass[$data['footerType']],
            'templateTitle' => $data['templateTitle'],
            'isCustomizer' => $data['isCustomizer'],
            'isCardShadow' => $data['isCardShadow'],
            'isScrollTop' => $data['isScrollTop'],
            'defaultLanguage' => $data['defaultLanguage'],
            'direction' => $data['direction'],
        ];

        // set default language if session hasn't locale value the set default language
        if (!session()->has('locale')) {
            app()->setLocale($layoutClasses['defaultLanguage']);
        }

        if (session()->has('dark')) {
            $layoutClasses['theme'] = 'dark';
        }

        return $layoutClasses;
    }

    // updatesPageConfig function override all configuration of custom.php file as page requirements.
    public static function updatePageConfig($pageConfigs)
    {
        $demo = 'custom';
        $custom = 'custom';

        if (isset($pageConfigs)) {
            if (count($pageConfigs) > 0) {
                foreach ($pageConfigs as $config => $val) {
                    Config::set($demo . '.' . $custom . '.' . $config, $val);
                }
            }
        }
    }

    /**
     * Determines if an IP is located in a specific range as specified via several alternative formats.
     * @param string $ip
     * @param string $range
     * Network ranges can be specified as:
     * 1. Wildcard format:     1.2.3.*
     * 2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
     * 3. Start-End IP format: 1.2.3.0-1.2.3.255
     * @return bool
     */
    public static function ipInRange($ip, $range): bool
    {
        if ($ip == $range) {
            return true;
        }
        if (str_contains($range, '/')) {
            // $range is in IP/NETMASK format
            list($range, $netmask) = explode('/', $range, 2);
            if (str_contains($netmask, '.')) {
                // $netmask is a 255.255.0.0 format
                $netmask = str_replace('*', '0', $netmask);
                $netmask_dec = ip2long($netmask);
                return ((ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec));
            } else {
                // $netmask is a CIDR size block
                // fix the range argument
                $x = explode('.', $range);
                while (count($x) < 4) $x[] = '0';
                list($a, $b, $c, $d) = $x;
                $range = sprintf("%u.%u.%u.%u", empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c, empty($d) ? '0' : $d);
                $range_dec = ip2long($range);
                $ip_dec = ip2long($ip);

                # Strategy 1 - Create the netmask with 'netmask' 1s and then fill it to 32 with 0s
                #$netmask_dec = bindec(str_pad('', $netmask, '1') . str_pad('', 32-$netmask, '0'));

                # Strategy 2 - Use math to create it
                $wildcard_dec = pow(2, (32 - $netmask)) - 1;
                $netmask_dec = ~$wildcard_dec;

                return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
            }
        } else {
            // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
            if (str_contains($range, '*')) { // a.b.*.* format
                // Just convert to A-B format by setting * to 0 for A and 255 for B
                $lower = str_replace('*', '0', $range);
                $upper = str_replace('*', '255', $range);
                $range = "$lower-$upper";
            }

            if (str_contains($range, '-')) { // A-B format
                list($lower, $upper) = explode('-', $range, 2);
                $lower_dec = (float)sprintf("%u", ip2long($lower));
                $upper_dec = (float)sprintf("%u", ip2long($upper));
                $ip_dec = (float)sprintf("%u", ip2long($ip));
                return (($ip_dec >= $lower_dec) && ($ip_dec <= $upper_dec));
            }

            // Range argument is not in 1.2.3.4/24 or 1.2.3.4/255.255.255.0 format
            return false;
        }
    }
}
