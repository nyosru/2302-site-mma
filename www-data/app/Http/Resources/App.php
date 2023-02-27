<?php

namespace App\Http\Resources;

use App\Facades\Time;
use App\Models\Client;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class App extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        $accept = Client::where('app_id', $this->id)->where('click_type', Client::TYLE_LAUNCH)->where('result', 1)->count();
        $reject = Client::where('app_id', $this->id)->where('click_type', Client::TYLE_LAUNCH)->where(
            function ($query) {
                $query->where('result', 0)->orWhereNull('result');
            }
        )->count();
        $count = Client::where('app_id', $this->id)->where('click_type', Client::TYLE_LAUNCH)->count();

        return [
            'id' => $this->id,
            'status' => $this->status,
            'name' => $this->name,
            'url' => $this->url,
            'banner_url' => $this->banner_url,
            'app_id' => $this->app_id,
            'download_url' => $this->download_url,
            'game_id' => $this->game_id,
            'views_count' => View::where('app_id', $this->id)->count(),
            'downloads_count' => Client::where('app_id', $this->id)->where('click_type', Client::TYLE_DOWNLOAD)->count(),
            'launches_count' => $count,
            'cr' => 0,
            'cost_per_install' => '0',
            'banned_countries' => $this->banned_countries,
            'banned_devices' => $this->banned_devices,
            'banned_time' => $this->banned_time,
            'banned_time_end' => $this->banned_time_end,
            'ban_by_tid' => $this->ban_by_tid,
            'allowed_countries' => $this->allowed_countries,
            'allowed_countries_filter' => $this->allowed_countries_filter,
            'banned_devices_filter' => $this->banned_devices_filter,
            'banned_time_filter' => $this->banned_time_filter,
            'af_dev_key' => $this->af_dev_key,
            'unity_dev_key' => $this->unity_dev_key,
            'unity_campaign_id' => $this->unity_campaign_id,
            'stat' => [
                'launches' => [
                    'count' => $count,
                    'accept' => $accept,
                    'reject' => $reject,
                    'ATR' => ($accept) ? floor(($accept / $count) * 100) : 0
                ]

            ],
            'created_at' => Time::applyTimezone($this->created_at),
            'updated_at' => Time::applyTimezone($this->updated_at),
            'fb_access_token' => $this->fb_access_token,
        ];
    }
}
