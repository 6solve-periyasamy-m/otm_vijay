<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Models\System\LargeTextTemplate as DataModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class LargeTextTemplate extends AbstractSelectComponent
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.generic', ['route' => 'large-text-templates']);
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
        return "{$model->name} | {$model->type->label()}";
    }
}
