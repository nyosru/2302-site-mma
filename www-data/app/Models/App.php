<?php

namespace App\Models;

use App\Libs\SxGeo;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * App\Models\App
 *
 * @property int $id
 * @property int|null $status
 * @property string $name
 * @property string $url
 * @property string|null $banner_url
 * @property string $app_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $download_url
 * @property string|null $game_id
 * @property string|null $banned_countries
 * @property string|null $banned_devices
 * @property string|null $banned_time
 * @property string|null $banned_time_end
 * @property int|null $ban_by_tid
 * @property string|null $allowed_countries
 * @property int|null $allowed_countries_filter
 * @property int|null $banned_devices_filter
 * @property int|null $banned_time_filter
 * @property string|null $banned_partners
 * @property string|null $dev_key
 * @property string|null $af_dev_key
 * @property string|null $unity_dev_key
 * @property string|null $unity_campaign_id
 * @property string|null $fb_access_token
 * @property int|null $country_by_ip
 * @property string|null $country_detection_type
 * @property int|null $ban_if_no_country
 * @property string|null $suspect_devices_list
 * @property int|null $ban_if_countries_not_matched
 * @method static \Illuminate\Database\Eloquent\Builder|App newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|App newQuery()
 * @method static Builder|App onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|App query()
 * @method static \Illuminate\Database\Eloquent\Builder|App whereAfDevKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereAllowedCountries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereAllowedCountriesFilter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBanByTid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBanIfCountriesNotMatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBanIfNoCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBannedCountries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBannedDevices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBannedDevicesFilter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBannedPartners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBannedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBannedTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBannedTimeFilter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBannerUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereCountryByIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereCountryDetectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereDevKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereDownloadUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereFbAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereSuspectDevicesList($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereUnityCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereUnityDevKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereUrl($value)
 * @method static Builder|App withTrashed()
 * @method static Builder|App withoutTrashed()
 * @property Carbon|null $deleted_at
 * @property string|null $banned_partners_filter
 * @method static \Illuminate\Database\Eloquent\Builder|App whereBannedPartnersFilter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|App whereDeletedAt($value)
 * @property string|null $store_id
 * @method static \Illuminate\Database\Eloquent\Builder|App whereStoreId($value)
 * @mixin IdeHelperApp
 */
class App extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const APP_STATE_NONE = 0; // live
    const APP_STATE_LIVE = 1; // live
    const APP_STATE_BAN = 2; // ban

    protected $fillable = [
        'af_dev_key',
        'unity_dev_key',
        'unity_campaign_id',
        'dev_key',
        'allowed_countries_filter',
        'banned_devices_filter',
        'banned_time_filter',
        "status",
        "name",
        "url",
        "banner_url",
        "app_id",
        "download_url",
        "game_id",
        "banned_countries",
        'banned_devices',
        'banned_time',
        'banned_time_end',
        'ban_by_tid',
        'allowed_countries',
        'banned_partners_filter',
        'banned_partners',
        'fb_access_token',
        'country_detection_type',
        'ban_if_countries_not_matched',
        'ban_if_no_country',
        'app_state_id',
        'store_name',
        'store_id'
    ];

    //

    public function makeDownloadUrl()
    {
        $SxGeo = new SxGeo(storage_path('app' . DIRECTORY_SEPARATOR . 'SxGeo.dat'));

        $client = new Client();
        $client->click_type = Client::TYLE_DOWNLOAD;
        $client->ip = (isset($_GET['proxied_ip'])) ? $_GET['proxied_ip'] : request()->ip();
        $client->tid = Str::random(12);
        $client->request = json_encode($_GET); // TODO Нужно ли это?
        $client->app_id = $this->id;
        $client->country = $SxGeo->getCountry($this->ip);

        $columns = Schema::getColumnListing($client->getTable());

        foreach ($_GET as $field => $value) {
            if (in_array($field, $columns)) {
                $client->$field = $value;
            }
        }

        $client->save();


        try {
            $test = json_decode($client->request);
        } catch (Exception $exception) {
            AdminNotification::create(
                [
                    'app_id' => $client->app_id,
                    'text' => "Receive invalid GET params from client: " . $client->id,
                    'readed' => false,
                ]
            );
        }

        $trackId = $client->tid;

        if (!$this->download_url) {
            $this->download_url = 'market://details?id=' . $this->app_id . '&referrer=tid%3D#tid#';
        }

        return str_replace(['#tid#'], [$trackId], $this->download_url);
    }

    public function getBannedCountries()
    {
        return $this->banned_countries;
    }

    public function getBannedDevices()
    {
        return $this->banned_devices;
    }

    public function getBannedPartners()
    {
        return $this->banned_partners;
    }

    public function getAllowedCountries()
    {
        return $this->allowed_countries;
    }

    public function getLogsLine()
    {
        $url = route('admin.apps.view', [$this]);
        return "<a href='{$url}'>App {$this->name} [{$this->id}]</a>";
    }
}
