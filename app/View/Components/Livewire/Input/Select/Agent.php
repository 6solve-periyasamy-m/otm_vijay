<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Models\Customer\Agent as OrgAgent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Agent extends AbstractSelectComponent
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.livewire.input.select.agent');
    }

    /**
     * getModels - finds the agent with the specific id, or all agents
     * 
     * TODO: inject selected organization?
     * 
     * @param int|null $id
     * @return Collection
     */
    protected function getModels(?int $id = null): Collection
    {
        if ($id !== null) {
            return OrgAgent::with('organization')->where('id', '=', $id)->get();
        }
        return OrgAgent::with('organization')->get();
    }

    protected function format(OrgAgent|Model $model): string
    {
        return $model->first_name . ' ' . $model->last_name . ' - ' . $model->organization->name;
    }
}
