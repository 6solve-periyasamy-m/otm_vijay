@php /** @var \App\Models\Tour\Tour $tour */ @endphp

@extends('layout.form', ['action' => route('quotes.store-bespoke')])

@section('title', 'Create Bespoke Quote')

@section('form-body')
    <x-admin.input name="name" width="8">Name</x-admin.input>
    <x-admin.input.selector.add name="customer_id" route="customers" width="4">
        <x-slot:create>{{ route('customers.create') }}</x-slot:create>
        Lead Traveller
    </x-admin.input.selector.add>
    <x-admin.input name="deposit" width="6">Deposit</x-admin.input>
    <x-admin.input name="single_occupancy_surcharge" width="6">Single Occupancy Surcharge</x-admin.input>
    <x-admin.input.text-area name="description">Description</x-admin.input.text-area>
    <hr class="splitter"/>
    <x-admin.input.checkbox name="travelling" width="6" nofloat>Lead Travelling?</x-admin.input.checkbox >
    <x-admin.input.checkbox name="paying" width="6" nofloat>Lead Paying?</x-admin.input.checkbox >
    <hr class="splitter">
    <x-admin.input type="date" name="from" width="4">Date From</x-admin.input>
    <x-admin.input type="date" name="to" width="4">Date To</x-admin.input>
    <x-admin.input type="date" name="final" width="4">Final Payment</x-admin.input>
    <hr class="splitter">
    <x-admin.input name="cost" width="6">Base Price Per Person</x-admin.input>
    <x-admin.input type="date" name="expires" width="6">Expiry Date</x-admin.input>
    <hr class="splitter">
    <x-admin.input.wysiwyg name="footer" width="6">Quote Footer</x-admin.input.wysiwyg>
    <x-admin.input.wysiwyg name="terms" width="6">Terms and Conditions</x-admin.input.wysiwyg>
    <hr class="splitter">
    <x-admin.input.text-area name="internal_notes" width="6">Internal Notes</x-admin.input.text-area>
    <x-admin.input.text-area name="external_notes" width="6">External Notes</x-admin.input.text-area>
    <hr class="splitter">
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
