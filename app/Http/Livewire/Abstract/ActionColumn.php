<?php

namespace App\Http\Livewire\Abstract;

use Mediconesystems\LivewireDatatables\Column;

class ActionColumn
{
    public static function view($field, $edit, $viewRoute = null, $buttonView = 'partials.admin.livewire.table.actions'): Column
    {
        return Column::callback(['id'], static function ($id) use ($viewRoute, $edit, $field, $buttonView) {
            return view($buttonView, [
                'id' => $id,
                'field' => $field,
                'edit' => $edit,
                'route' => $viewRoute,
            ]);
        })
            ->label(__('custom.table.actions'))
            ->width('15rem')
            ->unsortable();
    }

    public static function modal($field, $edit, $viewRoute = null,  $buttonView = 'partials.admin.livewire.table.actions'): Column
    {
        return Column::callback(['id'], static function ($id) use ($viewRoute, $edit, $field, $buttonView) {
            return view($buttonView, [
                'id' => $id,
                'field' => $field,
                'modal' => $edit,
                'route' => $viewRoute,
            ]);
        })
            ->label(__('custom.table.actions'))
            ->width('15rem')
            ->unsortable();
    }
}
