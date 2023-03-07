<?php

namespace App\Imports;

use App\Models\Customer\Customer;
use App\Models\Location\Address;
use App\Models\Location\AddressParent;
use App\Models\Location\Country;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function collection(Collection $collection): array
    {
        $models = [];
        foreach ($collection as $row) {
            $homeCountry = Country::where('name', 'like', trim($row['home_country']))->first();
            $billingCountry = Country::where('name', 'like', trim($row['billing_country']))->first();
            $addressName = "(" . trim($row['email']) . ")" . trim($row['first_name']) . " " . trim($row['last_name']);
            $addressParent = AddressParent::getParentId('customer');
            $homeAddress = Address::create([
                'name' => $addressName,
                'address_parent_id' => $addressParent,
                'address_line_1' => trim($row['home_line_1']),
                'address_line_2' => trim($row['home_line_2'] ?? ''),
                'town' => trim($row['home_town'] ?? ''),
                'region' => trim($row['home_region'] ?? ''),
                'country_id' => $homeCountry?->id,
                'postcode' => trim($row['home_postcode']),
            ]);
            $billingAddress = Address::create([
                'name' => $addressName,
                'address_parent_id' => $addressParent,
                'address_line_1' => trim($row['billing_line_1']),
                'address_line_2' => trim($row['billing_line_2'] ?? ''),
                'town' => trim($row['billing_town'] ?? ''),
                'region' => trim($row['billing_region'] ?? ''),
                'country_id' => $billingCountry?->id,
                'postcode' => trim($row['billing_postcode']),
            ]);
            $customer = Customer::create([
                'email_address' => empty(trim($row['email'])) ? null : trim($row['email']),
                'title' => trim($row['title']),
                'first_name' => trim($row['first_name']),
                'middle_names' => trim($row['middle_names'] ?? ''),
                'last_name' => trim($row['last_name']),
                'date_of_birth' => (isset($row['date_of_birth']) ? Carbon::createFromFormat('d/m/Y', trim($row['date_of_birth'])) : null),
                'gender' => trim($row['gender']),
                'mobile_number' => trim($row['mobile_number']),
                'other_phone_number' => trim($row['other_number'] ?? ''),
                'home_address_id' => $homeAddress->id,
                'billing_address_id' => $billingAddress->id,
                'emergency_contact_name' => trim($row['emergency_contact_name'] ?? ''),
                'emergency_contact_telephone' => trim($row['emergency_contact_telephone'] ?? ''),
                'emergency_contact_relationship' => trim($row['emergency_contact_relationship'] ?? ''),
                'passport_first_name' => trim($row['passport_first_name'] ?? ''),
                'passport_middle_name' => trim($row['passport_middle_name'] ?? ''),
                'passport_last_name' => trim($row['passport_last_name'] ?? ''),
                'passport_number' => trim($row['passport_number'] ?? ''),
                'passport_expiry_date' => isset($row['passport_expiry_date']) ? Carbon::createFromFormat('d/m/Y', trim($row[28])) : null,
                'passport_country_of_issue' => trim($row['passport_country_of_issue'] ?? ''),
                'loyalty_number' => trim($row['loyalty_number'] ?? ''),
            ]);
            $models[] = $customer;
        }
        return $models;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'nullable|date|date_format:d/m/Y',
            'passport_expiry_date' => 'nullable|date|date_format:d/m/Y',
            'email' => 'nullable|distinct|unique:customers,email_address'
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'email.unique' => 'A customer with this email address already exists',
            'email.distinct' => 'A row with this email address is already defined',
        ];
    }
}
