<?php

namespace App\Exports\Identifier;

use App\Models\Accommodation\AccommodationInventory;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class AccommodationInventoryExport implements FromQuery, Responsable, WithMapping, WithHeadings
{
    use Exportable;

    private string $fileName = 'accommodation-inventory-ids.csv';
    private string $writerType = Excel::CSV;

    private array $headers = [ 'Content-Type' => 'text/csv', ];

    public function query()
    {

        return AccommodationInventory::query();
    }

    /**
     * @param AccommodationInventory $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->accommodation->name,
            f_datetime($row->check_in),
            f_datetime($row->check_out),
            $row->roomType->name,
            $row->roomType->maximum_occupancy,
            $row->boardType->name,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Hotel',
            'Check In',
            'Check Out',
            'Room Type',
            'Size',
            'Board Type'
        ];
    }
}