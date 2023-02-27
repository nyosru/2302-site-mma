<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $token
 * @property string $ip
 * @property string $last_used_at
 */
class Token extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'ip',
        'last_used_at'
    ];
}
