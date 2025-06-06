<?php

namespace App\Http\Livewire\Admin\Quote\Basic;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Tour\Tour;
use App\Repository\Storage\Quote\BasicQuote;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{
    use LivewireForm, SendsEvents;

    public BasicQuote $quote;
    public bool $agentRequired = false;

    public function mount(Tour|int $tour)
    {
        $tour = Tour::getForMount($tour);
        $this->quote = new BasicQuote($tour);
    }
    
    public function updated($key, $value)
    {
        if ($key === 'quote.final') {
            $date = Carbon::parse($value);
            if ($date->lt(now())) {
                $this->addError('quote.final', 'That date has already passed');
            } else {
                $this->resetValidation('quote.final');
            }
        }
        if ($key === 'quote.expiry') {
            $date = Carbon::parse($value);
            if ($date->lt(now())) {
                $this->addError('quote.expiry', 'That date has already passed');
            } else {
                $this->resetValidation('quote.expiry');
            }
        }
    }

    public function save()
    {
        $this->validate();
        $quote = $this->quote->convert();
        return redirect()->route('quotes.view', ['quote' => $quote]);
    }

    public function addCost()
    {
        $this->quote->costs[] = [
            'id' => null,
            'name' => null,
            'amount' => null,
            'per_customer' => false,
        ];
    }

    public function removeCost($key)
    {
        if (array_key_exists($key, $this->costs)) {
            unset($this->quote->costs[$key]);
        }
    }

    public function render()
    {
        return view('livewire.admin.quote.basic.form');
    }

    public function rules(): array
    {
        return [
            'quote.brand' => 'nullable|int|exists:brands,id',
            'quote.lead' => 'required|int|exists:customers,id',
            'quote.tax' => 'nullable|int|exists:tax_brackets,id',
            'quote.organization' => 'nullable|int|exists:organizations,id',
            'quote.agent' => $this->agentRequired
                    ? 'required|int|exists:agents,id'
                    : 'nullable|int|exists:agents,id',
            'quote.expiry' => 'required|date|date_format:Y-m-d',
            'quote.travelling' => 'nullable|boolean',
            'quote.paying' => 'nullable|boolean',
            'quote.singleOccupancy' => 'nullable|numeric|min:0',
            'quote.final' => 'required|date|date_format:Y-m-d',
            'quote.deposit' => 'required|numeric|min:0',
            'quote.depositPercentage' => 'nullable|boolean',
            'quote.commission' => 'nullable|numeric|min:0',
            'quote.internalNotes' => 'nullable|string',
            'quote.externalNotes' => 'nullable|string',
            'quote.costs.*.id' => 'nullable|int|exists:additional_costs,id',
            'quote.costs.*.name' => 'required|string|min:3',
            'quote.costs.*.amount' => 'required|numeric',
            'quote.costs.*.per_customer' => 'boolean',
        ];
    }
}
