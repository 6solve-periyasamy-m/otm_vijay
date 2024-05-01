<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Models\Customer\Customer as CustomerModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Customer extends AbstractSelectComponent
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.customer');
    }

    protected function getModels(?int $id = null): Collection
    {
        if ($id !== null) {
            return CustomerModel::where('id', '=', $id)->get();
        }
        return CustomerModel::all();
    }

    protected function format(CustomerModel|Model $model): string
    {
        return "$model->title $model->first_name $model->last_name";
    }
}
