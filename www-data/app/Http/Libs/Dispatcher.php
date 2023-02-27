<?php

namespace App\Http\Libs;

use App\Models\AdminNotification;
use App\Models\App;
use App\Models\BannedId;
use App\Models\Client;
use App\Models\ClientLog;
use App\Models\Setting;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class Dispatcher
{
    const IP = 'ip';

    public static function isClientTrusted(Request $request, App $app, $type = 'launch')
    {
        $bid = $request->input('bid');
        $partnerId = $request->input('grts');
        $bannedReason = false;

        $log = [];

        //Получение списка трастовых девайсов (это тестеры)
        if ($trustedDevices = Setting::where('key', 'trusted_devices')->first()) {
            $value = str_replace(["\r", " "], '', $trustedDevices->value);
            $trustedDevices = explode("\n", $value);
        } else {
            $trustedDevices = [];
        }

        //Трастовый девайс не может быть забанен
        if ($bid && !in_array($bid, $trustedDevices)) {
            $bannedId = BannedId::where('app_id', $app->id)->where('client_bid', $bid)->first();
        } else {
            $bannedId = false;
        }


        $log[] = "bannedID: " . (!$bannedId ? 'false' : json_encode($bannedId));

        $productionUrl = null;
        $bannerUrl = $app->banner_url;

        $banned = false;
        $bannedByTime = false;
        $timeIsTheOnlyBanReason = false; //

        //Проверка допустимости клиента, если он уже не в бане
        if (!$bannedId) {
            //Доступ только для клиентов, которым выдан tid (выдается при скачке)
            if ($app->ban_by_tid) {
                $client = Client::where('tid', $request->get('tid'))->first();

                if (!$client or !$request->get('tid')) {
                    $banned = true;
                    $bannedReason = 'banned by tid';
                }

            } else {
                $client = null;

                $country = @$_GET['country'];
                $log[] = "country: " . $country;
                $cip = (isset($_GET['proxied_ip'])) ? $_GET['proxied_ip'] : request()->ip();

                if ($app->ban_if_countries_not_matched) {
                    $SxGeo = new SxGeo(storage_path('app' . DIRECTORY_SEPARATOR . 'SxGeo.dat'));
                    $ipCountry = $SxGeo->getCountry($cip);

                    if ($country && $ipCountry) {
                        if (strtolower(trim($country)) !== strtolower(trim($ipCountry))) {
                            $banned = true;
                            $bannedReason = 'banned because ip country not allowed';
                        }
                    }
                }

                if ($app->allowed_countries_filter && !$banned) {
                    $allowedCountries = $app->getAllowedCountries();
                    $allowedCountries = explode(',', $allowedCountries);
                    $allowedCountries = array_map(
                        function ($item) {
                            return strtolower($item);
                        }, $allowedCountries
                    );

                    if ($app->country_detection_type == 'sim' && !$country && $app->ban_if_no_country) {
                        $device = @$_GET['device'];
                        if ($device) {
                            $suspectDevices = explode(',', $app->suspect_devices_list);
                            // Если страна не предоставлена и устройство в перечне подозрительных, то это бан
                            foreach ($suspectDevices as $sDevice) {
                                if (substr_count(strtolower(trim($device)), strtolower(trim($sDevice)))) {
                                    $banned = true;
                                    $bannedReason = 'banned because device not allowed';
                                }
                            }
                        }
                    } else if ((!$country) || (strlen($country) < 2) || ($app->country_detection_type == self::IP)) {
                        $ip = (isset($_GET['proxied_ip'])) ? $_GET['proxied_ip'] : request()->ip();
                        $log[] = "trying to know the country by ip " . $ip;

                        $SxGeo = new SxGeo(storage_path('app' . DIRECTORY_SEPARATOR . 'SxGeo.dat'));
                        $country = $SxGeo->getCountry($cip);

                        if (!$country) {
                            $country = @$_GET['country'];
                        }

                        $log[] = "country by ip: " . $country;
                    }

                    if ($country) {
                        $cb = explode('_', $country);
                        if (count($cb) > 1) {
                            $country = $cb[1];
                        }
                    }

                    $log[] = "finded country: " . $country;


                    if (!$country or !in_array(strtolower($country), $allowedCountries)) {
                        $banned = true;
                        $log[] = "banned by country: " . json_encode($allowedCountries);
                        $bannedReason = 'banned because ip country not allowed';


                    }
                }

                if ($app->banned_devices_filter) {
                    $bannedDevices = $app->getBannedDevices();
                    $bannedDevices = explode(',', $bannedDevices);
                    $bannedDevices = array_map('strtolower', $bannedDevices);
                    $device = @$_GET['device'];
                    //if (!$device | in_array(strtolower($device), $bannedDevices)) {
                    //    $banned = true;
                    //}

                    foreach ($bannedDevices as $devs) {
                        if (!$device | preg_match("/{$devs}/i", $device)) {
                            $banned = true;

                            $log[] = "banned_devices_filter: " . $device;

                            $bannedReason = 'banned because device not allowed';


                            break;
                        }
                    }
                }

                if ($partnerId && $app->banned_partners_filter) {
                    $bannedPartners = $app->getBannedPartners();
                    $bannedPartners = explode(',', $bannedPartners);
                    $bannedPartners = array_map('strtolower', $bannedPartners);
                    if (in_array(strtolower($partnerId), $bannedPartners)) {
                        $banned = true;

                        $log[] = "bannedPartners: " . $partnerId;

                        $bannedReason = 'banned because partner not allowed';


                    }
                }

                if ($app->banned_time_filter && $app->banned_time && $app->banned_time_end) {
                    $currentTime = date('H:i');

                    if (self::isBetween($app->banned_time, $app->banned_time_end, $currentTime)) {
                        if (!$banned) {
                            $timeIsTheOnlyBanReason = true;
                        }
                        $banned = true;
                        $bannedByTime = true;

                        $log[] = "bannedByTime: true";

                        $bannedReason = 'banned because time not allowed';


                    }
                }

            }

            if ($bannedDevices = Setting::where('key', 'banned_devices')->first()) {
                $bannedDevices = explode(',', $bannedDevices->value);
                $device = @$_GET['device'];
                if ($bannedDevices && in_array($device, $bannedDevices)) {
                    $banned = true;
                    $timeIsTheOnlyBanReason = false;

                    $log[] = "bannedDevices: " . $device;

                    $bannedReason = 'banned because device from settings not allowed';


                }
            }

            if ($client && $bannedIps = Setting::where('key', 'banned_ip')->first()) {
                $bannedIps = explode(',', $bannedIps->value);
                if ($bannedIps && in_array($client->ip, $bannedIps)) {
                    $timeIsTheOnlyBanReason = false;
                    $banned = true;
                    $log[] = "banned_ip: " . $client->ip;


                    $bannedReason = 'banned because ip from settings not allowed';


                }
            }
        } else {
            $banned = true;
            $log[] = "banned previously by bannedId";
            $bannedReason = 'banned because previously inserted in BannedId list';

        }

        if ($bid && in_array($bid, $trustedDevices)) {
            //$banned = false;
        }



        if (!$banned) {
            $productionUrl = $app->url;
        } else if (!$bannedByTime && $bid && !$bannedId) {
            //Баним девайс навсегда
            if (!in_array($bid, $trustedDevices)) {
                $bannedId = new BannedId();
                $bannedId->app_id = $app->id;
                $bannedId->client_bid = $bid;
                $bannedId->save();

                $log[] = "created bannedId";

            }
        }

        $newClick = new Client();
        $newClick->click_type = Client::TYLE_LAUNCH;
        $newClick->ip = (isset($_GET['proxied_ip'])) ? $_GET['proxied_ip'] : request()->ip();
        $newClick->tid = htmlspecialchars($request->get('tid'));
        $newClick->request = json_encode($_GET);
        $newClick->app_id = $app->id;

        if ($productionUrl) {
            $newClick->result = 1;
        } else {
            $newClick->result = 0;
        }

        $columns = Schema::getColumnListing($newClick->getTable());

        foreach ($_GET as $field => $value) {
            if (in_array($field, $columns)) {
                $newClick->$field = $value;
            }
        }

        $cip = (isset($_GET['proxied_ip'])) ? $_GET['proxied_ip'] : request()->ip();
        $SxGeo = new SxGeo(storage_path('app' . DIRECTORY_SEPARATOR . 'SxGeo.dat'));
        $newClick->country =  $SxGeo->getCountry($cip);

        if ($newClick->country) {
            $ca = explode('_', $newClick->country);

            if (count($ca) > 1) {
                $newClick->country = $ca[1];
            }
        }

        $newClick->save();


        $log[] = "created client: " . $newClick->id;


        //для TWA bannerURL должен быть всегда
        if ($type != 'twa' && !$timeIsTheOnlyBanReason) {
            $bannerUrl = null;
        }


        //Макросы продашн урла
        if ($productionUrl) {
            foreach ($request->all() as $key => $value) {
                $productionUrl = str_replace('#' . $key . '#', urlencode(htmlspecialchars($value)), $productionUrl);
            }

            $productionUrl = SxGeo::trim($productionUrl, $request, $app);
        }

        if ($bannedReason) {
            $log[] = 'Ban reason: ' . $bannedReason;
        }

        ClientLog::create(
            [
                'app_id' => $app->id,
                'bid' => $bid,
                'log' => $log,
                'client_id' => $newClick->id,
                'type' => Client::TYLE_LAUNCH,
            ]
        );

        try {
            $test = json_decode($newClick->request);
        } catch (Exception $exception) {
            AdminNotification::create(
                [
                    'app_id' => $newClick->app_id,
                    'text' => "Receive invalid GET params from client: " . $newClick->id,
                    'readed' => false,
                ]
            );
        }

        return [
            'productionUrl' => trim($productionUrl),
            'bannerUrl' => trim($bannerUrl),
        ];
    }

    private static function isBetween($from, $till, $input)
    {
        $f = DateTime::createFromFormat('!H:i', $from);
        $t = DateTime::createFromFormat('!H:i', $till);
        $i = DateTime::createFromFormat('!H:i', $input);
        if ($f > $t) {
            $t->modify('+1 day');
        }
        return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
    }
}
