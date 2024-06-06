<?php

namespace App\Http\Livewire\Admin\System\Conversion;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\System\ConversionRate;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;
    use LivewireForm;

    /** @var ConversionRate|null $rate */
    public ConversionRate|int|null $rate = null;

    public function mount(ConversionRate|int|null $rate = null)
    {
        if (is_int($rate)) {
            $rate = ConversionRate::find($rate);
        }
        if ($rate === null) {
            $rate = new ConversionRate();
        }
        $this->rate = $rate;
    }

    public function save()
    {
        $this->validate();
        $this->rate->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.system.conversion.form');
    }

    public function rules()
    {
        return [
            'rate.from_currency_id' => 'required|int|exists:currencies,id',
            'rate.to_currency_id' => 'required|int|exists:currencies,id',
            'rate.rate' => 'required|numeric|gt:0',
        ];
    }
}
