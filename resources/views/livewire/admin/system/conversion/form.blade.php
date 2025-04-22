<x-admin.section.card>
    <div class="row">
        <x-livewire.input.select.currency name="rate.from_currency_id" value="{{ $rate?->from_currency_id }}" label="From Currency" width="3" />
        <x-livewire.input.select.currency name="rate.to_currency_id" value="{{ $rate?->to_currency_id }}" label="To Currency" width="3" />
        <x-livewire.input wire:model.debounce.300ms="rate.rate" label="Conversion Rate" width="2" type="number" step="0.01" />
        @if($this->canEstimateInverse())
            <x-livewire.input.checkbox wire:model="estimateInverse" label="Estimate inverse rate" width="2" />
        @else
            <x-livewire.input.checkbox wire:model="estimateInverse" label="Estimate inverse rate" width="2" disabled />
        @endif
        <div class="col-xl-1">
            Est. Rate: {{ sigfig(1 / ($rate->rate ?? 1)) }}
        </div>
        <div class="col-xl-1">
            <button class="btn btn-success" wire:click="save">Save</button>
        </div>
    </div>
</x-admin.section.card>