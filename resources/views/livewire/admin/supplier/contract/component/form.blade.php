<x-admin.section.card>
    <x-slot:title>
        Update Component
    </x-slot:title>
    <div class="row">
        <x-livewire.input wire:model="component.quantity" required width="5" label="Quantity" />
        <x-livewire.input wire:model="component.cost_per_unit" required width="5" label="Cost Per Unit" />
        <button class="btn btn-primary col-xl-2" wire:click="save">Submit</button>
    </div>
</x-admin.section.card>
