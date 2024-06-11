<?php

namespace App\Http\Livewire\Admin\System\LargeTextTemplate;

use App\Http\Livewire\SendsEvents;
use App\Models\Helper\Enum\LargeTextType;
use App\Models\System\LargeTextTemplate;
use Livewire\Component;

class Table extends Component
{
    use SendsEvents;

    protected $listeners = ['refreshLivewireDatatable' => 'render',];

    public function render()
    {
        return view('livewire.admin.system.large-text-template.table');
    }

    public function delete($id)
    {
        LargeTextTemplate::find($id)?->delete();
    }
}
