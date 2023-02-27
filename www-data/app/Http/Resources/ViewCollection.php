<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ViewCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\View';

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
