<?php

namespace App\Http\Livewire\Admin\Location\Country;

use App\Models\Location\Country;
use Icon;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public $name = "country-table";

    public function builder()
    {
        return Country::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->sortable()
                ->searchable(),
            Column::callback(['alpha_code', 'cca2'], function ($code, $cca2) { return "$code ($cca2)"; })
                ->label('Code')
                ->sortable()
                ->searchable(),
            Column::name('dialing_code')
                ->sortable()
                ->searchable(),
            BooleanColumn::name('priority')
                ->sortable()
                ->filterable(),
            Column::callback(['id', 'priority'], function ($id, $priority) {
                return "<button wire:click='togglePriority($id)' class='btn btn-outline-" . ($priority ? 'success' : 'yellow') ."'>" . Icon::star() . "</button>";
            })
                ->maxWidth('3rem'),
        ];
    }

    public function togglePriority($id)
    {
        $country = Country::find($id);
        if ($country !== null) {
            $country->priority = !$country->priority;
            $country->save();
        }
        $this->refreshLivewireDatatable();
    }
}
