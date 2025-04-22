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
    public bool $estimateInverse = false;

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

    public function canEstimateInverse(): bool
    {
        if ($this->rate->from === null || $this->rate->to === null) {
            // if one or the other isn't set, then can't check
            return false;
        }
        if ($this->rate->from_currency_id === $this->rate->to_currency_id) {
            // if they're the same, can't check
            return false;
        }
        // Make sure one doesn't already exist in the system
        return ConversionRate::where('from_currency_id', $this->rate->to_currency_id)
                ->where('to_currency_id', $this->rate->from_currency_id)->first() === null;
    }

    public function save()
    {
        $this->validate();
        $this->rate->save();
        if ($this->estimateInverse && $this->canEstimateInverse()) {
            ConversionRate::create([
               'from_currency_id' => $this->rate->to_currency_id,
               'to_currency_id' => $this->rate->from_currency_id,
               'rate' => sigfig(1.0 / $this->rate->rate),
            ]);
        }
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
