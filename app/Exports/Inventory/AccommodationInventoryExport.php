<?php

namespace App\Exports\Inventory;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Excel;

class AccommodationInventoryExport implements FromView, WithHeadingRow
{
    use Exportable;

    /** @var Collection<AccommodationInventory>|AccommodationInventory[] */
    private Collection|array $inventories;
    private string $writerType = Excel::CSV;
    private array $headers = [ 'Content-Type' => 'text/csv', ];

    public function __construct(Accommodation $accommodation)
    {
        $this->inventories = $accommodation->inventory()->with('roomType', 'boardType', 'category')->get();
        $this->fileName = sanitize($accommodation->name) . '-inventory.csv';
    }

    public function view(): View
    {
        return view('partials.export.inventory.accommodation', ['inventories' => $this->inventories]);
    }
}