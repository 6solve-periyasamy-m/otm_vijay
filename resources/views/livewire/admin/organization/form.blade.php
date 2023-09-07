<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">{{ __($organization?->id !== null ? 'organization.form.edit' : 'organization.form.create') }}</h4>
        </div>
        <div class="row">
            <x-livewire.input wire:model="organization.name" required width="4" label="{{__('organization.form.fields.name')}}" />
            <x-livewire.input type="date" wire:model="organization.contact_email"  width="4" label="{{__('organization.form.fields.contact.email')}}" />
            <x-livewire.input type="date" wire:model="organization.contact_number"  width="4" label="{{__('organization.form.fields.contact.number')}}" />
            <x-livewire.input wire:model="organization.internal_notes" width="6" label="{{__('organization.form.fields.notes.internal')}}" />
            <x-livewire.input wire:model="organization.external_notes" width="6" label="{{__('organization.form.fields.notes.external')}}" />
        </div>
        <hr class="splitter" />
        <div class="row">
            <div class="@if(!$billingIsDelivery) col-xl-6 col-lg-6 @endif col-12 row">
                <x-livewire.input wire:model="delivery.address_line_1" width="6" label="{{__('organization.form.fields.address.delivery.line_1')}}" />
                <x-livewire.input wire:model="delivery.address_line_1" width="6" label="{{__('organization.form.fields.address.delivery.line_2')}}" />
                <x-livewire.input wire:model="delivery.address_line_1" width="6" label="{{__('organization.form.fields.address.delivery.town')}}" />
                <x-livewire.input wire:model="delivery.address_line_1" width="6" label="{{__('organization.form.fields.address.delivery.region')}}" />
                <x-livewire.input.select2 name="delivery.country_id" route="countries" width="6" label="{{__('organization.form.fields.address.delivery.country')}}" />
                <x-livewire.input wire:model="delivery.address_line_1" width="6" label="{{__('organization.form.fields.address.delivery.postcode')}}" />
            </div>
            @if(!$billingIsDelivery)
            <div class="col-xl-6 col-lg-6 col-12 row">
                <x-livewire.input wire:model="billing.address_line_1" width="6" label="{{__('organization.form.fields.address.billing.line_1')}}" />
                <x-livewire.input wire:model="billing.address_line_1" width="6" label="{{__('organization.form.fields.address.billing.line_2')}}" />
                <x-livewire.input wire:model="billing.address_line_1" width="6" label="{{__('organization.form.fields.address.billing.town')}}" />
                <x-livewire.input wire:model="billing.address_line_1" width="6" label="{{__('organization.form.fields.address.billing.region')}}" />
                <x-livewire.input.select2 name="billing.country_id" route="countries" width="6" label="{{__('organization.form.fields.address.billing.country')}}" />
                <x-livewire.input wire:model="billing.address_line_1" width="6" label="{{__('organization.form.fields.address.billing.postcode')}}" />
            </div>
            @endif
        </div>
        <button class="btn btn-primary" wire:click="save">Submit</button>
    </div>
</div>
