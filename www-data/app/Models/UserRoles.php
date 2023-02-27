<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserRoles
 *
 * @property-read string $name
 * @method static Builder|UserRoles newModelQuery()
 * @method static Builder|UserRoles newQuery()
 * @method static Builder|UserRoles query()
 * @property int $id
 * @property string|null $key
 * @property array|null $allows
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static Builder|UserRoles whereAllows($value)
 * @method static Builder|UserRoles whereCreatedAt($value)
 * @method static Builder|UserRoles whereDescription($value)
 * @method static Builder|UserRoles whereId($value)
 * @method static Builder|UserRoles whereKey($value)
 * @method static Builder|UserRoles whereName($value)
 * @method static Builder|UserRoles whereUpdatedAt($value)
 * @mixin IdeHelperUserRoles
 */
class UserRoles extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'user_roles'; // or null
    protected $primaryKey = 'key';

    // In Laravel 6.0+ make sure to also set $keyType
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'allows',
        'name',
        'description'
    ];

    protected $casts = [
        'allows' => 'array'
    ];


    /**
     * @param  $value
     * @return string
     */
    public function getNameAttribute($value): string
    {

        if ($value) {
            return $value;
        }


        if (config('enum.user_roles.' . $this->key)) {
            return config('enum.user_roles.' . $this->key);
        } else {
            return $this->key;
        }
    }

    /**
     * @param  $el
     * @return bool
     */
    public function has($el): bool
    {
        return in_array($el, $this->allows) or in_array('all', $this->allows);
    }

    public function getLogsLine()
    {
        $url = route('admin.view_role', [$this]);
        return "<a href='{$url}'>Role: {$this->key}</a>";
    }
}
