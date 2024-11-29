<?php

namespace App\Http\Livewire\Admin\Supplier;

use App\Models\Supplier\Supplier;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public $name = 'supplier-table';
    public function builder()
    {
        return Supplier::query()
            ->leftJoin('addresses', 'addresses.id', '=', 'suppliers.address_id')
            ->leftJoin('currencies', 'currencies.id', '=', 'suppliers.currency_id');
    }

    public function columns(): array
    {
        return [
            Column::callback(['name', 'id'], function ($name, $id) {
                $route = route('supplier.view', ['supplier' => $id]);
                return "<a href=\"{$route}\">$name</a>";
            })
                ->label('Name')
                ->sortable()
                ->searchable(),
            Column::callback(['website'], function ($website) {
                return "<a target='_blank' href=\"{$website}\">$website</a>";
            })
                ->label('Website')
                ->sortable()
                ->searchable(),
            Column::callback(['telephone'], function ($telephone) {
                return "<a target='_blank' href=\"tel:{$telephone}\">$telephone</a>";
            })
                ->label('Telephone')
                ->sortable()
                ->searchable(),
            Column::callback(['email'], function ($email) {
                return "<a target='_blank' href=\"mailto:{$email}\">$email</a>";
            })
                ->label('Email')
                ->sortable()
                ->searchable(),
            Column::name('currencies.name')
                ->label('Trading Currency')
                ->sortable()
                ->searchable(),
            Column::callback(['id'], function ($id) {
                return view('partials.admin.livewire.table.actions', [
                    'id' => $id,
                    'field' => 'supplier',
                    'modal' => 'admin.supplier.form',
                    'route' => 'supplier.view'
                ]);
            })
                ->label('Actions')
                ->unsortable()
                ->width('12rem'),
        ];
    }

    public function delete($id)
    {
        // TODO: Implement Properly
        Supplier::find($id)?->delete();
    }
}
