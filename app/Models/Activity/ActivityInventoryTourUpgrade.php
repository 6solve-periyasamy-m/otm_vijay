<?php

namespace App\Models\Activity;

use App\Repository\Model\Activity\ActivityInventoryTourUpgradeRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Activity\ActivityInventoryTourUpgrade
 *
 * @property int $id
 * @property int $base_id
 * @property int $upgrade_id
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read ActivityInventoryTour $base
 * @property-read ActivityInventoryTour $upgrade
 * @property-read ActivityInventoryTourUpgradeRepository $repository
 * @method static Builder|ActivityInventoryTourUpgrade newModelQuery()
 * @method static Builder|ActivityInventoryTourUpgrade newQuery()
 * @method static QueryBuilder|ActivityInventoryTourUpgrade onlyTrashed()
 * @method static Builder|ActivityInventoryTourUpgrade query()
 * @method static Builder|ActivityInventoryTourUpgrade whereBaseId($value)
 * @method static Builder|ActivityInventoryTourUpgrade whereCreatedAt($value)
 * @method static Builder|ActivityInventoryTourUpgrade whereDeletedAt($value)
 * @method static Builder|ActivityInventoryTourUpgrade whereDescription($value)
 * @method static Builder|ActivityInventoryTourUpgrade whereId($value)
 * @method static Builder|ActivityInventoryTourUpgrade whereUpdatedAt($value)
 * @method static Builder|ActivityInventoryTourUpgrade whereUpgradeId($value)
 * @method static QueryBuilder|ActivityInventoryTourUpgrade withTrashed()
 * @method static QueryBuilder|ActivityInventoryTourUpgrade withoutTrashed()
 * @mixin Eloquent
 */
class ActivityInventoryTourUpgrade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['upgrade_id', 'description'];
    private ActivityInventoryTourUpgradeRepository $internal_repository;

    public function base(): BelongsTo
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'base_id');
    }

    public function upgrade(): BelongsTo
    {
        return $this->belongsTo(ActivityInventoryTour::class, 'upgrade_id');
    }

    public function getRepositoryAttribute(): ActivityInventoryTourUpgradeRepository
    {
        if (!isset($this->internal_repository)) $this->internal_repository = new ActivityInventoryTourUpgradeRepository($this);
        return $this->internal_repository;
    }
}
