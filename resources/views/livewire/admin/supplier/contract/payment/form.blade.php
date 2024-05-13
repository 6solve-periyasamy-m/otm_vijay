<x-admin.section.card>
    <x-slot:title>
        {{ __($payment?->id !== null ? 'supplier.contract.payment.form.title.update' : 'supplier.contract.payment.form.title.create') }}
    </x-slot:title>
    <div class="row">
        <x-livewire.input wire:model.defer.500ms="payment.amount" required width="3" label="{{__('supplier.contract.payment.form.fields.amount')}}" />
        <x-livewire.input type="datetime-local" wire:model.defer.500ms="payment.paid" required width="3" label="{{__('supplier.contract.payment.form.fields.paid')}}" />
        <x-livewire.input wire:model.defer.500ms="payment.exchange_rate" required width="3" label="{{__('supplier.contract.payment.form.fields.exchange_rate')}}" />
        <x-livewire.input wire:model="payment.notes" label="{{__('supplier.contract.payment.form.fields.notes')}}" />
        <button class="btn btn-primary" wire:click="save">Submit</button>
    </div>
</x-admin.section.card>