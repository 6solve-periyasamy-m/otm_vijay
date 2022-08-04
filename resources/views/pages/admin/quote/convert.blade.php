@php /** @var \App\Models\Quote\Quote $quote */ @endphp

@extends('layout.form', ['action' => route('quotes.convert', ['quote' => $quote,])])

@section('title', 'Conversion of Quote ' . $quote->reference)

@push('header-stack')
    <script>
        function getUnknownCustomer(selector, paying) {
            console.log($(selector));
            $.ajax({
                url: '{{ route('api.quote.unknown-traveller', ['quote' => $quote,]) }}',
                type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', paying: paying }
            })
            .then(function (data) {
                $(selector).append(new Option(data.text, data.id, true, true)).trigger('change');
                $(selector).trigger({
                    type: 'select2:select',
                    params: { data: data, }
                });
            });
        }
    </script>

@endpush

@section('form-body')
    @for($x = 1; $x <= $travelling; $x++)
        <x-admin.input.selector.quote-conversion name="travelling[{{ $x }}]" route="customers" width="6" paying="0">
            <x-slot:create>{{ route('customers.create') }}</x-slot:create>
            Non-paying Traveller {{ $x }}
        </x-admin.input.selector.quote-conversion>
    @endfor
    <hr class="splitter">
    @for($x = 1; $x <= $paying; $x++)
        <x-admin.input.selector.quote-conversion name="paying[{{ $x }}]" route="customers" width="6" paying="1">
            <x-slot:create>{{ route('customers.create') }}</x-slot:create>
            Paying Traveller {{ $x }}
        </x-admin.input.selector.quote-conversion>
    @endfor
    <hr class="splitter">
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
