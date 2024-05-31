<?php

namespace App\Imports;

use App\Models\Flight\Airport;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Location\Address;
use App\Models\Location\Country;
use App\Models\Location\LocationType;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class AirportImport implements ToModel
{
    use Importable;

    /**
    * @param array $row
    *
    * @return Airport
     */
    public function model(array $row)
    {
        $country = Country::where('name', 'like', trim($row[6]))->first();
        $address = Address::create([
            'name' => $row[0],
            'parent' => AddressParent::AIRPORT,
            'location_type_id' => LocationType::findOrCreate('Airport')->id,
            'address_line_1' => trim($row[2]),
            'address_line_2' => trim($row[3]),
            'town' => trim($row[4]),
            'region' => trim($row[5]),
            'country_id' => $country->id,
            'postcode' => trim($row[7]),
        ]);
        return new Airport([
            'name' => trim($row[0]),
            'iata_code' => trim($row[1]),
            'address_id' => $address->id,
        ]);
    }
}
