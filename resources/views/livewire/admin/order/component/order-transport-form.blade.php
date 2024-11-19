<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="component.cost" label="Cost" width="3" />
        <x-livewire.input wire:model="component.departs_at_time_override" type="time" label="Departs At Time Override" width="3" />
        <x-livewire.input wire:model="component.arrives_at_time_override" type="time" label="Arrives At Time Override" width="3" />
        <div class="col-xl-3">
            <button wire:click="save" class="btn btn-success">
                Save Changes
            </button>
        </div>
    </div>
</x-admin.section.card>
