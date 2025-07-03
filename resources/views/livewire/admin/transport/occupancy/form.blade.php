<x-admin.section.card>
    
    <div class="row">
        <x-livewire.input wire:model="occupancy.name" label="Name" width="6" />
        <x-livewire.input wire:model="occupancy.maximum_occupancy" label="Maximum Occupancy" width="4" />
        <div class="col-xl-2">
            <button class="btn btn-success" wire:click="save">
                Save
            </button>
        </div>
    </div>
</x-admin.section.card>
