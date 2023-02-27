<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AppCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\App';

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
