<x-admin.section.card>
    <x-slot:title>{{ $sales ? 'Sales' : 'Internal' }} Conversion Rate</x-slot:title>
    <div class="row">
        <x-livewire.input.select.currency name="rate.from_currency_id" value="{{ $rate?->from_currency_id }}" label="From Currency" width="2" />
        <x-livewire.input.select.currency name="rate.to_currency_id" value="{{ $rate?->to_currency_id }}" label="To Currency" width="2" />
        <x-livewire.input wire:model.debounce.300ms="rate.rate" label="Conversion Rate" width="2" type="number" step="0.01" />
        @if($this->canEstimateInverse())
            <x-livewire.input.checkbox wire:model="estimateInverse" label="Estimate inverse rate" width="2" />
        @else
            <x-livewire.input.checkbox wire:model="estimateInverse" label="Estimate inverse rate" width="2" disabled />
        @endif
        <div class="col-xl-2">
            Est. Rate: {{ $this->getEstimatedRate() }}
            <br />
            @if($this->getInverseRateObject() !== null)
                Will Overwrite
            @else
                Will Create New
            @endif
        </div>
        <div class="col-xl-2">
            <button class="btn btn-success" wire:click="save">Save</button>
        </div>
    </div>
</x-admin.section.card>