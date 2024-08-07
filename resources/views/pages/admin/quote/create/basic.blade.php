@php /** @var \App\Models\Tour\Tour $tour */ @endphp

@extends('layout.form', ['action' => route('quotes.store-basic', ['tour' => $tour,])])

@section('title', 'Create Basic Quote for ' . $tour->name)

@section('form-body')
    <x-admin.input.selector.add name="customer_id" route="customers" width="3">
        <x-slot:create>{{ route('customers.create') }}</x-slot:create>
        Lead Traveller
    </x-admin.input.selector.add>
    <x-livewire.input.select.brand name="brand_id" label="Quote Branding" width="3" value="-1" />
    <x-admin.input type="date" name="expires" width="3" @if(setting('system.quote.expiry') !== null) value="{{now()->addDays(setting('system.quote.expiry'))}}" @endif>Expiry Date</x-admin.input>
    <x-admin.input name="single_occupancy_surcharge" width="3">Single Occupancy Surcharge</x-admin.input>
    <x-livewire.input.select.organization name="organization_id" label="Organization (Optional)" width="6"/>
    <x-livewire.input.select.tax-bracket name="tax_bracket_id" label="Tax Bracket" width="6"/>
    <hr class="splitter">
    <x-admin.input.checkbox name="travelling" value="1" width="6" nofloat>Lead Travelling?</x-admin.input.checkbox >
    <x-admin.input.checkbox name="paying" value="1" width="6" nofloat>Lead Paying?</x-admin.input.checkbox >
    <hr class="splitter">
    <x-admin.input.text-area name="internal_notes" width="6">Internal Notes</x-admin.input.text-area>
    <x-admin.input.text-area name="external_notes" width="6">External Notes</x-admin.input.text-area>
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
