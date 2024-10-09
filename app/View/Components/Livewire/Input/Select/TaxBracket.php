<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Models\System\TaxBracket as TaxModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TaxBracket extends AbstractSelectComponent
{
    use LivewireForm;

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.tax-bracket');
    }

    protected function getModels(?int $id = null): Collection
    {
        if ($id !== null) {
            return TaxModel::where('id', '=', $id)->get();
        }
        return TaxModel::all();
    }

    protected function format(TaxModel|Model $model): string
    {
        return $model->name . ' - ' . ($model->rate === null ? 'No Taxes' : $model->rate . '%');
    }

}
