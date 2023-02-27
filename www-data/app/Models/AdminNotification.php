<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\AdminNotification
 *
 * @property int $id
 * @property int|null $app_id
 * @property string $text
 * @property int $readed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read App|null $app
 * @method static Builder|AdminNotification newModelQuery()
 * @method static Builder|AdminNotification newQuery()
 * @method static Builder|AdminNotification query()
 * @method static Builder|AdminNotification whereAppId($value)
 * @method static Builder|AdminNotification whereCreatedAt($value)
 * @method static Builder|AdminNotification whereId($value)
 * @method static Builder|AdminNotification whereReaded($value)
 * @method static Builder|AdminNotification whereText($value)
 * @method static Builder|AdminNotification whereUpdatedAt($value)
 * @mixin IdeHelperAdminNotification
 */
class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_id',
        'text',
        'readed'
    ];


    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function getLogsLine()
    {
        return "Notification [{$this->id}]";
    }
}
