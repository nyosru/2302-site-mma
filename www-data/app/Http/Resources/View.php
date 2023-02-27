<?php

namespace App\Http\Resources;

use App\Facades\Time;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class View extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'app_id' => $this->app_id,
            'app_bundle_id' => ($this->app) ? $this->app->app_id : null,
            'request' => $this->request,
            'date' => Time::applyTimezone($this->created_at)->format('d.m.Y H:i:s'),
        ];
    }
}
