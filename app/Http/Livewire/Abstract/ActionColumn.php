<?php

namespace App\Http\Livewire\Abstract;

use Mediconesystems\LivewireDatatables\Column;

class ActionColumn
{
    public static function view($field, $edit, $view = null): Column
    {
        return Column::callback(['id'], static function ($id) use ($view, $edit, $field) {
            return view('partials.admin.livewire.table.actions', [
                'id' => $id,
                'field' => $field,
                'edit' => $edit,
                'route' => $view,
            ]);
        })
            ->label(__('custom.table.actions'))
            ->width('15rem')
            ->unsortable();
    }

    public static function modal($field, $edit, $view = null): Column
    {
        return Column::callback(['id'], static function ($id) use ($view, $edit, $field) {
            return view('partials.admin.livewire.table.actions', [
                'id' => $id,
                'field' => $field,
                'modal' => $edit,
                'route' => $view,
            ]);
        })
            ->label(__('custom.table.actions'))
            ->width('15rem')
            ->unsortable();
    }
}
