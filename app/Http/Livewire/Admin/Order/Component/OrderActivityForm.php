<?php

namespace App\Http\Livewire\Admin\Order\Component;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Order\Component\OrderActivity;
use LivewireUI\Modal\ModalComponent;

class OrderActivityForm extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public OrderActivity|int $component;

    public function mount(OrderActivity|int $component): void
    {
        $this->component = OrderActivity::getForMount($component);
    }

    public function save()
    {
        $this->validate();
        $this->component->save();
        $this->refreshPage();
        $this->toast('Changes Saved Successfully.', 'Successfully updated Order Activity component', 'success');
    }

    public function render()
    {
        return view('livewire.admin.order.component.order-activity-form');
    }

    public function rules()
    {
        return [
            'component.cost' => 'nullable|numeric|gte:0',
            'component.estimated_purchase_price' => 'nullable|numeric|gte:0',
        ];
    }
}
