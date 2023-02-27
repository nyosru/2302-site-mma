<?php

namespace App\Models;

use App\Libs\SxGeo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Client
 *
 * @property int $id
 * @property int $app_id
 * @property int|null $status
 * @property string $tid
 * @property string $ip
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $request
 * @property string|null $country
 * @property int|null $result
 * @property string|null $ifa
 * @property string|null $ifa_md5
 * @property string|null $android_id_md5
 * @property string|null $country_code
 * @property string|null $campaign_id
 * @property string|null $campaign_name
 * @property int|null $game_id
 * @property int|null $source_game_id
 * @property string|null $os
 * @property string|null $device_type
 * @property string|null $creative_pack
 * @property string|null $creative_pack_id
 * @property string|null $language
 * @property string|null $user_agent
 * @property string|null $device_make
 * @property string|null $device_model
 * @property string|null $cpi
 * @property string|null $video_orientation
 * @property string|null $screen_size
 * @property string|null $screen_density
 * @property string|null $click_type
 * @property-read App|null $app
 * @property-read ClientLog|null $clientLog
 * @method static Builder|Client newModelQuery()
 * @method static Builder|Client newQuery()
 * @method static Builder|Client query()
 * @method static Builder|Client whereAndroidIdMd5($value)
 * @method static Builder|Client whereAppId($value)
 * @method static Builder|Client whereCampaignId($value)
 * @method static Builder|Client whereCampaignName($value)
 * @method static Builder|Client whereClickType($value)
 * @method static Builder|Client whereCountry($value)
 * @method static Builder|Client whereCountryCode($value)
 * @method static Builder|Client whereCpi($value)
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereCreativePack($value)
 * @method static Builder|Client whereCreativePackId($value)
 * @method static Builder|Client whereDeviceMake($value)
 * @method static Builder|Client whereDeviceModel($value)
 * @method static Builder|Client whereDeviceType($value)
 * @method static Builder|Client whereGameId($value)
 * @method static Builder|Client whereId($value)
 * @method static Builder|Client whereIfa($value)
 * @method static Builder|Client whereIfaMd5($value)
 * @method static Builder|Client whereIp($value)
 * @method static Builder|Client whereLanguage($value)
 * @method static Builder|Client whereOs($value)
 * @method static Builder|Client whereRequest($value)
 * @method static Builder|Client whereResult($value)
 * @method static Builder|Client whereScreenDensity($value)
 * @method static Builder|Client whereScreenSize($value)
 * @method static Builder|Client whereSourceGameId($value)
 * @method static Builder|Client whereStatus($value)
 * @method static Builder|Client whereTid($value)
 * @method static Builder|Client whereUpdatedAt($value)
 * @method static Builder|Client whereUserAgent($value)
 * @method static Builder|Client whereVideoOrientation($value)
 * @mixin IdeHelperClient
 */
class Client extends Model
{
    const TYLE_DOWNLOAD = 'd';
    const TYLE_LAUNCH = 'l';
    const TYLE_EVENT = 'e';

    protected $fillable = [
        "app_id",
        "status",
        "tid",
        "ip",
        "request",
        "country",
        'result',
        'click_type',
        'ifa',
        'ifa_md5',
        'android_id_md5',
        'country_code',
        'campaign_id',
        'campaign_name',
        'game_id',
        'source_game_id',
        'os',
        'device_type',
        'creative_pack',
        'creative_pack_id',
        'language',
        'user_agent',
        'device_make',
        'device_model',
        'cpi',
        'video_orientation',
        'screen_size',
        'screen_density'
    ];

    public function typeName()
    {
        switch ($this->click_type) {
        case self::TYLE_DOWNLOAD:
            return 'download';
        case self::TYLE_LAUNCH:
            return 'launch';
        case self::TYLE_EVENT:
            return 'event';
        }

        return '-';
    }

    public function clientLog(): HasOne
    {
        return $this->hasOne(ClientLog::class);
    }

    public function app()
    {
        return $this->hasOne('App\Models\App', 'id', 'app_id');
    }

    // TODO метод получения не должен делать сохранение
    public function getCountry()
    {
        if (!$this->country) {
            $SxGeo = new SxGeo(storage_path('app' . DIRECTORY_SEPARATOR . 'SxGeo.dat'));

            $this->country = $SxGeo->getCountry($this->ip);
            $this->save();
        }

        return $this->country;
    }

    public function getLogsLine()
    {
        //        $url = route('admin.apps.view', [$this]);
        return "Client {$this->id}";
    }
}
