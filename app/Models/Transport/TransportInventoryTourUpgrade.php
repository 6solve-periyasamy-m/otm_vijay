<?php

namespace App\Models\Transport;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Transport\TransportInventoryTourUpgrade
 *
 * @property int $id
 * @property int $base_id
 * @property int $upgrade_id
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read TransportInventoryTour $base
 * @property-read TransportInventoryTour $upgrade
 * @method static Builder|TransportInventoryTourUpgrade newModelQuery()
 * @method static Builder|TransportInventoryTourUpgrade newQuery()
 * @method static QueryBuilder|TransportInventoryTourUpgrade onlyTrashed()
 * @method static Builder|TransportInventoryTourUpgrade query()
 * @method static Builder|TransportInventoryTourUpgrade whereBaseId($value)
 * @method static Builder|TransportInventoryTourUpgrade whereCreatedAt($value)
 * @method static Builder|TransportInventoryTourUpgrade whereDeletedAt($value)
 * @method static Builder|TransportInventoryTourUpgrade whereDescription($value)
 * @method static Builder|TransportInventoryTourUpgrade whereId($value)
 * @method static Builder|TransportInventoryTourUpgrade whereUpdatedAt($value)
 * @method static Builder|TransportInventoryTourUpgrade whereUpgradeId($value)
 * @method static QueryBuilder|TransportInventoryTourUpgrade withTrashed()
 * @method static QueryBuilder|TransportInventoryTourUpgrade withoutTrashed()
 * @mixin Eloquent
 */
class TransportInventoryTourUpgrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['upgrade_id','description'];

    public function base(): BelongsTo
    {
        return $this->belongsTo(TransportInventoryTour::class, 'base_id');
    }

    public function upgrade(): BelongsTo
    {
        return $this->belongsTo(TransportInventoryTour::class, 'upgrade_id');
    }
}
