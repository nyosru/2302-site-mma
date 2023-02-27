<?php

namespace App\Http\Resources;

use App\Facades\Time;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Client extends JsonResource
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
            'result' => $this->result,
            'app_bundle_id' => ($this->app) ? $this->app->app_id : null,
            'status' => $this->status,
            'request' => $this->request,
            'country' => strtoupper($this->resource->getCountry()),
            'tid' => $this->tid,
            'ip' => $this->ip,
            'created_at' => Time::applyTimezone($this->created_at),
            'updated_at' => Time::applyTimezone($this->updated_at),
            'click_type' => $this->resource->typeName(),
            'date' => Time::applyTimezone($this->created_at)->format('d.m.Y H:i:s'),
        ];
    }
}
