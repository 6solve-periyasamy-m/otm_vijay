<?php

namespace App\Imports;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\TicketType;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class ActivityInventoryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return ActivityInventory
    */
    public function model(array $row)
    {
        $activity = Activity::where('name', 'like', trim($row[0]))->first();
        if ($activity == null) return null;
        return new ActivityInventory([
            'activity_id' => $activity->id,
            'ticket_type_id' => TicketType::findOrCreate($row[1])->id,
            'starts_at' => Carbon::createFromFormat('d/m/Y H:i', trim($row[2])),
            'ends_at' => Carbon::createFromFormat('d/m/Y H:i', trim($row[3])),
            'fit_selectable' => trim($row[4]) == 'YES',
            'stock' => trim($row[5]),
            'purchase_price' => trim($row[6]),
            'sales_price' => trim($row[7]) != '' ? trim($row[7]) : trim($row[6]),
            'notes' => trim($row[8]),
        ]);
    }
}
