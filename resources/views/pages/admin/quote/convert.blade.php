@php
/**
 * @var \App\Models\Quote\Quote $quote
 * @var int $paying
 * @var int $travelling
 */
@endphp

@extends('layout.master')

@section('title', 'Conversion of Quote ' . $quote->reference)

@push('footer-stack')
    <script>
        class Customer { constructor(id, name) { this.id = id; this.name = name; }}
        function getCustomers(paying = false, count = 1) {
            return new Promise(resolve => {
                $.ajax({
                    url: '{{ route("api.quote.unknown-traveller", ["quote" => $quote,]) }}',
                    type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', paying: paying ? 1 : 0, count: count,}
                })
                .then(data => {
                    resolve(data.data.map(customer => new Customer(customer.id, customer.text)));
                })
                .catch(data => {
                    console.error(data);
                    alert('An error occurred. Please check the console for more information.')
                    resolve([]);
                });
            });
        }
        function getUnknownCustomer(selector, paying = false) {
            getCustomers(paying).then((customers) => {
                if (customers.length > 0) {
                    setCustomer($(selector), customers[0]);
                }
            });
        }
        function unknownAll(selector, paying = false) {
            let fields = $(selector);
            if (fields.length > 0) {
                let x = 0;
                getCustomers(paying, fields.length).then((customers) => {
                    if (customers.length >= fields.length) {
                        fields.each((index, element) => {
                            setCustomer($(element), customers[x]);
                            x++;
                        });
                    }
                });
            }
        }
        function setCustomer(selector, customer) {
            selector.append(new Option(customer.name, customer.id, true, true)).trigger('change');
        }
    </script>

@endpush

@section('content')
    <livewire:admin.quote.conversion :quote="$quote" :paying="$paying" :travelling="$travelling" />
@endsection
