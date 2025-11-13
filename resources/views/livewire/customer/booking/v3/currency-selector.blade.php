<div class="single">
    <p>Select Currency</p>
    <select wire:model="selectedCurrency">
        @foreach($availableCurrencies as $currency)
            <option value="{{ $currency }}">{{ $currency }}</option>
        @endforeach
    </select>
</div>
