<?php

namespace App\Http\Livewire\Admin\Organization;

use App\Models\Customer\Organization;
use App\Models\Customer\Agent;
use Livewire\Component;

class Details extends Component
{
    public Organization $organization;

    public $listeners = [
        'refreshLivewireDatatable' => 'render',
        'agent-delete' => 'deleteAgent'
    ];

    public function deleteAgent($id): void
    {
        \Log::info('agent-delete', ['id' => $id]);
        Agent::find($id)?->delete();
        $this->refreshLivewireDatatable();
    }


    public function render()
    {
        \Log::info('render organization');
        return view('livewire.admin.organization.details');
    }
}
