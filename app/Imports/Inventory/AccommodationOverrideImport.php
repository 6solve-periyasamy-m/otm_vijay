<?php

namespace App\Imports\Inventory;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventory;
use App\Models\Accommodation\BoardType;
use App\Models\Accommodation\RoomCategory;
use App\Models\Accommodation\RoomType;
use App\Models\Accommodation\TicketType;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AccommodationOverrideImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    private Accommodation $component;

    public function __construct(Accommodation $component)
    {
        $this->component = $component->load('inventory', 'inventory.roomType', 'inventory.boardType', 'inventory.category');
    }

    public function collection(Collection $collection): array
    {
        $data = [];
        foreach ($collection as $row) {
            /** @var AccommodationInventory $inventory */
            $inventory = $this->component->inventory()->where('id', $row['id'])->first() ??
                AccommodationInventory::make(['accommodation_id' => $this->component->id,]);
            
            if (strtolower($inventory->roomType?->name) === strtolower(trim($row['room_type']))) {
                $room = $inventory->roomType?->id;
            } else {
                $room = RoomType::findOrCreate(trim($row['room_type']), trim($row['room_type']))?->id;
            }
            if (strtolower($inventory->boardType?->name) === strtolower(trim($row['board_type']))) {
                $board = $inventory->boardType?->id;
            } else {
                $board = BoardType::findOrCreate(trim($row['board_type']))?->id;
            }
            if (strtolower($inventory->boardType?->name) === strtolower(trim($row['category']))) {
                $category = $inventory->category?->id;
            } else {
                $category = RoomCategory::findOrCreate(trim($row['category']))?->id;
            }
            
            $inventory->update([
                'check_in' => get_date(trim($row['start'])),
                'check_out' => get_date(trim($row['end'])),
                'check_in_time_confirmed' => (((trim($row['start_confirmed'] ?? "") === 'TRUE') || (trim($row['start_confirmed'] ?? "")) === 'YES')),
                'check_out_time_confirmed' => (((trim($row['end_confirmed'] ?? "") === 'TRUE') || (trim($row['end_confirmed'] ?? "")) === 'YES')),
                'fit_selectable' => (((trim($row['fit'] ?? "") === 'TRUE') || (trim($row['fit'] ?? "")) === 'YES')),
                'room_type_id' => $room,
                'board_type_id' => $board,
                'room_category_id' => $category,
                'stock' => empty(trim($row['stock'] ?? "")) ? 0 : trim($row['stock']),
                'purchase_price' => empty(trim($row['purchase'] ?? "")) ? 0 : trim($row['purchase']),
                'sales_price' => empty(trim($row['sales'] ?? "")) ? 0 : trim($row['sales']),
                'internal_notes' => trim($row['internal'] ?? ""),
                'external_notes' => trim($row['external'] ?? ""),
            ]);
            $data[] = $this->component->inventory()->save($inventory);
        }
        return $data;
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|int|exists:accommodation_inventories,id',
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'start_confirmed' => ['nullable', Rule::in(['YES', 'TRUE', 'FALSE', 'NO'])],
            'end_confirmed' => ['nullable', Rule::in(['YES', 'TRUE', 'FALSE', 'NO'])],
            'fit' => ['nullable', Rule::in(['YES', 'TRUE', 'FALSE', 'NO'])],
            'room_type' => 'required|string|exists:room_types,name',
            'board_type' => 'required|string|exists:board_types,name',
            'category' => 'required|string|exists:room_categories,name',
            'stock' => 'nullable|int',
            'purchase' => 'nullable|numeric',
            'sales' => 'nullable|numeric',
            'internal' => 'nullable',
            'external' => 'nullable',
        ];
    }
}