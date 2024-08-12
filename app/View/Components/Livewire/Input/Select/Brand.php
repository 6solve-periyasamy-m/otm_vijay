<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Http\Requests\SelectFilterRequest;
use App\Models\System\Brand as DataModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Brand extends AbstractSelectComponent
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.generic', ['route' => 'brands']);
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
        return "{$model->name}";
    }
}
