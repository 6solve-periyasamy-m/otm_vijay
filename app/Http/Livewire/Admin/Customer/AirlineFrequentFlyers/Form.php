<?php

namespace App\Http\Livewire\Admin\Customer\AirlineFrequentFlyers;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Customer\AirlineFrequentFlyers;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents, LivewireForm;

    /** @var AirlineFrequentFlyers */
    public AirlineFrequentFlyers|int|null $frequentflyer;

    public function mount(AirlineFrequentFlyers|int|null $frequentflyer = null)
    {
        $this->frequentflyer = AirlineFrequentFlyers::getForMount($frequentflyer);
    }

    public function save()
    {
        $this->validate();
        $this->frequentflyer->save();
        $this->refreshTables();
        $this->closeModal();
    }
    public function render()
    {
        return view('livewire.admin.customer.airline-frequent-flyers.form');
    }

    public function rules()
    {
        return [
            'frequentflyer.name' => 'required|string',
        ];
    }
}
