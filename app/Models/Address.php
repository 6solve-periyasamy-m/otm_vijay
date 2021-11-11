<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['address_line_1','address_line_2','town','region','country','postcode',];

    public function locationType()
    {
        return $this->belongsTo(LocationType::class);
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function __toString()
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
