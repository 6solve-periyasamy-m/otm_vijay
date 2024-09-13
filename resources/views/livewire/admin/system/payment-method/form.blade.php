<x-admin.section.card>
    <x-slot:title>{{ $method->id !== null ? 'Update' : 'Create'}} Payment Method</x-slot:title>
    <div class="row">
        <x-livewire.input label="Name" wire:model="method.name" width="7" />
        <x-livewire.input label="Fee (%)" wire:model="method.fee_percentage" width="3" />
        <div class="col-2">
            <button class="btn btn-success my-auto" wire:click="save">{{ Icon::save() }} Save</button>
        </div>
    </div>
</x-admin.section.card>
