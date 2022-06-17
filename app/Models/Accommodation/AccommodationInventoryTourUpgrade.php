<?php

namespace App\Models\Accommodation;

use App\Repository\Model\Accommodation\AccommodationInventoryTourUpgradeRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Accommodation\AccommodationInventoryTourUpgrade
 *
 * @property int $id
 * @property int $base_id
 * @property int $upgrade_id
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read AccommodationInventoryTour $base
 * @property-read AccommodationInventoryTour $upgrade
 * @property-read AccommodationInventoryTourUpgradeRepository $repository
 * @method static Builder|AccommodationInventoryTourUpgrade newModelQuery()
 * @method static Builder|AccommodationInventoryTourUpgrade newQuery()
 * @method static QueryBuilder|AccommodationInventoryTourUpgrade onlyTrashed()
 * @method static Builder|AccommodationInventoryTourUpgrade query()
 * @method static Builder|AccommodationInventoryTourUpgrade whereBaseId($value)
 * @method static Builder|AccommodationInventoryTourUpgrade whereCreatedAt($value)
 * @method static Builder|AccommodationInventoryTourUpgrade whereDeletedAt($value)
 * @method static Builder|AccommodationInventoryTourUpgrade whereDescription($value)
 * @method static Builder|AccommodationInventoryTourUpgrade whereId($value)
 * @method static Builder|AccommodationInventoryTourUpgrade whereUpdatedAt($value)
 * @method static Builder|AccommodationInventoryTourUpgrade whereUpgradeId($value)
 * @method static QueryBuilder|AccommodationInventoryTourUpgrade withTrashed()
 * @method static QueryBuilder|AccommodationInventoryTourUpgrade withoutTrashed()
 * @mixin Eloquent
 */
class AccommodationInventoryTourUpgrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['upgrade_id', 'description'];
    private AccommodationInventoryTourUpgradeRepository $internal_repository;

    public function base(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventoryTour::class, 'base_id');
    }

    public function upgrade(): BelongsTo
    {
        return $this->belongsTo(AccommodationInventoryTour::class, 'upgrade_id');
    }

    public function getRepositoryAttribute(): AccommodationInventoryTourUpgradeRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new AccommodationInventoryTourUpgradeRepository($this);
        return $this->internal_repository;
    }
}
