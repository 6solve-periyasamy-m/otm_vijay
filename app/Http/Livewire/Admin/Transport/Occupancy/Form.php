<?php

namespace App\Http\Livewire\Admin\Transport\Occupancy;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Transport\TransportOccupancy;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents, LivewireForm;

    /** @var TransportOccupancy */
    public TransportOccupancy|int|null $occupancy;

    public function mount(TransportOccupancy|int|null $occupancy = null)
    {
        $this->occupancy = TransportOccupancy::getForMount($occupancy);
    }

    public function save()
    {
        $this->validate();
        $this->occupancy->save();
        $this->refreshTables();
        $this->closeModal();
    }
    public function render()
    {
        return view('livewire.admin.transport.occupancy.form');
    }

    public function rules()
    {
        return [
            'occupancy.name' => 'required|string',
            'occupancy.maximum_occupancy' => 'required|integer|min:1',
        ];
    }
}
