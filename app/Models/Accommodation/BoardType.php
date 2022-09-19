<?php

namespace App\Models\Accommodation;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Accommodation\BoardTypeRepository;
use App\Repository\Model\Accommodation\RoomTypeRepository;
use Database\Factories\Accommodation\BoardTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\BoardType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|AccommodationInventory[] $inventories
 * @property-read BoardTypeRepository $repository
 * @method static BoardTypeFactory factory(...$parameters)
 * @method static Builder|BoardType newModelQuery()
 * @method static Builder|BoardType newQuery()
 * @method static QueryBuilder|BoardType onlyTrashed()
 * @method static Builder|BoardType query()
 * @method static Builder|BoardType whereCreatedAt($value)
 * @method static Builder|BoardType whereDeletedAt($value)
 * @method static Builder|BoardType whereId($value)
 * @method static Builder|BoardType whereName($value)
 * @method static Builder|BoardType whereUpdatedAt($value)
 * @method static QueryBuilder|BoardType withTrashed()
 * @method static QueryBuilder|BoardType withoutTrashed()
 * @mixin Eloquent
 */
class BoardType extends SimpleModel
{
    use SoftDeletes, HasFactory, HasRepository;

    protected $fillable = ['name',];
    protected string $repositoryClass = BoardTypeRepository::class;

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:board_types,name',];
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(AccommodationInventory::class, 'board_type_id');
    }
}
