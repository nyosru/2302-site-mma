<?php

namespace App\Models\Accessors;

trait CategoryStatus
{
    public function getStatusNameAttribute()
    {
        if (!$this->status) {
            $this->status = 1;
        }
        return self::meta()['statuses'][$this->status];
    }

    public function getCategoryNameAttribute()
    {
        return ($this->category) ? $this->category->name : '-';
    }
}
