<?php

namespace App\Imports;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\TicketType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ActivityInventoryImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * @param Collection $collection
     * @return array
     */
    public function collection(Collection $collection)
    {
        $data = [];
        foreach ($collection as $row) {
            $activity = Activity::where('name', 'like', trim($row['activity']))->first();
            if ($activity == null) return null;
            $data[] = ActivityInventory::create([
                'activity_id' => $activity->id,
                'ticket_type_id' => TicketType::findOrCreate($row['ticket_type'])->id,
                'starts_at' => Carbon::createFromFormat('d/m/Y H:i', trim($row['starts_at'])),
                'ends_at' => Carbon::createFromFormat('d/m/Y H:i', trim($row['ends_at'])),
                'fit_selectable' => trim($row['fit_selectable']) == 'YES',
                'stock' => trim($row['stock']),
                'purchase_price' => trim($row['purchase_price']),
                'sales_price' => trim($row['sales_price']) != '' ? trim($row['sales_price']) : trim($row['purchase_price']),
                'notes' => trim($row['notes']),
            ]);
        }
        return $data;
    }

    public function rules(): array
    {
        return [
            'activity' => 'required|exists:activities,name',
            'ticket_type' => 'required',
            'starts_at' => 'required|date|date_format:d/m/Y H:i',
            'ends_at' => 'required|date|date_format:d/m/Y H:i',
            'fit_selectable' => ['nullable', Rule::in(['YES', 'NO', null])],
            'stock' => 'required|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'nullable|numeric',
        ];
    }
}
