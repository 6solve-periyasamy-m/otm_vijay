<?php

namespace App\Models\System;

use App\Models\Helper\ModelEventType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * \App\Models\System\ModelEvent
 *
 * @property int $id
 * @property string|null $actor_type
 * @property int|null $actor_id
 * @property string $target_type
 * @property int $target_id
 * @property string $action
 * @property string $occurred
 * @property mixed|null $from
 * @property mixed|null $to
 * @property string $ip
 * @property string|null $location
 * @property string|null $agent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent|null $actor
 * @property-read Model|Eloquent $target
 * @method static Builder|ModelEvent newModelQuery()
 * @method static Builder|ModelEvent newQuery()
 * @method static Builder|ModelEvent query()
 * @method static Builder|ModelEvent whereAction($value)
 * @method static Builder|ModelEvent whereActorId($value)
 * @method static Builder|ModelEvent whereActorType($value)
 * @method static Builder|ModelEvent whereAgent($value)
 * @method static Builder|ModelEvent whereCreatedAt($value)
 * @method static Builder|ModelEvent whereFrom($value)
 * @method static Builder|ModelEvent whereId($value)
 * @method static Builder|ModelEvent whereIp($value)
 * @method static Builder|ModelEvent whereLocation($value)
 * @method static Builder|ModelEvent whereOccurred($value)
 * @method static Builder|ModelEvent whereTargetId($value)
 * @method static Builder|ModelEvent whereTargetType($value)
 * @method static Builder|ModelEvent whereTo($value)
 * @method static Builder|ModelEvent whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ModelEvent extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = ['action' => ModelEventType::class,];

    public function actor(): MorphTo
    {
        return $this->morphTo('actor', 'actor_type', 'actor_id');
    }

    public function target(): MorphTo
    {
        return $this->morphTo('target', 'target_type', 'target_id');
    }
}
