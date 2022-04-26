<?php

namespace App\Imports;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomType;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class AccommodationInventoryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $accommodation = Accommodation::where('name', 'like', trim($row[0]))->first();
        if ($accommodation == null) return null;
        return new AccommodationInventory([
            'accommodation_id' => $accommodation->id,
            'room_type_id' => RoomType::findOrCreate(trim($row[1]), trim($row[2]))->id,
            'board_type_id' => BoardType::findOrCreate(trim($row[3]))->id,
            'check_in' => Carbon::createFromFormat('d/m/Y H:i', trim($row[4])),
            'check_in_time_confirmed' => true,
            'check_out' => Carbon::createFromFormat('d/m/Y H:i', trim($row[5])),
            'check_out_time_confirmed' => true,
            'fit_selectable' => trim($row[6]) == 'YES',
            'stock' => trim($row[7]),
            'purchase_price' => trim($row[8]),
            'sales_price' => trim($row[9]) != '' ? trim($row[9]) : trim($row[8]),
            'notes' => trim($row[10]),
        ]);
    }
}
