<?php

namespace App\Http\Livewire\Abstract;

use Mediconesystems\LivewireDatatables\Column;

class ImageColumn
{
    public static function view($field): Column
    {
        return Column::callback([$field,], static function ($field) {
            return view('partials.admin.livewire.table.image', [
                'image' => $field,
            ]);
        });
    }
}
