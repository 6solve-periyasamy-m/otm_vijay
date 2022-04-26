<?php

namespace App\Imports;

use App\Models\Accommodation\Accommodation;
use App\Models\Location\Address;
use App\Models\Location\AddressParent;
use App\Models\Location\Country;
use App\Models\Location\Currency;
use App\Models\Location\LocationType;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class AccommodationImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $country = Country::where('name', 'like', trim($row[7]))->first();
        $currency = Currency::where('code', '=', trim($row[9]))->first();
        $address = Address::create([
            'name' => $row[0],
            'address_parent_id' => AddressParent::getParentId('Accommodation'),
            'location_type_id' => LocationType::findOrCreate('Hotel')->id,
            'address_line_1' => trim($row[3]),
            'address_line_2' => trim($row[4]),
            'town' => trim($row[5]),
            'region' => trim($row[6]),
            'country_id' => $country->id,
            'postcode' => trim($row[8]),
        ]);
        return new Accommodation([
            'name' => trim($row[0]),
            'description' => trim($row[1]),
            'audit_date' => Carbon::createFromFormat('d/m/Y', trim($row[2])),
            'address_id' => $address->id,
            'currency_id' => $currency->id,
            'notes' => trim($row[10] ?? ''),
        ]);
    }
}
