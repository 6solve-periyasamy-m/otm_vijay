@php /** @var \App\Models\Quote\Quote $quote */ @endphp

@extends('layout.form', ['action' => route('quotes.update', ['quote' => $quote,])])

@section('title', 'Create Bespoke Quote')

@section('form-body')
    <x-admin.input name="name" width="3" value="{{$quote->name}}">Name</x-admin.input>
    <x-admin.input.selector.add name="customer_id" route="customers" width="3" value="{{$quote->leadTraveller->customer_id}}">
        <x-slot:create>{{ route('customers.create') }}</x-slot:create>
        Lead Traveller
    </x-admin.input.selector.add>
    <x-admin.input.selector.standard name="brand_id" route="brands" value="{{$quote->brand_id}}" width="3">
        Quote Branding
    </x-admin.input.selector.standard>
    <x-livewire.input.select.tax-bracket name="tax_bracket_id" label="Tax Bracket" width="3"/>
    <x-livewire.input.select.organization name="organization_id" value="{{ $quote?->organization_id }}" label="Organization (Optional)" width="6" />
    <x-livewire.input.select.user name="consultant_id" value="{{ $quote?->consultant_id }}" label="Consultant (Optional)" width="6" clear="true" />
    <x-admin.input name="deposit" width="3" value="{{$quote?->deposit}}">Deposit</x-admin.input>
    <x-admin.input.checkbox name="percentage" value="{{$quote?->is_deposit_percentage}}" width="3">Is Percentage</x-admin.input.checkbox>
    <x-admin.input name="single_occupancy_surcharge" width="6" value="{{$quote->single_occupancy_surcharge}}">Single Occupancy Surcharge</x-admin.input>
    <x-admin.input.text-area name="description" value="{{$quote->description}}">Description</x-admin.input.text-area>
    <hr class="splitter"/>
    <x-admin.input.checkbox name="travelling" width="6" value="{{ $quote->leadTraveller->travelling }}" nofloat>Lead Travelling?</x-admin.input.checkbox >
    <x-admin.input.checkbox name="paying" width="6" value="{{ $quote->leadTraveller->paying }}" nofloat>Lead Paying?</x-admin.input.checkbox >
    <hr class="splitter">
    <x-admin.input type="date" name="from" width="3" value="{{ $quote->date_from->format('Y-m-d') }}">Date From</x-admin.input>
    <x-admin.input type="date" name="to" width="3" value="{{ $quote->date_to->format('Y-m-d') }}">Date To</x-admin.input>
    <x-admin.input type="date" name="final" width="3" value="{{ $quote->final_payment->format('Y-m-d') }}">Final Payment</x-admin.input>
    <x-admin.input type="date" name="expires" width="3" value="{{ $quote->expires->format('Y-m-d') }}">Expiry Date</x-admin.input>
    <hr class="splitter">
    <x-admin.input.wysiwyg name="footer" width="6" value="{!! $quote->invoice_footer !!}">Quote Footer</x-admin.input.wysiwyg>
    <x-admin.input.wysiwyg name="terms" width="6" value="{!! $quote->terms !!}">Terms and Conditions</x-admin.input.wysiwyg>
    <hr class="splitter">
    <x-admin.input.text-area name="internal_notes" width="6" value="{{ $quote->internal_notes }}">Internal Notes</x-admin.input.text-area>
    <x-admin.input.text-area name="external_notes" width="6" value="{{ $quote->external_notes }}">External Notes</x-admin.input.text-area>
    <hr class="splitter">
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
