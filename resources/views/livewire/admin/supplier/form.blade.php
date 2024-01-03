<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">{{ __($supplier?->id !== null ? 'supplier.form.title.update' : 'supplier.form.title.create') }}</h4>
        </div>
        <div class="row">
            <x-livewire.input wire:model="supplier.name" required label="{{__('supplier.form.fields.name')}}" />
            <x-livewire.input wire:model="supplier.website" width="4" label="{{__('supplier.form.fields.website')}}" />
            <x-livewire.input wire:model="supplier.telephone" width="4" label="{{__('supplier.form.fields.telephone')}}" />
            <x-livewire.input wire:model="supplier.email" width="4" label="{{__('supplier.form.fields.email')}}" />
            <x-livewire.input.select2 name="supplier.currency_id" width="8" label="{{__('supplier.form.fields.currency')}}" route="currencies" value="{{ $supplier->currency_id }}" />
            <x-livewire.input wire:model="supplier.agreed_exchange" width="4" label="{{__('supplier.form.fields.exchange')}}" />
            <hr class="splitter" />
            <x-livewire.input wire:model="address.address_line_1" required width="6" label="{{__('supplier.form.fields.address.line-1')}}" />
            <x-livewire.input wire:model="address.address_line_2" width="6" label="{{__('supplier.form.fields.address.line-2')}}" />
            <x-livewire.input wire:model="address.town" width="6" label="{{__('supplier.form.fields.address.town')}}" />
            <x-livewire.input wire:model="address.region" width="6" label="{{__('supplier.form.fields.address.region')}}" />
            <x-livewire.input.select2 name="address.country_id" required width="6" label="{{__('supplier.form.fields.address.country')}}" route="countries" value="{{ $address->country_id }}" />
            <x-livewire.input wire:model="address.postcode" width="6" label="{{__('supplier.form.fields.address.postcode')}}" />
            <hr class="splitter" />
            <x-livewire.input.text-area wire:model="supplier.notes" label="{{__('supplier.form.fields.notes')}}" />
            <button class="btn btn-primary" wire:click="save">Submit</button>
        </div>
    </div>
</div>
