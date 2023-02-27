<?php

namespace App\Http\Resources;

use App\Facades\Time;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Setting extends JsonResource
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
            'key' => $this->key,
            'value' => $this->value,
            'created_at' => Time::applyTimezone($this->created_at),
            'updated_at' => Time::applyTimezone($this->updated_at),
        ];
    }
}
