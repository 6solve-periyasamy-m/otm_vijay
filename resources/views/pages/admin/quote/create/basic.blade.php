@php /** @var \App\Models\Tour\Tour $tour */ @endphp

@extends('layout.form', ['action' => route('quotes.store-basic', ['tour' => $tour,])])

@section('title', 'Create Bespoke Quote')

@section('form-body')
    <x-admin.input.selector.add name="customer_id" route="customers" width="6">
        <x-slot:create>{{ route('customers.create') }}</x-slot:create>
        Lead Traveller
    </x-admin.input.selector.add>
    <x-admin.input type="date" name="expires" width="6">Expiry Date</x-admin.input>
    <x-admin.input.text-area name="internal_notes" width="6">Internal Notes</x-admin.input.text-area>
    <x-admin.input.text-area name="external_notes" width="6">External Notes</x-admin.input.text-area>
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
