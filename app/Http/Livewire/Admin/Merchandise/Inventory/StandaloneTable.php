<?php

namespace App\Http\Livewire\Admin\Merchandise\Inventory;

use App\Http\Livewire\Abstract\StandaloneDatatable;
use App\Models\Merchandise\MerchandiseInventory;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;

class StandaloneTable extends StandaloneDatatable
{
    public function builder()
    {
        $query = MerchandiseInventory::query()
            ->join('merchandises', 'merchandises.id', '=', 'merchandise_inventories.merchandise_id')
            ->join('variants', 'merchandise_inventories.variant_id', '=', 'variants.id')
            ->join('merchandise_sizes', 'merchandise_inventories.merchandise_size_id', '=', 'merchandise_sizes.id')
            ->join('merchandise_types', 'merchandises.merchandise_type_id', '=', 'merchandise_types.id');
        return $this->hideLinked($query, 'merchandise_inventories.id');
    }

    public function columns()
    {
        return [
            Column::checkbox()
                ->width('10rem'),
            Column::name('merchandises.name')
                ->label('Name')
                ->sortable()
                ->searchable(),
            Column::name('merchandise_types.name')
                ->label('Type')
                ->sortable()
                ->filterable(),
            Column::name('variants.name')
                ->label('Variant')
                ->sortable()
                ->filterable(),
            Column::name('merchandise_sizes.name')
                ->label('Size')
                ->sortable()
                ->filterable(),
            NumberColumn::callback(['purchase_price'], function ($price) {
                return f_currency($price);
            })
                ->label('Purchase Price')
                ->sortable()
                ->filterable(),
            NumberColumn::callback(['sales_price'], function ($price) {
                return f_currency($price);
            })
                ->label('Sales Price')
                ->sortable()
                ->filterable(),
        ];
    }

    public function getClass(): string
    {
        return MerchandiseInventory::class;
    }
}