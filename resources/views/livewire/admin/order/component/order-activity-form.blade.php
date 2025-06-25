<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="component.cost" label="Cost (Non-Included)" width="4" />
        <x-livewire.input wire:model="component.estimated_purchase_price" label="Purchase Price" width="4" />
        <div class="col-xl-4">
            <button wire:click="save" class="btn btn-success">
                Save Changes
            </button>
        </div>
    </div>
</x-admin.section.card>
