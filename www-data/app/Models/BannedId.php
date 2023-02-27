<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\BannedId
 *
 * @property int $id
 * @property int $app_id
 * @property string $client_bid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BannedId newModelQuery()
 * @method static Builder|BannedId newQuery()
 * @method static Builder|BannedId query()
 * @method static Builder|BannedId whereAppId($value)
 * @method static Builder|BannedId whereClientBid($value)
 * @method static Builder|BannedId whereCreatedAt($value)
 * @method static Builder|BannedId whereId($value)
 * @method static Builder|BannedId whereUpdatedAt($value)
 * @mixin IdeHelperBannedId
 */
class BannedId extends Model
{
    protected $fillable = ['app_id', 'client_bid'];

    //

    public function getLogsLine()
    {
        return "Banned ID {$this->client_bid} [{$this->app_id}]";
    }
}
