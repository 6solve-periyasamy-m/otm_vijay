<?php

namespace App\Http\Livewire\Admin\System\LargeTextTemplate;

use App\Http\Livewire\SendsEvents;
use Livewire\Component;

class Table extends Component
{
    use SendsEvents;

    protected $listeners = ['refreshLivewireDatatable' => 'render',];

    public function render()
    {
        return view('livewire.admin.system.large-text-template.table');
    }
}
