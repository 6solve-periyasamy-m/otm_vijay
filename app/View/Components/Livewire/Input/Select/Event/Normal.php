<?php

namespace App\View\Components\Livewire\Input\Select\Event;

use App\Models\Helper\Enum\EventType;
use App\Models\Tour\Event as DataModel;
use App\View\Components\Livewire\Input\Select\AbstractSelectComponent;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Normal extends AbstractSelectComponent
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.event', ['route' => 'events.normal']);
    }

    protected function getModels(?int $id = null): Collection
    {
        if ($id !== null) {
            // This should *always* return the selected event otherwise
            // You end up with things getting updated by accident
            return DataModel::where('id', '=', $id)->get();
        }
        return DataModel::where('event_category', EventType::NORMAL)
            ->whereDate('ends_at', '>=', Carbon::today())
            ->orderBy('ends_at')
            ->get();
    }

    protected function format(DataModel|Model $model): string
    {
        return "{$model->name}";
    }
}
