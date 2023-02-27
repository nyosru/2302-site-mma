<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\ClientLog
 *
 * @property int $id
 * @property int|null $app_id
 * @property string|null $bid
 * @property string|null $type
 * @property array|null $log
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read App|null $app
 * @method static Builder|ClientLog newModelQuery()
 * @method static Builder|ClientLog newQuery()
 * @method static Builder|ClientLog query()
 * @method static Builder|ClientLog whereAppId($value)
 * @method static Builder|ClientLog whereBid($value)
 * @method static Builder|ClientLog whereCreatedAt($value)
 * @method static Builder|ClientLog whereId($value)
 * @method static Builder|ClientLog whereLog($value)
 * @method static Builder|ClientLog whereType($value)
 * @method static Builder|ClientLog whereUpdatedAt($value)
 * @mixin IdeHelperClientLog
 */
class ClientLog extends Model
{
    const TYLE_DOWNLOAD = 'd';
    const TYLE_LAUNCH = 'l';
    const TYLE_EVENT = 'e';

    protected $fillable = [
        'app_id',
        'bid',
        'type',
        'client_id',
        'log'
    ];

    protected $casts = [
        'log' => 'json'
    ];


    public function typeName()
    {
        switch ($this->type) {
        case self::TYLE_DOWNLOAD:
            return 'download';
        case self::TYLE_LAUNCH:
            return 'launch';
        case self::TYLE_EVENT:
            return 'event';
        }

        return '-';
    }


    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function getLogsLine()
    {
        return "Client log {$this->id} [{$this->bid}]";
    }
}
