<?php

namespace App\Http\Livewire\Admin\Customer\LoyaltyNumber;

use App\Models\Customer\Customer;
use App\Models\Customer\LoyaltyNumber;
use App\Models\Customer\LoyaltyNumberType;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public Customer|null $customer;
    public function builder()
    {
        return LoyaltyNumber::query()
            ->where('customer_id', '=', $this->customer->id)
            ->leftJoin('loyalty_number_types', 'loyalty_number_types.id', '=', 'loyalty_numbers.loyalty_number_type_id');
    }

    public function columns()
    {
        return [
            Column::name('notes')
                ->label('Details')
                ->searchable()
                ->editable(),
            Column::name('loyalty_number')
                ->label('Loyalty Number')
                ->searchable()
                ->editable(),
            Column::name('loyalty_number_types.name')
                ->label('Type')
                ->searchable()
                ->sortable()
                ->filterable(LoyaltyNumberType::pluck('name'))
        ];
    }

    public function delete($id): void
    {
        LoyaltyNumber::find($id)?->delete();
    }
}