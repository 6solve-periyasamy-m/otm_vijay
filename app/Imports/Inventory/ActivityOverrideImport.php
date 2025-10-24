<?php

namespace App\Imports\Inventory;

use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventory;
use App\Models\Activity\TicketType;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ActivityOverrideImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    private Activity $component;

    public function __construct(Activity $component)
    {
        $this->component = $component->load('activityInventory', 'activityInventory.ticketType');
    }
    public function collection(Collection $collection)
    {
        $data = [];
        foreach ($collection as $row) {
            $id = $row['id'] ?? null;
            $inventory = $this->component->activityInventory()->where('id', $id)->first() ??
                ActivityInventory::make(['activity_id' => $this->component->id,]);
            if (strtolower($inventory->ticketType?->name) === strtolower(trim($row['ticket_type']))) {
                $ticketType = $inventory->ticketType?->id;
            } else {
                $ticketType = TicketType::findOrCreate(trim($row['ticket_type']))?->id;
            }
            $inventory->update([
                'description' => trim($row['description']),
                'starts_at' => get_date(trim($row['start'])),
                'ends_at' => get_date(trim($row['end'])),
                'fit_selectable' => (((trim($row['fit'] ?? "") === 'TRUE') || (trim($row['fit'] ?? "")) === 'YES')),
                'ticket_type_id' => $ticketType,
                'stock' => empty(trim($row['stock'] ?? "")) ? 0 : trim($row['stock']),
                'purchase_price' => empty(trim($row['purchase'] ?? "")) ? 0 : trim($row['purchase']),
                'sales_price' => empty(trim($row['sales'] ?? "")) ? 0 : trim($row['sales']),
                'internal_notes' => trim($row['internal'] ?? ""),
                'external_notes' => trim($row['external'] ?? ""),
            ]);
            $data[] = $this->component->activityInventory()->save($inventory);
        }
        return $data;
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|int|exists:activity_inventories,id',
            'description' => 'nullable|string',
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'fit' => ['nullable', Rule::in(['YES', 'TRUE', 'FALSE', 'NO'])],
            'ticket_type' => 'required|string|exists:ticket_types,name',
            'stock' => 'nullable|int',
            'purchase' => 'nullable|numeric',
            'sales' => 'nullable|numeric',
            'internal' => 'nullable',
            'external' => 'nullable',
        ];
    }
}