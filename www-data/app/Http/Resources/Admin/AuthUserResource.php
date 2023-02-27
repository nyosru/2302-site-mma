<?php

namespace App\Http\Resources\Admin;

use App\Facades\Time;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'profile_image' => $this->profile_image,
            'created_at' => Time::applyTimezone($this->created_at),
            'updated_at' => Time::applyTimezone($this->updated_at),
            'roles' => RoleResource::collection($this->roles),
        ];
    }
}
