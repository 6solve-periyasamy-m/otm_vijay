@can('create', \App\Models\Activity\Seating::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'activity.seating', 'createForm' => 'admin.activity.seating.form', ]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'activity.seating', ]) }}></x-livewire.input.select2>
@endif