<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserLog
 *
 * @property int $id
 * @property int $user_id
 * @property string $comment
 * @property string|null $model_type
 * @property string|null $model_key
 * @property array|null $old_model
 * @property array|null $new_model
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $model
 * @property-read User|null $user
 * @method static Builder|UserLog newModelQuery()
 * @method static Builder|UserLog newQuery()
 * @method static Builder|UserLog query()
 * @method static Builder|UserLog whereComment($value)
 * @method static Builder|UserLog whereCreatedAt($value)
 * @method static Builder|UserLog whereId($value)
 * @method static Builder|UserLog whereModelKey($value)
 * @method static Builder|UserLog whereModelType($value)
 * @method static Builder|UserLog whereNewModel($value)
 * @method static Builder|UserLog whereOldModel($value)
 * @method static Builder|UserLog whereUpdatedAt($value)
 * @method static Builder|UserLog whereUserId($value)
 * @mixin IdeHelperUserLog
 */
class UserLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comment',
        'model_type',
        'model_key',
        'old_model',
        'new_model',
    ];

    protected $casts = [
        'old_model' => 'json',
        'new_model' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo('model', 'model_type', 'model_key');
    }

    public function getLogsLine()
    {
        return "Log line: {$this->id}";
    }
}
