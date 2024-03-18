<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Models\Customer\Organization as OrgModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Organization extends AbstractSelectComponent
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.organization');
    }

    protected function getModels(?int $id = null): Collection
    {
        if ($id !== null) {
            return OrgModel::where('id', '=', $id)->get();
        }
        return OrgModel::all();
    }

    protected function format(OrgModel|Model $model): string
    {
        return $model->name;
    }
}
