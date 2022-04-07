<?php

namespace App\Models;

use App\Models\Helper\SimpleModel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\TourCategory
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Tour[] $tours
 * @property-read int|null $tours_count
 * @method static Builder|TourCategory newModelQuery()
 * @method static Builder|TourCategory newQuery()
 * @method static QueryBuilder|TourCategory onlyTrashed()
 * @method static Builder|TourCategory query()
 * @method static Builder|TourCategory whereCreatedAt($value)
 * @method static Builder|TourCategory whereDeletedAt($value)
 * @method static Builder|TourCategory whereId($value)
 * @method static Builder|TourCategory whereName($value)
 * @method static Builder|TourCategory whereUpdatedAt($value)
 * @method static QueryBuilder|TourCategory withTrashed()
 * @method static QueryBuilder|TourCategory withoutTrashed()
 * @mixin Eloquent
 */
class TourCategory extends SimpleModel
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required'];
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'tour_category_id');
    }
}
