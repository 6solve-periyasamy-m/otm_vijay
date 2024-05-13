<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Component;

use App\Models\Accommodation\AccommodationInventory;
use App\Models\Supplier\SupplierContract;
use App\Models\Supplier\SupplierContractComponent;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DatetimeColumn;

class AccommodationTable extends ComponentTable
{
    public SupplierContract $contract;

    public function builder()
    {
        return SupplierContractComponent::query()
            ->where('component_type', '=', AccommodationInventory::class)
            ->where('supplier_contract_id', '=', $this->contract->id)
            ->join('accommodation_inventories', 'supplier_contract_components.component_id', '=', 'accommodation_inventories.id')
            ->join('accommodations', 'accommodations.id', '=', 'accommodation_inventories.accommodation_id')
            ->join('room_types', 'room_types.id', '=', 'accommodation_inventories.room_type_id')
            ->join('board_types', 'board_types.id', '=', 'accommodation_inventories.board_type_id');
    }

    public function columns()
    {
        return [
            Column::name('accommodations.name')
                ->label('Accommodation')
                ->sortable()
                ->searchable(),
            Column::raw('CONCAT(room_types.name, " (", room_types.maximum_occupancy, " occupant/s)")')
                ->label('Room Type')
                ->sortable()
                ->searchable(),
            Column::name('board_types.name')
                ->label('Board Type')
                ->sortable()
                ->searchable(),
            DatetimeColumn::name('accommodation_inventories.check_in')
                ->label('Check In')
                ->sortable()
                ->filterable(),
            DatetimeColumn::name('accommodation_inventories.check_out')
                ->label('Check Out')
                ->sortable()
                ->filterable(),
            ...$this->componentColumns(),
        ];
    }

    protected function getCurrencyCode(): string
    {
        return $this->contract->currency->code;
    }
}