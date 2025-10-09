<?php

namespace App\Http\Livewire\Admin\Customer\LoyaltyNumberType;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Customer\LoyaltyNumberType;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public LoyaltyNumberType|null $type = null;

    public function mount(LoyaltyNumberType|int|null $type = null): void
    {
        $this->type = LoyaltyNumberType::getForMount($type);
    }

    public function save(): void
    {
        $this->validate();
        $this->type->save();
        $this->closeModal();
        //$this->redirect(route('attributes.edit'));
    }

    public function rules(): array
    {
        return [
            'type.name' => ['required', 'string', Rule::unique('loyalty_number_types', 'name')->ignore($this->type),],
        ];
    }

    public function render()
    {
        return view('livewire.admin.customer.loyalty-number-type.form');
    }
}
