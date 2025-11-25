@can('create', \App\Models\Accommodation\BoardType::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'board-types', 'createRoute' => route('board-types.create'), 'required' => true]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'board-types', 'required' => true]) }}></x-livewire.input.select2>
@endif
