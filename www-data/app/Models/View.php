<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\View
 *
 * @property int $id
 * @property int $app_id
 * @property string $request
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $ifa
 * @property string|null $ifa_md5
 * @property string|null $android_id_md5
 * @property string|null $country_code
 * @property string|null $campaign_id
 * @property string|null $campaign_name
 * @property int|null $game_id
 * @property int|null $source_game_id
 * @property string|null $os
 * @property string|null $ip
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
 * @property-read App|null $app
 * @method static Builder|View newModelQuery()
 * @method static Builder|View newQuery()
 * @method static Builder|View query()
 * @method static Builder|View whereAndroidIdMd5($value)
 * @method static Builder|View whereAppId($value)
 * @method static Builder|View whereCampaignId($value)
 * @method static Builder|View whereCampaignName($value)
 * @method static Builder|View whereCountryCode($value)
 * @method static Builder|View whereCpi($value)
 * @method static Builder|View whereCreatedAt($value)
 * @method static Builder|View whereCreativePack($value)
 * @method static Builder|View whereCreativePackId($value)
 * @method static Builder|View whereDeviceMake($value)
 * @method static Builder|View whereDeviceModel($value)
 * @method static Builder|View whereDeviceType($value)
 * @method static Builder|View whereGameId($value)
 * @method static Builder|View whereId($value)
 * @method static Builder|View whereIfa($value)
 * @method static Builder|View whereIfaMd5($value)
 * @method static Builder|View whereIp($value)
 * @method static Builder|View whereLanguage($value)
 * @method static Builder|View whereOs($value)
 * @method static Builder|View whereRequest($value)
 * @method static Builder|View whereScreenDensity($value)
 * @method static Builder|View whereScreenSize($value)
 * @method static Builder|View whereSourceGameId($value)
 * @method static Builder|View whereUpdatedAt($value)
 * @method static Builder|View whereUserAgent($value)
 * @method static Builder|View whereVideoOrientation($value)
 * @mixin IdeHelperView
 */
class View extends Model
{
    protected $fillable = [
        "app_id", "request",
        'ifa', 'ifa_md5', 'android_id_md5', 'ip', 'country_code', 'campaign_id', 'campaign_name', 'game_id', 'source_game_id', 'os', 'device_type', 'creative_pack', 'creative_pack_id',
        'language', 'user_agent', 'device_make', 'device_model', 'cpi', 'video_orientation', 'screen_size', 'screen_density'
    ];

    public function app()
    {
        return $this->hasOne('App\Models\App', 'id', 'app_id');
    }
}
