<?php

namespace App\Http\Livewire\Admin\System\Bank;

use App\Models\System\Bank;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    public Bank|int|null $bank;

    public function mount(Bank|int|null $bank = null)
    {
        if (is_int($bank)) {
            $bank = Bank::find($bank);
        }
        $this->bank = $bank ?? new Bank();
    }

    public function save()
    {
        $this->validate();
        $this->bank->save();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.system.bank.form');
    }

    public function rules()
    {
        return [
            'bank.name' => 'required',
            'bank.address_line_1' => 'required',
            'bank.address_line_2' => 'nullable',
            'bank.town' => 'nullable',
            'bank.region' => 'nullable',
            'bank.country' => 'required',
            'bank.postcode' => 'nullable',
        ];
    }
}
