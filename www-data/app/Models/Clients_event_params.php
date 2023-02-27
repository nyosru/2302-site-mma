<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Clients_event_params
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $client
 * @property mixed $params
 * @method static Builder|Clients_event_params newModelQuery()
 * @method static Builder|Clients_event_params newQuery()
 * @method static Builder|Clients_event_params query()
 * @method static Builder|Clients_event_params whereClient($value)
 * @method static Builder|Clients_event_params whereCreatedAt($value)
 * @method static Builder|Clients_event_params whereId($value)
 * @method static Builder|Clients_event_params whereParams($value)
 * @method static Builder|Clients_event_params whereUpdatedAt($value)
 * @mixin IdeHelperClients_event_params
 */
class Clients_event_params extends Model
{
    protected $guarded = [];
}
