<?php

namespace App\Http\Livewire\Admin\System\Installments;

use App\Http\Livewire\SendsEvents;
use LivewireUI\Modal\ModalComponent;
use Settings;

class Form extends ModalComponent
{
    use SendsEvents;

    public int|null $days;
    public int|null $percentage;

    public function mount(int|null $days = null, int|null $percentage = null)
    {
        $this->days = $days;
        $this->percentage = $percentage;
    }

    public function save()
    {
        Settings::setDefaultInstallment($this->days, $this->percentage);
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.system.installments.form');
    }
}
