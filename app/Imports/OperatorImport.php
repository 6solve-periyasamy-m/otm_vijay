<?php

namespace App\Imports;

use App\Models\Transport\Operator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OperatorImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function collection(Collection $collection)
    {
        $data = [];
        foreach ($collection as $row) {
            $data[] = Operator::create([
                'name' => $row['name'] ?? '',
            ]);
        }
        return $data;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|distinct|unique:operators,name',
        ];
    }
}
