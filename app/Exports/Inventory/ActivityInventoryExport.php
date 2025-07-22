<?php

namespace App\Exports\Inventory;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Excel;

class ActivityInventoryExport implements FromView, WithHeadingRow
{
    use Exportable;

    /** @var Collection<ActivityInventory>|ActivityInventory[] */
    private Collection|array $inventories;
    private string $writerType = Excel::CSV;
    private array $headers = [ 'Content-Type' => 'text/csv', ];

    public function __construct(Activity $activity)
    {
        $this->inventories = $activity->activityInventory()->with('ticketType')->get();
        $this->fileName = sanitize($activity->name) . '-inventory.csv';
    }

    public function view(): View
    {
        return view('partials.export.inventory.activity', ['inventories' => $this->inventories]);
    }
}