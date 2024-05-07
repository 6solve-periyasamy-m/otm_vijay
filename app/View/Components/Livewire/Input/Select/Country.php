<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Models\Location\Country as DataModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Country extends AbstractSelectComponent
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.generic', ['route' => 'countries']);
    }

    protected function getModels(?int $id = null): Collection
    {
        if ($id !== null) {
            return DataModel::where('id', '=', $id)->get();
        }
        return DataModel::orderBy('priority', 'desc')->orderBy('name', 'asc')->get();
    }

    protected function format(DataModel|Model $model): string
    {
        return "{$model->name} - {$model->alpha_code} " . ($model->priority ? '*' : '');
    }
}
