<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Location\Address
 *
 * @property int $id
 * @property string $name
 * @property int $address_parent_id
 * @property int|null $location_type_id
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $address_line_3
 * @property string|null $town
 * @property string|null $region
 * @property int|null $country_id
 * @property string|null $postcode
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AddressParent $addressParent
 * @property-read Country|null $country
 * @property-read LocationType|null $locationType
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static QueryBuilder|Address onlyTrashed()
 * @method static Builder|Address query()
 * @method static Builder|Address whereAddressLine1($value)
 * @method static Builder|Address whereAddressLine2($value)
 * @method static Builder|Address whereAddressLine3($value)
 * @method static Builder|Address whereAddressParentId($value)
 * @method static Builder|Address whereCountryId($value)
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereDeletedAt($value)
 * @method static Builder|Address whereId($value)
 * @method static Builder|Address whereLocationTypeId($value)
 * @method static Builder|Address whereName($value)
 * @method static Builder|Address wherePostcode($value)
 * @method static Builder|Address whereRegion($value)
 * @method static Builder|Address whereTown($value)
 * @method static Builder|Address whereUpdatedAt($value)
 * @method static QueryBuilder|Address withTrashed()
 * @method static QueryBuilder|Address withoutTrashed()
 * @mixin \Eloquent
 */
class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'address_parent_id', 'location_type_id', 'address_line_1', 'address_line_2', 'address_line_3', 'town', 'region', 'country_id', 'postcode',];

    public static function getValidationRules($prefix = ''): array
    {
        return [
            $prefix . 'location_type_id' => 'required|exists:location_types,id',
            $prefix . 'address_line_1' => 'required',
            $prefix . 'country_id' => 'required|exists:countries,id',
            $prefix . 'postcode' => 'required',
        ];
    }

    public function locationType(): BelongsTo
    {
        return $this->belongsTo(LocationType::class, 'location_type_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function addressParent(): BelongsTo
    {
        return $this->belongsTo(AddressParent::class, 'address_parent_id');
    }

    public function __toString(): string
    {
        $addrString = $this->address_line_1;
        if (isset($this->address_line_2)) $addrString .= ", " . $this->address_line_2;
        if (isset($this->address_line_3)) $addrString .= ", " . $this->address_line_3;
        if (isset($this->town)) $addrString .= ", " . $this->town;
        if (isset($this->region)) $addrString .= ", " . $this->region;
        if (isset($this->country)) $addrString .= ", " . $this->country->name;
        if (isset($this->postcode)) $addrString .= ", " . $this->postcode;
        return $addrString;
    }
}
