@can('create', \App\Models\Tour\Event::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => $route ?? 'events', 'createRoute' => route('events.create'),]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => $route ?? 'events',]) }}></x-livewire.input.select2>
@endcan
