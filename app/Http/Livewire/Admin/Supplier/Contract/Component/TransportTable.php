<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Component;

use App\Models\Supplier\SupplierContract;
use App\Models\Supplier\SupplierContractComponent;
use App\Models\Transport\TransportInventory;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;

class TransportTable extends ComponentTable
{
    public SupplierContract $contract;

    public function builder()
    {
        return SupplierContractComponent::query()
            ->where('component_type', '=', TransportInventory::class)
            ->where('supplier_contract_id', '=', $this->contract->id)
            ->join('transport_inventories', 'supplier_contract_components.component_id', '=', 'transport_inventories.id')
            ->join('transports', 'transports.id', '=', 'transport_inventories.transport_id')
            ->join('transport_types', 'transports.transport_type_id', '=', 'transport_types.id')
            ->join('operators', 'transports.operator_id', '=', 'operators.id')
            ->join('travel_classes', 'travel_classes.id', '=', 'transport_inventories.travel_class_id');
    }

    public function columns()
    {
        return [
            Column::name('transports.name')
                ->label('Name')
                ->sortable()
                ->searchable(),
            Column::name('transport_inventories.transport_number')
                ->label('Transport Number')
                ->sortable()
                ->searchable(),
            Column::name('operators.name')
                ->label('Operator')
                ->sortable()
                ->searchable(),
            Column::name('travel_classes.name')
                ->label('Travel Class')
                ->sortable()
                ->searchable(),
            DateColumn::name('transport_inventories.departs_at')
                ->label('Departure')
                ->sortable()
                ->filterable(),
            DateColumn::name('transport_inventories.arrives_at')
                ->label('Arrival')
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