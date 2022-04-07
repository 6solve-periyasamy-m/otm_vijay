<?php

namespace App\Imports;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityType;
use App\Models\Address;
use App\Models\AddressParent;
use App\Models\Country;
use App\Models\Currency;
use App\Models\LocationType;
use Maatwebsite\Excel\Concerns\ToModel;

class ActivityImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return Activity
     */
    public function model(array $row)
    {
        $country = Country::where('name', 'like', trim($row[8]))->first();
        $currency = Currency::where('code', '=', trim($row[10]))->first();
        $address = Address::create([
            'name' => $row[0],
            'address_parent_id' => AddressParent::getParentId('Activity'),
            'location_type_id' => LocationType::firstOrCreate(trim($row[3]))->id,
            'address_line_1' => trim($row[4]),
            'address_line_2' => trim($row[5]),
            'town' => trim($row[6]),
            'region' => trim($row[7]),
            'country_id' => $country->id,
            'postcode' => trim($row[9]),
        ]);
        return new Activity([
            'name' => trim($row[0]),
            'description' => trim($row[1]),
            'activity_type_id' => ActivityType::firstOrCreate(trim($row[2]))->id,
            'address_id' => $address->id,
            'currency_id' => $currency->id,
            'notes' => trim($row[11] ?? ''),
        ]);
    }
}
