<?php

namespace App\Http\Livewire\Admin\System\Installments;

use App\Http\Livewire\SendsEvents;
use Livewire\Component;
use Settings;

class View extends Component
{
    use SendsEvents;
    public string|float|int|null $deposit;
    public string|float|int|null $final;

    protected $listeners = ['refreshLivewireDatatable' => 'update',];

    public function mount(): void
    {
        $this->update();
    }

    public function update(): void
    {
        $this->deposit = setting('system.installments.deposit', null);
        $this->final = setting('system.installments.final', null);
        $this->render();
    }

    public function setDeposit(): void
    {
        Settings::set('system.installments.deposit', empty($this->deposit) ? null : $this->deposit);
        $this->refreshTables();
        $this->toast('Updated Successfully', 'Successfully updated the default deposit', 'success');
    }

    public function setFinal(): void
    {
        Settings::set('system.installments.final', empty($this->final) ? null : $this->final);
        $this->refreshTables();
        $this->toast('Updated Successfully', 'Successfully updated the default final payment date', 'success');
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
        return ['deposit' => 'nullable|numeric|gte:0', 'final' => 'nullable|integer|gte:0',];
    }
}
