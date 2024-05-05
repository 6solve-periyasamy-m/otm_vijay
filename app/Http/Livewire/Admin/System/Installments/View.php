<?php

namespace App\Http\Livewire\Admin\System\Installments;

use App\Http\Livewire\SendsEvents;
use Livewire\Component;
use Settings;

class View extends Component
{
    use SendsEvents;

    protected $listeners = ['refreshLivewireDatatable' => 'render',];

    public function delete($days)
    {
        Settings::setDefaultInstallment($days, null);
        $this->refreshTables();
    }

    public function render()
    {
        return view('livewire.admin.system.installments.view');
    }
}
