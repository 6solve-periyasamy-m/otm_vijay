<x-admin.section.card>
    <x-slot:title>
        {{ __($contract?->id !== null ? 'supplier.contract.form.title.update' : 'supplier.contract.form.title.create') }}
    </x-slot:title>
    <div class="row">
        <x-livewire.input wire:model="contract.purchase_order_number" required width="5" label="{{__('supplier.contract.form.fields.order_number')}}" />
        <x-livewire.input wire:model="contract.reference_number" required width="5" label="{{__('supplier.contract.form.fields.reference_number')}}" />
        <x-livewire.input.checkbox wire:model="contract.confirmed" width="2" label="{{__('supplier.contract.form.fields.confirmed')}}" />

        <x-livewire.input wire:model="local_cost" wire:change="changeLocal" width="4" label="{{__('supplier.contract.form.fields.net_cost')}}" type="number" step="0.01" />
        <x-livewire.input wire:model="contract.agreed_exchange" wire:change="changeExchange" required width="4" label="{{__('supplier.contract.form.fields.exchange')}}" type="number" step="0.01" />
        <x-livewire.input wire:model="contract.total_cost" wire:change="changeGross" required width="4" label="{{__('supplier.contract.form.fields.total_cost')}}" type="number" step="0.01" />

        <x-livewire.input wire:model="contract.tax_rate" wire:change="changeTax" required width="6" label="{{__('supplier.contract.form.fields.tax_rate')}}" />
        <x-livewire.input wire:model="before_tax" disabled width="6" label="{{__('supplier.contract.form.fields.before_tax')}}" />
        <x-livewire.input.select2 name="contract.currency_id" required width="12" label="{{__('supplier.contract.form.fields.currency')}}" route="currencies" value="{{ $supplier->currency_id }}" />
        <x-livewire.input.text-area wire:model="contract.notes" label="{{__('supplier.contract.form.fields.notes')}}" />
        <button class="btn btn-primary" wire:click="save">Submit</button>
    </div>
</x-admin.section.card>