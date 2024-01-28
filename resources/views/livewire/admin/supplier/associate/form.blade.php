<x-admin.section.card>
    <x-slot:title>
        <h4 class="fw-bold">{{ __($associate?->id !== null ? 'supplier.associate.form.title.update' : 'supplier.associate.form.title.create') }}</h4>
    </x-slot:title>
    <div class="row">
        <x-livewire.input wire:model="associate.name" required width="6" label="{{__('supplier.associate.form.fields.name')}}" />
        <x-livewire.input wire:model="associate.job_title" width="6" label="{{__('supplier.associate.form.fields.job')}}" />
        <x-livewire.input wire:model="associate.email" width="4" label="{{__('supplier.associate.form.fields.email')}}" />
        <x-livewire.input wire:model="associate.primary_phone" width="4" label="{{__('supplier.associate.form.fields.primary_phone')}}" />
        <x-livewire.input wire:model="associate.alternative_phone" width="4" label="{{__('supplier.associate.form.fields.alternative_phone')}}" />
        <x-livewire.input wire:model="associate.notes" label="{{__('supplier.associate.form.fields.notes')}}" />
        <button class="btn btn-primary" wire:click="save">Submit</button>
    </div>
</x-admin.section.card>
