<?php

namespace App\Http\Livewire\Admin\Organization;

use App\Models\Customer\Organization;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public function builder()
    {
        return Organization::query()
            ->leftJoin('customers', 'customers.organization_id', '=', 'organizations.id')
            ->leftJoin('orders', 'orders.organization_id',  '=', 'organizations.id')
            ->leftJoin('quotes', 'quotes.organization_id', '=', 'organizations.id')
            ->groupBy('organizations.id');
    }

    public function columns()
    {
        return [
            Column::callback(['name', 'id'], function ($name, $id) {
                return '<a href="' . route('organizations.view', ['organization' => $id,]) .'">' . $name . '</a>';
            })
                ->label(__('organization.table.columns.name'))
                ->searchable()
                ->sortable(),
            Column::name('contact_email')
                ->label(__('organization.table.columns.email'))
                ->searchable()
                ->sortable(),
            Column::name('contact_number')
                ->label(__('organization.table.columns.telephone'))
                ->searchable()
                ->sortable(),
            NumberColumn::raw('COUNT(customers.id)')
                ->label(__('organization.table.columns.customers'))
                ->sortable(),
            NumberColumn::raw('COUNT(orders.id)')
                ->label(__('organization.table.columns.orders'))
                ->sortable(),
            NumberColumn::raw('COUNT(quotes.id)')
                ->label(__('organization.table.columns.quotes'))
                ->sortable(),
            Column::callback(['id'], function ($id) {
                return view('partials.admin.livewire.table.actions', [
                    'id' => $id,
                    'field' => 'organization',
                    'modal' => 'admin.organization.form',
                    'route' => 'organizations.view'
                ]);
            })
        ];
    }

    public function delete($id): void
    {
        Organization::find($id)?->delete();
        $this->refreshLivewireDatatable();
    }
}