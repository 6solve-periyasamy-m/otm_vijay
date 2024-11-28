<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="type.name" label="Name" width="10" />
        <div class="col-xl-2">
            <button wire:click="save" class="btn btn-primary">
                Save
            </button>
        </div>
    </div>
</x-admin.section.card>
