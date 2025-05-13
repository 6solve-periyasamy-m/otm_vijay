<?php

namespace App\Imports;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomCategory;
use App\Models\Accommodation\RoomType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AccommodationInventoryImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * @param Collection $collection
     * @return array|null
     */
    public function collection(Collection $collection): ?array
    {
        $data = [];
        foreach ($collection as $row) {
            $accommodation = Accommodation::where('name', 'like', trim($row['accommodation'] ?? ''))->first();
            $parent = AccommodationInventory::find($row['parent_id']);
            $category = empty($row['category'] ?? '') ? null : RoomCategory::findOrCreate($row['category'] ?? '');
            if ($accommodation == null) return null;
            $data[] = AccommodationInventory::create([
                'accommodation_id' => $accommodation->id,
                'stock_parent_id' => $parent?->id,
                'room_type_id' => RoomType::findOrCreate(trim($row['room_type'] ?? ''), trim($row['size'] ?? ''))->id,
                'board_type_id' => BoardType::findOrCreate(trim($row['board_type'] ?? ''))->id,
                'room_category_id' => $category?->id,
                'check_in' => Carbon::createFromFormat('d-m-Y H:i', trim($row['check_in'] ?? '')),
                'check_in_time_confirmed' => true,
                'check_out' => Carbon::createFromFormat('d-m-Y H:i', trim($row['check_out'] ?? '')),
                'check_out_time_confirmed' => true,
                'fit_selectable' => trim($row['fit_selectable'] ?? '') == 'YES',
                'stock' => trim($row['stock'] ?? '') != '' ? trim($row['stock'] ?? '') : 0,
                'purchase_price' => trim($row['purchase_price'] ?? ''),
                'sales_price' => trim($row['sales_price'] ?? '') != '' ? trim($row['sales_price'] ?? '') : trim($row['purchase_price'] ?? ''),
                'internal_notes' => trim($row['notes']),
            ]);
        }
        return $data;
    }

    public function rules(): array
    {
        return [
            'accommodation' => 'required|exists:accommodations,name',
            'room_type' => 'required',
            'parent_id' => 'nullable|exists:accommodation_inventories,id',
            'size' => 'required|integer|gte:1',
            'check_in' => 'required|date|date_format:"d-m-Y H:i"',
            'check_out' => 'required|date|date_format:"d-m-Y H:i"|after:check_in',
            'fit_selectable' => ['nullable', Rule::in(['YES', 'NO', null])],
            'stock' => 'nullable|integer',
            'purchase_price' => 'required|numeric',
            'sales_price' => 'nullable|numeric',
        ];
    }
}
