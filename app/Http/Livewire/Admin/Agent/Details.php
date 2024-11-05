<?php

namespace App\Http\Livewire\Admin\Agent;

use App\Models\Customer\Agent;
use Livewire\Component;

class Details extends Component
{
    public Agent $agent;

    public $listeners = ['refreshLivewireDatatable' => 'render',];

    public function render()
    {
        return view('livewire.admin.agent.details');
    }
}