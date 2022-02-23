<?php

namespace App\Imports;

use App\Models\AddressParent;
use App\Models\Country;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Address;
use Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Str;

class CustomerImport implements ToModel
{

    public function model(array $row)
    {
        $homeCountry = Country::where('name', 'like', trim($row[13]))->first();
        $billingCountry = Country::where('name', 'like', trim($row[19]))->first();
        $homeAddress = Address::create([
            'name' => trim($row[2]) . " " . trim($row[4]) . " (Imported Home)",
            'address_parent_id' => AddressParent::getParentId('customer'),
            'address_line_1' => trim($row[9]),
            'address_line_2' => trim($row[10] ?? ''),
            'town' => trim($row[11] ?? ''),
            'region' => trim($row[12] ?? ''),
            'country_id' => $homeCountry->id,
            'postcode' => trim($row[14]),
        ]);
        $billingAddress = Address::create([
            'name' => trim($row[2]) . " " . trim($row[4]) . " (Imported Billing)",
            'address_parent_id' => AddressParent::getParentId('customer'),
            'address_line_1' => trim($row[15]),
            'address_line_2' => trim($row[16] ?? ''),
            'town' => trim($row[17] ?? ''),
            'region' => trim($row[18] ?? ''),
            'country_id' => $billingCountry->id,
            'postcode' => trim($row[20]),
        ]);
        $customer = Customer::create([
            'email_address' => trim($row[0]),
            'password' => Hash::make(Str::random(60)),
            'title' => trim($row[1]),
            'first_name' => trim($row[2]),
            'middle_names' => trim($row[3] ?? ''),
            'last_name' => trim($row[4]),
            'date_of_birth' => Carbon::createFromFormat('d/m/Y', trim($row[5])),
            'gender' => trim($row[8]),
            'mobile_number' => trim($row[6]),
            'other_phone_number' => trim($row[7] ?? ''),
            'home_address_id' => $homeAddress->id,
            'billing_address_id' => $billingAddress->id,
            'emergency_contact_name' => trim($row[21] ?? ''),
            'emergency_contact_telephone' => trim($row[22] ?? ''),
            'emergency_contact_relationship' => trim($row[23] ?? ''),
            'passport_first_name' => trim($row[24] ?? ''),
            'passport_middle_name' => trim($row[25] ?? ''),
            'passport_last_name' => trim($row[26] ?? ''),
            'passport_number' => trim($row[26] ?? ''),
            'passport_expiry_date' => isset($row[27]) ? Carbon::createFromFormat('d/m/Y', trim($row[27])) : null,
            'passport_country_of_issue' => trim($row[29] ?? ''),
            'loyalty_number' => trim($row[30] ?? ''),
        ]);
        return $customer;
    }
}
