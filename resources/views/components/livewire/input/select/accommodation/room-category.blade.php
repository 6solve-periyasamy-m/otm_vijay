@can('create', \App\Models\Accommodation\RoomCategory::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'room-categories', 'createForm' => 'admin.accommodation.room-category.form', ]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'room-categories', ]) }}></x-livewire.input.select2>
@endif