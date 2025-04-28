@can('create', \App\Models\Activity\Session::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'activity.session', 'createForm' => 'admin.activity.session.form', ]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => 'activity.session', ]) }}></x-livewire.input.select2>
@endif