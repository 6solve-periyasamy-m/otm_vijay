<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">Update Component</h4>
        </div>
        <div class="row">
            <x-livewire.input wire:model="component.quantity" required width="5" label="Quantity" />
            <x-livewire.input wire:model="component.cost_per_unit" required width="5" label="Cost Per Unit" />
            <button class="btn btn-primary col-xl-2" wire:click="save">Submit</button>
        </div>
    </div>
</div>
