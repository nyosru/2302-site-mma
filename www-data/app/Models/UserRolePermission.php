<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserRolePermission
 *
 * @property-read Collection|UserRolePermission[] $children
 * @property-read int|null $children_count
 * @property-read UserRolePermission|null $parent
 * @method static Builder|UserRolePermission newModelQuery()
 * @method static Builder|UserRolePermission newQuery()
 * @method static Builder|UserRolePermission query()
 * @property int $id
 * @property string|null $key_name
 * @property string|null $description
 * @property int|null $parent_id
 * @property string|null $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|UserRolePermission whereCreatedAt($value)
 * @method static Builder|UserRolePermission whereDescription($value)
 * @method static Builder|UserRolePermission whereId($value)
 * @method static Builder|UserRolePermission whereKeyName($value)
 * @method static Builder|UserRolePermission whereName($value)
 * @method static Builder|UserRolePermission whereParentId($value)
 * @method static Builder|UserRolePermission whereUpdatedAt($value)
 * @mixin IdeHelperUserRolePermission
 */
class UserRolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'key_name',
        'name',
        'description',
        'parent_id',
    ];


    public function getTextName(): string
    {
        if ($this->name) {
            return $this->name;
        }

        return $this->key_name;
    }


    public function parent(): HasOne
    {
        return $this->hasOne(UserRolePermission::class, 'id', 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(UserRolePermission::class, 'parent_id', 'id');
    }

    public function getLogsLine()
    {
        $url = route('admin.view_role_permissions', [$this]);
        return "<a href='{$url}'>Role permission: {$this->key_name}</a>";
    }
}
