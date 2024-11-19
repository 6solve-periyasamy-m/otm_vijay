<?php

namespace App\Http\Livewire\Admin\Order\Component;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Order\Component\OrderTransport;
use LivewireUI\Modal\ModalComponent;

class OrderTransportForm extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public OrderTransport|int $component;

    public function mount(OrderTransport|int $component): void
    {
        $this->component = OrderTransport::getForMount($component);
    }

    public function save()
    {
        $this->validate();
        $this->component->save();
        $this->refreshPage();
        $this->toast('Changes Saved Successfully.', 'Successfully updated Order Transport component', 'success');
    }

    public function render()
    {
        return view('livewire.admin.order.component.order-transport-form');
    }

    public function rules()
    {
        return [
            'component.cost' => 'nullable|numeric|gte:0',
            'component.departs_at_time_override' => 'nullable|date_format:H:i',
            'component.arrives_at_time_override' => 'nullable|date_format:H:i',
        ];
    }
}
