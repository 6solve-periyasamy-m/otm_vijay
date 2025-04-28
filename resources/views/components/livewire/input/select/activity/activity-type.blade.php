@can('create', \App\Models\Activity\ActivityType::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'activity.type', 'createRoute' => 'activity-types.create', ]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'activity.type', ]) }}></x-livewire.input.select2>
@endif