@php /** @var \App\Models\Quote\Quote $quote */ @endphp

@extends('layout.form', ['action' => route('quotes.convert', ['quote' => $quote,])])

@section('title', 'Conversion of Quote ' . $quote->reference)

@section('form-body')
    @for($x = 1; $x <= $travelling; $x++)
        <x-admin.input.selector.add name="travelling[{{ $x }}]" route="customers" width="6">
            <x-slot:create>{{ route('customers.create') }}</x-slot:create>
            Non-paying Traveller {{ $x }}
        </x-admin.input.selector.add>
    @endfor
    <hr class="splitter">
    @for($x = 1; $x <= $paying; $x++)
        <x-admin.input.selector.add name="paying[{{ $x }}]" route="customers" width="6">
            <x-slot:create>{{ route('customers.create') }}</x-slot:create>
            Paying Traveller {{ $x }}
        </x-admin.input.selector.add>
    @endfor
    <hr class="splitter">
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
