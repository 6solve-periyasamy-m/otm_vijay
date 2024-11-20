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
    ];

    public function render()
    {
        return view('livewire.admin.organization.details');
    }
}
