<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SettingCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Setting';

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
