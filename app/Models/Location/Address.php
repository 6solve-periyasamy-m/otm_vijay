<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
