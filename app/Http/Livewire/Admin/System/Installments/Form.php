<?php

namespace App\Http\Livewire\Admin\System\Installments;

use App\Http\Livewire\SendsEvents;
use LivewireUI\Modal\ModalComponent;
use Settings;

class Form extends ModalComponent
{
    use SendsEvents;

    public string|null $days;
    public string|null $percentage;

    public function mount(int|null $days = null, int|null $percentage = null): void
    {
        $this->days = $days;
        $this->percentage = $percentage;
    }

    public function save(): void
    {
        $this->validate();
        Settings::setDefaultInstallment((int)$this->days, (float)$this->percentage);
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.system.installments.form');
    }

    public function rules()
    {
        return [
            'days' => 'required|integer',
            'percentage' => 'required|numeric'
        ];
    }
}
