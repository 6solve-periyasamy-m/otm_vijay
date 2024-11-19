<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="component.cost" label="Cost" width="5" />
        <x-livewire.input wire:model="component.flight_number_override" label="Flight Number Override" width="5" />
        <div class="col-xl-2">
            <button wire:click="save" class="btn btn-success">
                Save Changes
            </button>
        </div>
    </div>
</x-admin.section.card>
