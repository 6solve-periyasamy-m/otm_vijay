@php /** @var \App\Models\Tour\Tour $tour */ @endphp

@extends('layout.form', ['action' => route('quotes.store-bespoke')])

@section('title', 'Create Bespoke Quote')

@section('form-body')
{{--    @include('partials.fields.selector.adder',
            ['name' => 'Lead Booker', 'field' => 'lead_booker_id', 'value' => null,
             'route' => 'customers', 'createRoute' => route('customers.create'), 'width' => 6])--}}
    <x-admin.input.selector.add name="customer_id" route="customers">
        <x-slot:create>{{ route('customers.create') }}</x-slot:create>
        Lead Traveller
    </x-admin.input.selector.add>
    <hr class="splitter"/>
    <x-admin.input type="date" name="from" width="4">Date From</x-admin.input>
    <x-admin.input type="date" name="to" width="4">Date To</x-admin.input>
    <x-admin.input type="date" name="final" width="4">Final Payment</x-admin.input>
    <hr class="splitter">
    <x-admin.input name="cost" width="6">Base Price Per Person</x-admin.input>
    <x-admin.input type="date" name="expires" width="6">Expiry Date</x-admin.input>
    <hr class="splitter">
    <x-admin.input.wysiwyg name="footer" width="6">Quote Footer</x-admin.input.wysiwyg>
    <x-admin.input.wysiwyg name="terms" width="6">Terms and Conditions</x-admin.input.wysiwyg>
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
