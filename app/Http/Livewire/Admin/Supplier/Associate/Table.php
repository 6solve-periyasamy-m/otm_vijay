<?php

namespace App\Http\Livewire\Admin\Supplier\Associate;

use App\Models\Supplier\SupplierAssociate;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public $name = 'supplier-associate-table';

    public $supplier;

    public function builder()
    {
        return SupplierAssociate::query()
            ->where('supplier_id', '=', $this->supplier);
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label(__('supplier.associate.table.name'))
                ->searchable()
                ->sortable(),
            Column::name('job_title')
                ->label(__('supplier.associate.table.job_title'))
                ->searchable()
                ->sortable(),
            Column::callback('email', function ($email) { return "<a href=\"mailto:{$email}\">{$email}</a>"; })
                ->label(__('supplier.associate.table.email'))
                ->searchable()
                ->sortable(),
            Column::callback('primary_phone', function ($tel) { return "<a href=\"tel:{$tel}\">{$tel}</a>"; })
                ->label(__('supplier.associate.table.primary_phone'))
                ->searchable()
                ->sortable(),
            Column::callback('alternative_phone', function ($tel) { return "<a href=\"tel:{$tel}\">{$tel}</a>"; })
                ->label(__('supplier.associate.table.alternative_phone'))
                ->searchable()
                ->sortable(),
            Column::name('notes')
                ->label(__('supplier.associate.table.notes'))
                ->searchable()
                ->sortable(),
            Column::callback(['id'],  function ($id) {
                return view('partials.admin.livewire.table.actions', [
                    'modal' => 'admin.supplier.associate.form',
                    'field' => 'associate',
                    'id' => $id,
                ]);
            })
                ->label(__('custom.table.actions')),
        ];
    }

    public function delete($id)
    {
        $associate = SupplierAssociate::where('supplier_id', '=', $this->supplier)->where('id', '=', $id)->first();
        $associate?->delete();
    }
}
