<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="days" width="4" required label="Days" />
        <x-livewire.input wire:model="percentage" width="4" label="Percentage" />
        <div class="col-xl-4">
            <button class="btn btn-success" wire:click="save">
                Save
            </button>
        </div>
    </div>
</x-admin.section.card>