<?php

namespace App\View\Components\Livewire\Input\Select\Accommodation;

use App\Models\Accommodation\RoomCategory as DataModel;
use App\View\Components\Livewire\Input\Select\AbstractSelectComponent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RoomCategory extends AbstractSelectComponent
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.accommodation.room-category');
    }

    protected function getModels(?int $id = null): Collection
    {
        if ($id !== null) {
            return DataModel::where('id', '=', $id)->get();
        }
        return DataModel::all();
    }

    protected function format(DataModel|Model $model): string
    {
        return "$model->name";
    }
}
