@can('create', \App\Models\Customer\LoyaltyNumberType::class)
    <x-livewire.input.select2 {{ $attributes->merge(['route' => $route ?? 'loyalty-number-type', 'createForm' => 'admin.customer.loyalty-number-type.form',]) }}></x-livewire.input.select2>
@else
    <x-livewire.input.select2 {{ $attributes->merge(['route' => $route ?? 'loyalty-number-type',]) }}></x-livewire.input.select2>
@endcan
