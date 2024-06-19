<?php

namespace App\Imports;

use App\Models\Customer\Organization;
use App\Models\Location\Address;
use App\Models\Location\Country;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OrganizationImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function collection(Collection $collection): array
    {
        $models = [];
        foreach ($collection as $row) {
            $delivery = Address::create([
                'address_line_1' => trim($row['delivery_line_1']),
                'address_line_2' => trim($row['delivery_line_2']),
                'town' => trim($row['delivery_town']),
                'region' => trim($row['delivery_region']),
                'country_id' => Country::where('name', 'like', trim($row['delivery_country']))->first()?->id,
                'postcode' => trim($row['delivery_postcode']),
            ]);
            $billing = Address::create([
                'address_line_1' => trim($row['billing_line_1']),
                'address_line_2' => trim($row['billing_line_2']),
                'town' => trim($row['billing_town']),
                'region' => trim($row['billing_region']),
                'country_id' => Country::where('name', 'like', trim($row['billing_country']))->first()?->id,
                'postcode' => trim($row['billing_postcode']),
            ]);
            $models[] = Organization::create([
                'name' => trim($row['name']),
                'contact_email' => trim($row['contact_email']),
                'contact_number' => trim($row['contact_number']),
                'commission' => trim($row['commission']),
                'delivery_address_id' => $delivery->id,
                'billing_address_id' => $billing->id,
                'internal_notes' => trim($row['internal_notes']),
                'external_notes' => trim($row['external_notes']),
            ]);
        }
        return $models;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'commission' => 'nullable|numeric|min:0',
        ];
    }
}
