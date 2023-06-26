<?php

namespace App\Imports;

use App\Models\Customer\Organization;
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
            $models[] = Organization::create([
                'name' => $row['name'],
                'contact_email' => $row['contact_email'],
                'contact_number' => $row['contact_number'],
                'internal_notes' => $row['internal_notes'],
                'external_notes' => $row['external_notes'],
            ]);
        }
        return $models;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
