<x-admin.section.card>
    <x-slot:title>{{ $method->id !== null ? 'Update' : 'Create'}} Payment Method</x-slot:title>
    <div class="row">
        <x-livewire.input width="10" label="Name" wire:model="method.name" />
        <div class="col-2">
            <button class="btn btn-success my-auto" wire:click="save">{{ Icon::save() }} Save</button>
        </div>
    </div>
</x-admin.section.card>
