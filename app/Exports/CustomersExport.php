<?php

namespace App\Exports;

use App\Models\Customer\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromQuery, WithHeadings, WithMapping
{
    
    public function query()
    {        
        return Customer::query()
            ->leftJoin('organizations', 'organizations.id', '=', 'customers.organization_id')
            ->select([
                'customers.first_name',
                'customers.last_name',
                'customers.email_address',
                'customers.mobile_number',
                'customers.date_of_birth',
                'customers.password',
                'organizations.name as organization_name',
            ]);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone Number',
            'Date of Birth',
            'Status',
            'Organization',
        ];
    }

    public function map($customer): array
    {
        $firstName = $customer->first_name ?? '';
        $lastName  = $customer->last_name ?? '';
        $fullName  = trim($firstName . ' ' . $lastName);
        $isRegistered = !empty($customer->email_address) && !empty($customer->password);

        return [
            $fullName !== '' ? $fullName : '',
            $customer->email_address ?? '',
            $customer->mobile_number ?? '',
            optional($customer->date_of_birth)->format('Y-m-d'),
            $isRegistered ? 'Active' : 'Inactive',
            $customer->organization_name ?? '',
        ];
    }
}