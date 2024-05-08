<?php

namespace App\Http\Livewire\Admin\System\Installments;

use App\Http\Livewire\SendsEvents;
use Livewire\Component;
use Settings;

class View extends Component
{
    use SendsEvents;
    public float|int|null $deposit;

    protected $listeners = ['refreshLivewireDatatable' => 'update',];

    public function mount(): void
    {
        $this->update();
    }

    public function update(): void
    {
        $this->deposit = setting('system.installments.deposit', null);
        $this->render();
    }

    public function setDeposit(): void
    {
        Settings::set('system.installments.deposit', $this->deposit);
        $this->refreshTables();
        $this->toast('Updated Successfully', 'Successfully updated the default deposit', 'success');
    }

    public function delete($days): void
    {
        Settings::setDefaultInstallment($days, null);
        $this->refreshTables();
    }

    public function render()
    {
        return view('livewire.admin.system.installments.view');
    }

    public function rules(): array
    {
        return ['deposit' => 'required|numeric|gte:0',];
    }
}
