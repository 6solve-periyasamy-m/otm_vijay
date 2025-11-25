{{-- <div class="single">
    <p>Select Currency</p>
    <select wire:model.lazy="selectedCurrency">
        @foreach($availableCurrencies as $currency)
            <option value="{{ $currency }}">{{ $currency }}</option>
        @endforeach
    </select>
</div> --}}

<div class="single">
    <p>Currency</p>
    <div class="custom-currency-display">
        {{ $selectedCurrency }}
    </div>
</div>