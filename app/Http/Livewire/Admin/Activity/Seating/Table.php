<?php

namespace App\Http\Livewire\Admin\Activity\Seating;

use App\Http\Livewire\SendsEvents;
use App\Models\Activity\Seating;
use Icon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public $name = "activity-seating-table";

    public function builder()
    {
        return Seating::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label('Name')
                ->searchable()
                ->editable()
                ->sortable(),
            NumberColumn::raw('(SELECT count(*) FROM activities WHERE seating_id = seatings.id AND deleted_at IS NULL)')
                ->label('Related')
                ->sortable(),
            Column::callback(['id'], function ($id) {
                return '<button wire:click="delete(' . $id . ')" class="btn btn-outline-danger btn-sm mb-1" title="Delete">' . Icon::delete() .'</button>';
            })
                ->label('Actions')
        ];
    }

    public function delete($id): void
    {
        /** @var Seating|null $model */
        $model = Seating::find($id);
        if ($model !== null) {
            if ($model->activities()->count() > 0) {
                $this->toast('Cannot Delete Seating', 'Cannot delete a seating with related activities.', 'danger');
            } else {
                $model->delete();
                $this->refreshTables();
            }
        }
    }
}
