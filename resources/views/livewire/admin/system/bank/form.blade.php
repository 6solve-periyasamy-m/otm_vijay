<x-admin.section.card>
    <x-slot:title>
        {{ __($bank?->id !== null ? 'custom.bank.form.update' : 'custom.bank.form.create') }}
    </x-slot:title>
    <div class="row">
        <x-livewire.input wire:model="bank.name" required label="{{__('custom.bank.name')}}" />
        <x-livewire.input wire:model="bank.address_line_1" width="6" required label="{{__('custom.address.line-1')}}" />
        <x-livewire.input wire:model="bank.address_line_2" width="6" label="{{__('custom.address.line-2')}}" />
        <x-livewire.input wire:model="bank.town" width="6" label="{{__('custom.address.town')}}" />
        <x-livewire.input wire:model="bank.region" width="6" label="{{__('custom.address.region')}}" />
        <x-livewire.input wire:model="bank.country" width="6" required label="{{__('custom.address.country')}}" />
        <x-livewire.input wire:model="bank.postcode" width="6" label="{{__('custom.address.postcode')}}" />
        <button class="btn btn-primary" wire:click="save">Submit</button>
    </div>
</x-admin.section.card>

