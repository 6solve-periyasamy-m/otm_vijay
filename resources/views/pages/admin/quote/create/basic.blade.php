@php /** @var \App\Models\Tour\Tour $tour */ @endphp

@extends('layout.form', ['action' => route('quotes.store-basic', ['tour' => $tour,])])

@section('title', 'Create Basic Quote for ' . $tour->name)

@section('form-body')
    <x-livewire.input.select.brand name="brand_id" value="{{$tour->brand_id}}" label="Quote Branding" width="3" />
    <x-livewire.input type="date" name="expires" width="3" value="{{\Settings::defaultQuoteExpiry()}}" label="Expiry Date"/>
    <x-livewire.input.select.customer name="customer_id" width="3" label="Lead Traveller" />
    <div class="col-xl-3 row">
        <x-livewire.input.checkbox name="travelling" value="1" width="6" checked label="Lead Travelling?" />
        <x-livewire.input.checkbox name="paying" value="1" width="6" checked label="Lead Paying?"/>
    </div>
    <x-livewire.input name="single_occupancy_surcharge" width="4" label="Single Occupancy Surcharge" value="{{ $tour->single_occupancy_surcharge }}"/>
    <x-livewire.input.select.tax-bracket name="tax_bracket_id" value="{{ $tour->tax_bracket_id }}" label="Tax Bracket" width="3"/>
    <x-livewire.input type="date" name="final_payment" label="Final Payment" width="2" value="{{ old('final_payment', $tour->final_payment?->format('Y-m-d')) }}"/>
    <x-livewire.input name="deposit" label="Deposit" width="2" value="{{ old('deposit', $tour->deposit) }}"/>
    <input type="hidden" name="is_deposit_percentage" value="0">
    <x-livewire.input.checkbox name="is_deposit_percentage" label="Percentage?" width="1"  :checked="old('is_deposit_percentage', $tour->is_deposit_percentage) ? true : false"  value="1"/>
    <livewire:admin.quote.organization-selector />
    <hr class="splitter">
    <x-livewire.input.text-area name="internal_notes" width="6" label="Internal Notes" />
    <x-livewire.input.text-area name="external_notes" width="6" label="External Notes" />
    <input type="submit" class="btn btn-primary text-white" value="Submit">
@endsection
