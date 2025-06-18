<?php

namespace App\Models\Transport;

use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Repository\Model\Transport\TransportOccupancyRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

/**
 * App\Models\Transport\TransportOccupancy
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Transport $transport
 * @property-read TransportOccupancyRepository $repository
 * @method static Builder|TransportOccupancy newModelQuery()
 * @method static Builder|TransportOccupancy newQuery()
 * @method static QueryBuilder|TransportOccupancy onlyTrashed()
 * @method static Builder|TransportOccupancy query()
 * @method static Builder|TransportOccupancy whereCreatedAt($value)
 * @method static Builder|TransportOccupancy whereDeletedAt($value)
 * @method static Builder|TransportOccupancy whereId($value)
 * @method static Builder|TransportOccupancy whereName($value)
 * @method static Builder|TransportOccupancy whereUpdatedAt($value)
 * @method static QueryBuilder|TransportOccupancy withTrashed()
 * @method static QueryBuilder|TransportOccupancy withoutTrashed()
 * @mixin Eloquent
 */
class TransportOccupancy extends SimpleModel
{
    use SoftDeletes, HasFactory, HasRepository;
    protected $table = 'transport_occupancy';
    protected $fillable = ['name','maximum_occupancy'];

    public static function getValidationRules(int|null $id = null): array
    {
        if (!empty($id)) {
            return [
                'name' => [
                    'required',
                    Rule::unique('transport_occupancy', 'name')->ignore($id),
                ],
                'maximum_occupancy' => 'required|integer|min:1'
            ];
        }
        return ['name' => 'required|unique:transport_occupancy,name', 'maximum_occupancy' => 'required|integer|min:1'];
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(TransportInventory::class, 'transport_occupancy_id');
    }
}
