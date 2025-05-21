<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="map.name" label="Name" width="4" />
        <x-livewire.input type="file" wire:model="image" label="Image" width="4" />
        <div class="col-xl-4">
            <button class="btn btn-success" wire:click="save">Save</button>
        </div>
    </div>
    {{-- Care about people's approval and you will be their prisoner. --}}
</x-admin.section.card>
