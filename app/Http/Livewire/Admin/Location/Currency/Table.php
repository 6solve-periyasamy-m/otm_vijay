<?php

namespace App\Http\Livewire\Admin\Location\Currency;

use App\Models\Location\Currency;
use Icon;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    public function builder()
    {
        return Currency::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->sortable()
                ->searchable(),
            Column::name('code')
                ->label('Code')
                ->sortable()
                ->searchable(),
            Column::name('symbol')
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
        $model = Currency::find($id);
        if ($model !== null) {
            $model->priority = !$model->priority;
            $model->save();
        }
        $this->refreshLivewireDatatable();
    }
}