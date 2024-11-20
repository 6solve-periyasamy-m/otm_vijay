<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="component.cost" label="Cost (Non-Included)" width="2" />
        <x-livewire.input wire:model="component.estimated_purchase_price" label="Purchase Price" width="2" />
        <x-livewire.input wire:model="component.departs_at_time_override" type="time" label="Departs At Time Override" width="3" />
        <x-livewire.input wire:model="component.arrives_at_time_override" type="time" label="Arrives At Time Override" width="3" />
        <div class="col-xl-2">
            <button wire:click="save" class="btn btn-success">
                Save Changes
            </button>
        </div>
    </div>
</x-admin.section.card>
