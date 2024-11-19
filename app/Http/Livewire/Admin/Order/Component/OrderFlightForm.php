<?php

namespace App\Http\Livewire\Admin\Order\Component;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Order\Component\OrderFlight;
use LivewireUI\Modal\ModalComponent;

class OrderFlightForm extends ModalComponent
{
    use SendsEvents, LivewireForm;

    public OrderFlight|int $component;

    public function mount(OrderFlight|int $component): void
    {
        $this->component = OrderFlight::getForMount($component);
    }

    public function render()
    {
        return view('livewire.admin.order.component.order-flight-form');
    }

    public function save()
    {
        $this->validate();
        $this->component->save();
        $this->refreshPage();
        $this->toast('Changes Saved Successfully.', 'Successfully updated Order Flight component', 'success');
    }

    public function rules()
    {
        return [
            'component.cost' => 'nullable|numeric|gte:0',
            'component.flight_number_override' => 'nullable|string',
        ];
    }
}
