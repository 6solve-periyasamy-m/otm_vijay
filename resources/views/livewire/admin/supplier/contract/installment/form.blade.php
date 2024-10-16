<x-admin.section.card>
    <x-slot:title>
        {{ __($installment?->id !== null ? 'supplier.contract.installment.form.title.update' : 'supplier.contract.installment.form.title.create') }}
    </x-slot:title>
    <div class="row">
        <x-livewire.input wire:model.defer.500ms="installment.amount" required width="6" label="{{__('supplier.contract.installment.form.fields.amount')}}" type="number" step="0.01" />
        <x-livewire.input type="date" wire:model.defer.500ms="installment.due" required width="6" label="{{__('supplier.contract.installment.form.fields.due')}}" />
        <button class="btn btn-primary" wire:click="save">Submit</button>
    </div>
</x-admin.section.card>