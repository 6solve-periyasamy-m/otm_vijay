@can('create', \App\Models\Accommodation\RoomType::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'room-types', 'createRoute' => route('room-types.create'), ]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'room-types', ]) }}></x-livewire.input.select2>
@endif
