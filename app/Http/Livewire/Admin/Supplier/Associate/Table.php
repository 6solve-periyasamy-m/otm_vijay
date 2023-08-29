<?php

namespace App\Http\Livewire\Admin\Supplier\Associate;

use App\Models\Supplier\SupplierAssociate;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public function builder()
    {
        return SupplierAssociate::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label(__('supplier.associate.table.name')),
            Column::name('job_title')
                ->label(__('supplier.associate.table.job_title')),
            Column::callback('email', function ($email) { return "<a href=\"mailto:{$email}\">{$email}</a>"; })
                ->label(__('supplier.associate.table.email')),
            Column::callback('primary_phone', function ($tel) { return "<a href=\"tel:{$tel}\">{$tel}</a>"; })
                ->label(__('supplier.associate.table.primary_phone')),
            Column::callback('alternative_phone', function ($tel) { return "<a href=\"tel:{$tel}\">{$tel}</a>"; })
                ->label(__('supplier.associate.table.alternative_phone')),
        ];
    }
}