<?php

namespace App\Http\Livewire\Admin\System\PaymentMethod;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Order\Payment\PaymentMethod;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public PaymentMethod|int|null $method = null;

    public function mount(PaymentMethod|int|null $method = null)
    {
        $this->method = PaymentMethod::getForMount($method);
    }

    public function render()
    {
        return view('livewire.admin.system.payment-method.form');
    }

    public function save(): void
    {
        $this->validate();
        $this->method->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function rules(): array
    {
        return [
            'method.name' => [
                'required',
                Rule::unique('payment_methods', 'name')->ignore($this->method->id),
            ],
            'method.fee_percentage' => [
                'nullable',
                'numeric',
                'min:0',
            ]
        ];
    }
}
