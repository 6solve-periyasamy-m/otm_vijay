@php /** @var \App\Models\Tour\Tour $tour */ @endphp

@extends('layout.form', ['action' => route('quotes.store-basic', ['tour' => $tour,])])

@section('title', 'Create Basic Quote for ' . $tour->name)

@section('form-body')
    <x-admin.input.selector.add name="customer_id" route="customers" width="3">
        <x-slot:create>{{ route('customers.create') }}</x-slot:create>
        Lead Traveller
    </x-admin.input.selector.add>
    <x-admin.input.selector.standard name="brand_id" route="brands" width="3">
        Quote Branding
    </x-admin.input.selector.standard>
    <x-admin.input type="date" name="expires" width="3">Expiry Date</x-admin.input>
    <x-admin.input name="single_occupancy_surcharge" width="3">Single Occupancy Surcharge</x-admin.input>
    <x-livewire.input.select.organization name="organization_id" label="Organization (Optional)" />
    <hr class="splitter">
    <x-admin.input.checkbox name="travelling" value="1" width="6" nofloat>Lead Travelling?</x-admin.input.checkbox >
    <x-admin.input.checkbox name="paying" value="1" width="6" nofloat>Lead Paying?</x-admin.input.checkbox >
    <hr class="splitter">
    <x-admin.input.text-area name="internal_notes" width="6">Internal Notes</x-admin.input.text-area>
    <x-admin.input.text-area name="external_notes" width="6">External Notes</x-admin.input.text-area>
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
