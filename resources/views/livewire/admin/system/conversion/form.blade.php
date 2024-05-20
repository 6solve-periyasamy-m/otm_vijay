<x-admin.section.card>
    <div class="row">
        <x-livewire.input.select.currency name="rate.from_currency_id" value="{{ $rate?->from_currency_id }}" label="From Currency" width="3" />
        <x-livewire.input.select.currency name="rate.to_currency_id" value="{{ $rate?->to_currency_id }}" label="To Currency" width="3" />
        <x-livewire.input wire:model.debounce.300ms="rate.rate" label="Conversion Rate" width="3" />
        <div class="col-xl-3">
            <button class="btn btn-success" wire:click="save">Save</button>
        </div>
    </div>
</x-admin.section.card>