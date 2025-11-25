@can('create', \App\Models\Activity\SeatingMap::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'activity.seating-map', 'createForm' => 'admin.activity.seating-map.form', ]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'activity.seating-map', ]) }}></x-livewire.input.select2>
@endif