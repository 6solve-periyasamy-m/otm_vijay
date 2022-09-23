<?php

namespace App\Models;

use App\Models\Flight\FlightInventory;
use App\Models\Helper\SimpleModel;
use App\Models\Traits\HasRepository;
use App\Models\Transport\TransportInventory;
use App\Repository\Model\TravelClassRepository;
use Database\Factories\TravelClassFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;


/**
 * App\Models\TravelClass
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read TravelClassRepository $repository
 * @property-read Collection|TransportInventory[] $transportInventories
 * @property-read Collection|FlightInventory[] $flightInventories
 * @method static TravelClassFactory factory(...$parameters)
 * @method static Builder|TravelClass newModelQuery()
 * @method static Builder|TravelClass newQuery()
 * @method static QueryBuilder|TravelClass onlyTrashed()
 * @method static Builder|TravelClass query()
 * @method static Builder|TravelClass whereCreatedAt($value)
 * @method static Builder|TravelClass whereDeletedAt($value)
 * @method static Builder|TravelClass whereId($value)
 * @method static Builder|TravelClass whereName($value)
 * @method static Builder|TravelClass whereUpdatedAt($value)
 * @method static QueryBuilder|TravelClass withTrashed()
 * @method static QueryBuilder|TravelClass withoutTrashed()
 * @mixin Eloquent
 */
class TravelClass extends SimpleModel
{
    use SoftDeletes, HasFactory, HasRepository;

    protected $fillable = ['name',];

    public static function getValidationRules(): array
    {
        return ['name' => 'required|unique:travel_classes,name',];
    }
    
    public function transportInventories(): HasMany
    {
        return $this->hasMany(TransportInventory::class, 'travel_class_id');
    }
    
    public function flightInventories(): HasMany
    {
        return $this->hasMany(FlightInventory::class, 'travel_class_id');
    }
}
