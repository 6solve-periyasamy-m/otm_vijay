<?php

namespace App\Models\Flight;

use App\Repository\Model\Flight\FlightInventoryTourUpgradeRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Flight\FlightInventoryTourUpgrade
 *
 * @property int $id
 * @property int $base_id
 * @property int $upgrade_id
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read FlightInventoryTour $base
 * @property-read FlightInventoryTour $upgrade
 * @property-read FlightInventoryTourUpgradeRepository $repository
 * @method static Builder|FlightInventoryTourUpgrade newModelQuery()
 * @method static Builder|FlightInventoryTourUpgrade newQuery()
 * @method static QueryBuilder|FlightInventoryTourUpgrade onlyTrashed()
 * @method static Builder|FlightInventoryTourUpgrade query()
 * @method static Builder|FlightInventoryTourUpgrade whereBaseId($value)
 * @method static Builder|FlightInventoryTourUpgrade whereCreatedAt($value)
 * @method static Builder|FlightInventoryTourUpgrade whereDeletedAt($value)
 * @method static Builder|FlightInventoryTourUpgrade whereDescription($value)
 * @method static Builder|FlightInventoryTourUpgrade whereId($value)
 * @method static Builder|FlightInventoryTourUpgrade whereUpdatedAt($value)
 * @method static Builder|FlightInventoryTourUpgrade whereUpgradeId($value)
 * @method static QueryBuilder|FlightInventoryTourUpgrade withTrashed()
 * @method static QueryBuilder|FlightInventoryTourUpgrade withoutTrashed()
 * @mixin Eloquent
 */
class FlightInventoryTourUpgrade extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    private FlightInventoryTourUpgradeRepository $internal_repository;

    public function base(): BelongsTo
    {
        return $this->belongsTo(FlightInventoryTour::class, 'base_id');
    }

    public function upgrade(): BelongsTo
    {
        return $this->belongsTo(FlightInventoryTour::class, 'upgrade_id');
    }

    public function getRepositoryAttribute(): FlightInventoryTourUpgradeRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new FlightInventoryTourUpgradeRepository($this);
        return $this->internal_repository;
    }
}
