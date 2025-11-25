<x-admin.section.card>
    
    <div class="row">
        <x-livewire.input wire:model="frequentflyer.name" label="Name" width="10" />
        <div class="col-xl-2">
            <button class="btn btn-success w-50 mt-4" wire:click="save">
                Save
            </button>
        </div>
    </div>
</x-admin.section.card>
