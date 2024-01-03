<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Component;

use App\Models\Activity\ActivityInventory;
use App\Models\Supplier\SupplierContract;
use App\Models\Supplier\SupplierContractComponent;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DatetimeColumn;

class ActivityTable extends ComponentTable
{
    public SupplierContract $contract;

    public function builder()
    {
        return SupplierContractComponent::query()
            ->where('component_type', '=', ActivityInventory::class)
            ->where('supplier_contract_id', '=', $this->contract->id)
            ->join('activity_inventories', 'supplier_contract_components.component_id', '=', 'activity_inventories.id')
            ->join('activities', 'activities.id', '=', 'activity_inventories.activity_id')
            ->join('ticket_types', 'ticket_types.id', '=', 'activity_inventories.ticket_type_id')
            ->join('activity_types', 'activity_types.id', '=', 'activities.activity_type_id');
    }

    public function columns()
    {
        return [
            Column::name('activities.name')
                ->label('Activity')
                ->sortable()
                ->searchable(),
            Column::name('activity_types.name')
                ->label('Activity Type')
                ->sortable()
                ->searchable(),
            Column::name('ticket_types.name')
                ->label('Ticket Type')
                ->sortable()
                ->searchable(),
            DatetimeColumn::name('activity_inventories.starts_at')
                ->label('Starts At')
                ->sortable()
                ->filterable(),
            DatetimeColumn::name('activity_inventories.ends_at')
                ->label('Ends At')
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