<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="type.name" label="Name" width="8" />
        <div class="col-4">
            <button wire:click="save" class="btn btn-primary">
                {{Icon::save()}} Save
            </button>
        </div>
    </div>
</x-admin.section.card>