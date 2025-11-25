<?php

namespace App\Http\Livewire\Admin\Activity\SeatingMap;

use App\Http\Livewire\Abstract\ActionColumn;
use App\Http\Livewire\Abstract\ImageColumn;
use App\Http\Livewire\SendsEvents;
use App\Models\Activity\SeatingMap;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public $name = "activity-seating-map-table";

    public function builder()
    {
        return SeatingMap::query();
    }

    public function columns()
    {
        return [
            Column::name('name')
                ->label('Name')
                ->searchable()
                ->editable()
                ->sortable(),
            ImageColumn::view('image_url')
                ->label('Image'),
            NumberColumn::raw('(SELECT count(*) FROM activities WHERE seating_map_id = seating_maps.id AND deleted_at IS NULL)')
                ->label('Related')
                ->sortable(),
            ActionColumn::modal('map', 'admin.activity.seating-map.form')
                ->label('Actions')
                ->width('5rem')
        ];
    }

    public function delete($id): void
    {
        /** @var SeatingMap|null $model */
        $model = SeatingMap::find($id);
        if ($model !== null) {
            if ($model->activities()->count() > 0) {
                $this->toast('Cannot Delete Seating Map', 'Cannot delete a seating map with related activities.', 'danger');
            } else {
                $model->delete();
                $this->refreshTables();
            }
        }
    }
}
