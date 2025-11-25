@can('create', \App\Models\Accommodation\RoomType::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'room-types', 'createRoute' => route('room-types.create'), 'required' => true]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'room-types', 'required' => true]) }}></x-livewire.input.select2>
@endif
