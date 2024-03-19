<x-admin.section.card>
    <x-slot:title>
        {{ __($organization?->id !== null ? 'organization.form.title.update' : 'organization.form.title.create') }}
    </x-slot:title>
    <div class="row">
        <x-livewire.input wire:model="organization.name" required width="3" label="{{__('organization.form.fields.name')}}" />
        <x-livewire.input wire:model="organization.contact_email"  width="3" label="{{__('organization.form.fields.contact.email')}}" />
        <x-livewire.input wire:model="organization.contact_number"  width="3" label="{{__('organization.form.fields.contact.number')}}" />
        <x-livewire.input wire:model="organization.commission"  width="3" label="{{__('organization.form.fields.commission')}}" />
        <x-livewire.input wire:model="organization.internal_notes" width="6" label="{{__('organization.form.fields.notes.internal')}}" />
        <x-livewire.input wire:model="organization.external_notes" width="6" label="{{__('organization.form.fields.notes.external')}}" />
    </div>
    <hr class="splitter" />
    <div class="row">
        <div class="@if(!$billingIsDelivery) col-xl-6 col-lg-6 @endif col-12 row">
            <h4 class="col-4 px-1 fw-bold">{{__('organization.form.fields.address.delivery.title')}}</h4>
            <x-livewire.input.checkbox width="8" wire:model="billingIsDelivery" label="{{__('organization.form.fields.address.clone')}}" />
            <hr class="splitter" />
            <x-livewire.input wire:model="delivery.address_line_1" width="6" label="{{__('organization.form.fields.address.delivery.line_1')}}" />
            <x-livewire.input wire:model="delivery.address_line_2" width="6" label="{{__('organization.form.fields.address.delivery.line_2')}}" />
            <x-livewire.input wire:model="delivery.town" width="6" label="{{__('organization.form.fields.address.delivery.town')}}" />
            <x-livewire.input wire:model="delivery.region" width="6" label="{{__('organization.form.fields.address.delivery.region')}}" />
            <x-livewire.input.select2 name="delivery.country_id" value="{{ $delivery->country_id }}" route="countries" width="6" label="{{__('organization.form.fields.address.delivery.country')}}" />
            <x-livewire.input wire:model="delivery.postcode" width="6" label="{{__('organization.form.fields.address.delivery.postcode')}}" />
        </div>
        @if(!$billingIsDelivery)
            <div class="col-xl-6 col-lg-6 col-12 row">
                <h4 style="padding-bottom: 0.35rem" class="col-12 px-1 fw-bold">{{__('organization.form.fields.address.billing.title')}}</h4>
                <hr class="splitter" />
                <x-livewire.input wire:model="billing.address_line_1" width="6" label="{{__('organization.form.fields.address.billing.line_1')}}" />
                <x-livewire.input wire:model="billing.address_line_2" width="6" label="{{__('organization.form.fields.address.billing.line_2')}}" />
                <x-livewire.input wire:model="billing.town" width="6" label="{{__('organization.form.fields.address.billing.town')}}" />
                <x-livewire.input wire:model="billing.region" width="6" label="{{__('organization.form.fields.address.billing.region')}}" />
                <x-livewire.input.select2 name="billing.country_id" value="{{ $billing->country_id }}" route="countries" width="6" label="{{__('organization.form.fields.address.billing.country')}}" />
                <x-livewire.input wire:model="billing.postcode" width="6" label="{{__('organization.form.fields.address.billing.postcode')}}" />
            </div>
        @endif
    </div>
    <button class="btn btn-primary" wire:click="save">Submit</button>
</x-admin.section.card>
