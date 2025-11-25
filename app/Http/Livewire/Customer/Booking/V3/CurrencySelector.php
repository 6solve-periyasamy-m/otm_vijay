<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use Livewire\Component;

class CurrencySelector extends Component
{
    public string $selectedCurrency;
    public array $availableCurrencies = ['AUD'];
    //public array $availableCurrencies = ['AUD', 'USD', 'GBP', 'SGD', 'INR', 'EUR'];

    protected $listeners = ['setCurrencyExternally'];

    public function mount($currency = null)
    {
        $this->selectedCurrency = $currency ?? $this->availableCurrencies[0];
        $this->emit('currencyUpdated', $this->selectedCurrency);
    }

    public function updatedSelectedCurrency($value)
    {
        $this->emit('currencyUpdated', $value);
    }

    public function setCurrencyExternally($currency)
    {
        $this->selectedCurrency = $currency;
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.currency-selector');
    }
}
