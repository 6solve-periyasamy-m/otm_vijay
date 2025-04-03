<x-admin.section.card>
    <x-slot:title>{{ $amenity->id !== null ? 'Update' : 'Create'}} Amenity</x-slot:title>
    <div class="row">
        <div class="col-4">
            <x-livewire.input label="Name" wire:model="amenity.name" />
        </div>
        @if($amenity->id !== null)
            <div class="col-3 d-flex align-items-center justify-content-center">
                <img src="{{ asset($amenity->image_url) }}" alt="{{$amenity->name}}" class="img-fluid" style="max-width: 100px; max-height: 100px;"/>
            </div>
        @endif
        <div class="col-4">
            <x-livewire.input type="file" wire:model="image" label="Image" />
        </div>
        <div class="col-1">
            <button class="btn btn-success my-auto" wire:click="save">{{ Icon::save() }} Save</button>
        </div>
    </div>
</x-admin.section.card>
