<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Models\Accommodation\AccommodationInventory as DataModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AccommodationInventory extends AbstractSelectComponent
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.generic', ['route' => 'inventory.accommodation']);
    }

    protected function getModels(?int $id = null): Collection
    {
        if ($id !== null) {
            return DataModel::where('id', '=', $id)->get();
        }
        return DataModel::with('component', 'boardType', 'roomType', 'component.address', 'component.address.country')->get();
    }

    protected function format(DataModel|Model $model): string
    {
        return "{$model->component->name} (" . f_datetime($model->check_in) .  " to " . f_datetime($model->check_out) .") ({$model->roomType->name}, Size: {$model->roomType->maximum_occupancy}) ({$model->boardType->name})";
    }
}
