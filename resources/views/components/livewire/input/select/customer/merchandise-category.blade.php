@can('create', \App\Models\Customer\MerchandiseCategory::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => $route ?? 'merchandise-category', 'createForm' => 'admin.customer.merchandise-category.form',]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => $route ?? 'merchandise-category',]) }}></x-livewire.input.select2>
@endcan
