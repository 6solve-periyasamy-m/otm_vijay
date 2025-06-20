<?php

namespace App\Http\Livewire\Admin\Order\Component;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Order\Component\OrderAccommodation;
use LivewireUI\Modal\ModalComponent;

class OrderAccommodationForm extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public OrderAccommodation|int $component;

    public function mount(OrderAccommodation|int $component): void
    {
        $this->component = OrderAccommodation::getForMount($component);
    }

    public function save()
    {
        $this->validate();
        $this->component->save();
        $this->refreshPage();
        $this->toast('Changes Saved Successfully.', 'Successfully updated Order Accommodation component', 'success');
    }

    public function render()
    {
        return view('livewire.admin.order.component.order-accommodation-form');
    }

    public function rules()
    {
        return [
            'component.cost' => 'nullable|numeric|gte:0',
            'component.estimated_purchase_price' => 'nullable|numeric|gte:0',
        ];
    }
}
