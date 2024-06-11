@can('create', \App\Models\Accommodation\BoardType::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'board-types', 'createRoute' => 'board-types.create', ]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'board-types', ]) }}></x-livewire.input.select2>
@endif