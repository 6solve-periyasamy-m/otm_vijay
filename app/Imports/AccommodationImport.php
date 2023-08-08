<?php

namespace App\Imports;

use App\Models\Accommodation\Accommodation;
use App\Models\Location\Address;
use App\Models\Location\AddressParent;
use App\Models\Location\Country;
use App\Models\Location\Currency;
use App\Models\Location\LocationType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AccommodationImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * @param Collection $collection
     * @return array
     */
    public function collection(Collection $collection)
    {
        $data = [];
        foreach ($collection as $row) {
            $country = Country::where('name', 'like', trim($row['country']))->first();
            $currency = Currency::where('code', '=', trim($row['currency']))->first();
            $address = Address::create([
                'name' => $row['name'],
                'address_parent_id' => AddressParent::getParentId('Accommodation'),
                'location_type_id' => LocationType::findOrCreate('Hotel')->id,
                'address_line_1' => trim($row['address_line_1']),
                'address_line_2' => trim($row['address_line_2']),
                'town' => trim($row['town']),
                'region' => trim($row['region']),
                'country_id' => $country?->id,
                'postcode' => trim($row['postcode']),
            ]);
            $data[] = Accommodation::create([
                'name' => trim($row['name']),
                'description' => trim($row['description']),
                'audit_date' => Carbon::createFromFormat('d/m/Y', trim($row['audit_date'])),
                'address_id' => $address?->id,
                'currency_id' => $currency?->id,
                'internal_notes' => trim($row['notes'] ?? ''),
            ]);
        }
        return $data;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'audit_date' => 'nullable|date|date_format:d/m/Y'
        ];
    }
}
