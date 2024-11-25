<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="component.cost" label="Cost (Non-Included)" width="3" />
        <x-livewire.input wire:model="component.estimated_purchase_price" label="Purchase Price" width="3" />
        <x-livewire.input wire:model="component.flight_number_override" label="Flight Number Override" width="3" />
        <div class="col-xl-3">
            <button wire:click="save" class="btn btn-success">
                Save Changes
            </button>
        </div>
    </div>
</x-admin.section.card>
