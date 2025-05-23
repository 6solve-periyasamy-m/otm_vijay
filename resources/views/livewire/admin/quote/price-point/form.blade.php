<div class="row w-100">
    <x-livewire.input wire:model="quantity" label="Quantity" width="5" />
    <x-livewire.input wire:model="amount" label="Price per Person" width="5" type="number" step="0.01" />
    <div class="col-2">
        <label></label>
        <button class="btn btn-success" wire:click="save" title="Add price matrix">Save</button>
    </div>
</div>
