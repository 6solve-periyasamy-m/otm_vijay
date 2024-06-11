<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="category.name" label="Name" width="10" />
        <div class="col-xl-2">
            <button class="btn btn-success" wire:click="save">
                Save
            </button>
        </div>
    </div>
</x-admin.section.card>
