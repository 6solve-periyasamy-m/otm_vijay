<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Models\User as UserModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class User extends AbstractSelectComponent
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.user');
    }

    protected function getModels(?int $id = null): Collection
    {
        if ($id !== null) {
            return UserModel::where('id', '=', $id)->get();
        }
        return UserModel::all();
    }

    protected function format(UserModel|Model $model): string
    {
        return "{$model->name} ({$model->email})";
    }
}
