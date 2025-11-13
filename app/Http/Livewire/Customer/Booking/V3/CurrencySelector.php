<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Controllers\Customer\BookingV3Controller;
use Livewire\Component;

class CurrencySelector extends Component
{
    public string $selectedCurrency;
    public array $availableCurrencies = [];

    protected $listeners = ['setCurrencyExternally'];

    public function mount($currency = null)
    {
        $this->availableCurrencies = BookingV3Controller::ALLOWED_CURRENCIES;
        $this->selectedCurrency = $currency ?? $this->availableCurrencies[0];
        $this->emit('currencyUpdated', $this->selectedCurrency);
    }

    public function updatedSelectedCurrency($value)
    {
        $this->emitUp('currencyUpdated', $value);
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
