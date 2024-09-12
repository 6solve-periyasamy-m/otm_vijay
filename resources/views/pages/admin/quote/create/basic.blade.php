@php /** @var \App\Models\Tour\Tour $tour */ @endphp

@extends('layout.form', ['action' => route('quotes.store-basic', ['tour' => $tour,])])

@section('title', 'Create Basic Quote for ' . $tour->name)

@section('form-body')
    <x-livewire.input.select.customer name="customer_id" width="3" label="Lead Traveller" />
    <x-livewire.input.select.brand name="brand_id" label="Quote Branding" width="3" />
    <x-livewire.input type="date" name="expires" width="3" value="{{\Settings::defaultQuoteExpiry()}}" label="Expiry Date"/>
    <x-livewire.input name="single_occupancy_surcharge" width="3" label="Single Occupancy Surcharge" />
    <x-livewire.input.select.organization name="organization_id" label="Organization (Optional)" width="6"/>
    <x-livewire.input.select.tax-bracket name="tax_bracket_id" label="Tax Bracket" width="6"/>
    <hr class="splitter">
    <x-livewire.input.checkbox name="travelling" value="1" width="6" label="Lead Travelling?" />
    <x-livewire.input.checkbox name="paying" value="1" width="6" label="Lead Paying?"/>
    <hr class="splitter">
    <x-livewire.input.text-area name="internal_notes" width="6" label="Internal Notes" />
    <x-livewire.input.text-area name="external_notes" width="6" label="External Notes" />
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
