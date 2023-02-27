<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Client';

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
