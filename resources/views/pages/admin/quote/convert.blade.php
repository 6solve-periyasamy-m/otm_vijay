@php /** @var \App\Models\Quote\Quote $quote */ @endphp

@extends('layout.form', ['action' => route('quotes.convert', ['quote' => $quote,])])

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

@section('form-body')
    <hr class="splitter">
    <div class="row form-group">
        <div class="col-12 col-xl-11">
            <span class="font-bold fw-bold">Non-Paying Travellers</span>
        </div>
        <div class="col-12 col-xl-1">
            <button class="btn btn-success" onclick="event.preventDefault();unknownAll($('.travelling'), false);">All Unknown</button>
        </div>
    </div>
    <hr class="splitter">
    @for($x = 1; $x <= $travelling; $x++)
        <x-admin.input.selector.quote-conversion name="travelling[{{ $x }}]" route="customers" width="6" paying="0">
            <x-slot:create>{{ route('customers.create') }}</x-slot:create>
            Non-paying Traveller {{ $x }}
        </x-admin.input.selector.quote-conversion>
    @endfor
    <hr class="splitter">
    <div class="row form-group">
        <div class="col-12 col-xl-11">
            <span class="font-bold fw-bold">Paying Travellers</span>
        </div>
        <div class="col-12 col-xl-1">
            <button class="btn btn-success" onclick="event.preventDefault();unknownAll($('.paying'), true);">All Unknown</button>
        </div>
    </div>
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
