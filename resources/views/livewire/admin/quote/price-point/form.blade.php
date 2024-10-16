<div class="row w-100">
    <x-livewire.input wire:model="pricePoint.quantity" label="Quantity" width="5" />
    <x-livewire.input wire:model="pricePoint.price_per_person" label="Price per Person" width="5" type="number" step="0.01" />
    <div class="col-2">
        <label></label>
        <button class="btn btn-success" wire:click="save">Save</button>
    </div>
</div>
