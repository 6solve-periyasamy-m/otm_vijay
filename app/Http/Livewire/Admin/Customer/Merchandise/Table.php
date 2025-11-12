<?php

namespace App\Http\Livewire\Admin\Customer\Merchandise;

use App\Models\Customer\Customer;
use App\Models\Customer\CustomerMerchandise;
use App\Models\Customer\MerchandiseCategory;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public Customer|null $customer;
    public function builder()
    {
        return CustomerMerchandise::query()
            ->where('customer_id', '=', $this->customer->id)
            ->leftJoin('merchandise_categories', 'merchandise_categories.id', '=', 'customer_merchandises.merchandise_category_id');
    }

    public function columns()
    {
        return [
            Column::name('size')
                ->label('Size')
                ->searchable()
                ->editable(),
            Column::name('other_details')
                ->label('Other Details')
                ->searchable()
                ->editable(),
            Column::name('merchandise_categories.name')
                ->label('Type')
                ->searchable()
                ->sortable()
                ->filterable(MerchandiseCategory::pluck('name'))
        ];
    }

    public function delete($id): void
    {
        CustomerMerchandise::find($id)?->delete();
    }
}