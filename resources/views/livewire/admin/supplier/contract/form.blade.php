<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">{{ __($contract?->id !== null ? 'supplier.contract.form.title.update' : 'supplier.contract.form.title.create') }}</h4>
        </div>
        <div class="row">
            <x-livewire.input wire:model="contract.purchase_order_number" required width="10" label="{{__('supplier.contract.form.fields.order_number')}}" />
            <x-livewire.input.checkbox wire:model="contract.confirmed" width="2" label="{{__('supplier.contract.form.fields.confirmed')}}" />
            <x-livewire.input wire:model="contract.total_cost" required width="6" label="{{__('supplier.contract.form.fields.total_cost')}}" />
            <x-livewire.input wire:model="contract.price_per_item" width="6" label="{{__('supplier.contract.form.fields.price_per_item')}}" />
            <x-livewire.input.select2 name="contract.currency_id" required width="8" label="{{__('supplier.contract.form.fields.currency')}}" route="currencies" value="{{ $supplier->currency_id }}" />
            <x-livewire.input wire:model.debounce.1000ms="contract.agreed_exchange" required width="4" label="{{__('supplier.contract.form.fields.exchange')}}" />
            <button class="btn btn-primary" wire:click="save">Submit</button>
        </div>
    </div>
</div>