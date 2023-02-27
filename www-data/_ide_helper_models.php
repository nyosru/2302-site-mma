<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AdminNotification
 *
 * @property int $id
 * @property int|null $app_id
 * @property string $text
 * @property int $readed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read App|null $app
 * @method static Builder|AdminNotification newModelQuery()
 * @method static Builder|AdminNotification newQuery()
 * @method static Builder|AdminNotification query()
 * @method static Builder|AdminNotification whereAppId($value)
 * @method static Builder|AdminNotification whereCreatedAt($value)
 * @method static Builder|AdminNotification whereId($value)
 * @method static Builder|AdminNotification whereReaded($value)
 * @method static Builder|AdminNotification whereText($value)
 * @method static Builder|AdminNotification whereUpdatedAt($value)
 */
	class IdeHelperAdminNotification {}
}

namespace App\Models{
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
 */
	class IdeHelperApp {}
}

namespace App\Models{
/**
 * App\Models\BannedId
 *
 * @property int $id
 * @property int $app_id
 * @property string $client_bid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BannedId newModelQuery()
 * @method static Builder|BannedId newQuery()
 * @method static Builder|BannedId query()
 * @method static Builder|BannedId whereAppId($value)
 * @method static Builder|BannedId whereClientBid($value)
 * @method static Builder|BannedId whereCreatedAt($value)
 * @method static Builder|BannedId whereId($value)
 * @method static Builder|BannedId whereUpdatedAt($value)
 */
	class IdeHelperBannedId {}
}

namespace App\Models{
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
 */
	class IdeHelperClient {}
}

namespace App\Models{
/**
 * App\Models\ClientLog
 *
 * @property int $id
 * @property int|null $app_id
 * @property string|null $bid
 * @property string|null $type
 * @property array|null $log
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read App|null $app
 * @method static Builder|ClientLog newModelQuery()
 * @method static Builder|ClientLog newQuery()
 * @method static Builder|ClientLog query()
 * @method static Builder|ClientLog whereAppId($value)
 * @method static Builder|ClientLog whereBid($value)
 * @method static Builder|ClientLog whereCreatedAt($value)
 * @method static Builder|ClientLog whereId($value)
 * @method static Builder|ClientLog whereLog($value)
 * @method static Builder|ClientLog whereType($value)
 * @method static Builder|ClientLog whereUpdatedAt($value)
 */
	class IdeHelperClientLog {}
}

namespace App\Models{
/**
 * App\Models\Clients_event_params
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $client
 * @property mixed $params
 * @method static Builder|Clients_event_params newModelQuery()
 * @method static Builder|Clients_event_params newQuery()
 * @method static Builder|Clients_event_params query()
 * @method static Builder|Clients_event_params whereClient($value)
 * @method static Builder|Clients_event_params whereCreatedAt($value)
 * @method static Builder|Clients_event_params whereId($value)
 * @method static Builder|Clients_event_params whereParams($value)
 * @method static Builder|Clients_event_params whereUpdatedAt($value)
 */
	class IdeHelperClients_event_params {}
}

namespace App\Models{
/**
 * App\Models\Setting
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereCreatedAt($value)
 * @method static Builder|Setting whereId($value)
 * @method static Builder|Setting whereKey($value)
 * @method static Builder|Setting whereUpdatedAt($value)
 * @method static Builder|Setting whereValue($value)
 */
	class IdeHelperSetting {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $role_key
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read UserRoles|null $role
 * @property-read Collection|PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRoleKey($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @property int $has_bot_access
 * @property string $tg_usernamme
 * @method static Builder|User whereHasBotAccess($value)
 * @method static Builder|User whereTgUsernamme($value)
 */
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * App\Models\UserLog
 *
 * @property int $id
 * @property int $user_id
 * @property string $comment
 * @property string|null $model_type
 * @property string|null $model_key
 * @property array|null $old_model
 * @property array|null $new_model
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $model
 * @property-read User|null $user
 * @method static Builder|UserLog newModelQuery()
 * @method static Builder|UserLog newQuery()
 * @method static Builder|UserLog query()
 * @method static Builder|UserLog whereComment($value)
 * @method static Builder|UserLog whereCreatedAt($value)
 * @method static Builder|UserLog whereId($value)
 * @method static Builder|UserLog whereModelKey($value)
 * @method static Builder|UserLog whereModelType($value)
 * @method static Builder|UserLog whereNewModel($value)
 * @method static Builder|UserLog whereOldModel($value)
 * @method static Builder|UserLog whereUpdatedAt($value)
 * @method static Builder|UserLog whereUserId($value)
 */
	class IdeHelperUserLog {}
}

namespace App\Models{
/**
 * App\Models\UserRolePermission
 *
 * @property-read Collection|UserRolePermission[] $children
 * @property-read int|null $children_count
 * @property-read UserRolePermission|null $parent
 * @method static Builder|UserRolePermission newModelQuery()
 * @method static Builder|UserRolePermission newQuery()
 * @method static Builder|UserRolePermission query()
 * @property int $id
 * @property string|null $key_name
 * @property string|null $description
 * @property int|null $parent_id
 * @property string|null $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|UserRolePermission whereCreatedAt($value)
 * @method static Builder|UserRolePermission whereDescription($value)
 * @method static Builder|UserRolePermission whereId($value)
 * @method static Builder|UserRolePermission whereKeyName($value)
 * @method static Builder|UserRolePermission whereName($value)
 * @method static Builder|UserRolePermission whereParentId($value)
 * @method static Builder|UserRolePermission whereUpdatedAt($value)
 */
	class IdeHelperUserRolePermission {}
}

namespace App\Models{
/**
 * App\Models\UserRoles
 *
 * @property-read string $name
 * @method static Builder|UserRoles newModelQuery()
 * @method static Builder|UserRoles newQuery()
 * @method static Builder|UserRoles query()
 * @property int $id
 * @property string|null $key
 * @property array|null $allows
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static Builder|UserRoles whereAllows($value)
 * @method static Builder|UserRoles whereCreatedAt($value)
 * @method static Builder|UserRoles whereDescription($value)
 * @method static Builder|UserRoles whereId($value)
 * @method static Builder|UserRoles whereKey($value)
 * @method static Builder|UserRoles whereName($value)
 * @method static Builder|UserRoles whereUpdatedAt($value)
 */
	class IdeHelperUserRoles {}
}

namespace App\Models{
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
 */
	class IdeHelperView {}
}

