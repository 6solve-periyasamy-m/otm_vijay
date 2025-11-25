<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Models\System\LargeTextTemplate as DataModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\Helper\Enum\LargeTextType;

class LargeTextTemplate extends AbstractSelectComponent
{
    public ?LargeTextType $filterType = null;

    public function __construct(?int $filterType = null)
    {
        if (!is_null($filterType)) {
            $this->filterType = LargeTextType::from($filterType);
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.generic', ['route' => 'large-text-templates', 'filterType' => $this->filterType?->value,]);
    }

    protected function getModels(?int $id = null): Collection
    {
        if ($this->filterType === null && request()->has('filterType')) {
            $this->filterType = LargeTextType::from((int) request('filterType'));
        }

        if ($id !== null) {
            return DataModel::where('id', $id)->get();
        }

        $query = DataModel::query();

        if ($this->filterType !== null) {
            $query->where('type', $this->filterType->value);
        }

        return $query->get();
    }

    protected function format(DataModel|Model $model): string
    {
        return "{$model->name} | {$model->type->label()}";
    }
}
