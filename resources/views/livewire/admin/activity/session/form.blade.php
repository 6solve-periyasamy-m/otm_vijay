<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="session.name" width="2" label="Name" required />
        <x-livewire.input wire:model="session.description" width="8" label="Description" />
        <div class="col-2">
            <button class="btn btn-primary" wire:click="save">Save</button>
        </div>
    </div>
</x-admin.section.card>