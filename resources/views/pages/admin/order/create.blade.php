@extends('layout.form', ['action' => route('orders.store'),])

@section('title', 'Create Order')

@push('footer-stack')
    <script type="text/javascript">
        class Customer { constructor(id, name) { this.id = id; this.name = name; }}
        function getCustomers(count = 1) {
            return new Promise(resolve => {
                return $.ajax({
                    url: '{{ route("api.order.unknown-traveller") }}',
                    type: 'post', data: {__api_token: '{{ Auth::user()->getCurrentToken()->token }}', 'count': count,}
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
        function addCustomer(customer = null) {
            let count = ($('.additional-traveller').length);
            let rd = render(template('additional-traveller'), {id: count});
            $('.customers-section').append(rd);
            $(document).ready(function () {
                let selector = $('#customers-' + count);
                selector.select2({
                    placeholder: "Please Select a Value",
                    ajax: {
                        url: '{{route("api.customers.select")}}',
                        data: function (params) {
                            return {
                                filter: params.term,
                                __api_token: '{{ Auth::user()->getCurrentToken()->token }}',
                            };
                        },
                        type: 'post',
                    }
                });
                if (customer !== null) {
                    setCustomer(selector, customer);
                }
            });
        }
        function generateUnknownCustomers(count = 1) {
            getCustomers(count).then(function (customers) {
                customers.map(customer => addCustomer(customer));
            });
        }
        function generateKnownTravellers(count = 1) {
            for (let x = 0; x < count; x++) {
                addCustomer();
            }
        }
        function setCustomer(selector, customer) {
            $(selector).append(new Option(customer.name, customer.id, true, true)).trigger('change');
        }
        function getUnknownCustomer(selector) {
            getCustomers().then(customers => {
                if (customers.length > 0) {
                    setCustomer(selector, customers[0]);
                } else {
                    alert('Failed to get unknown traveller')
                }
            });
        }
        function addUnknownTravellers() { generateUnknownCustomers(getTravellerCount()); }
        function addKnownTravellers() { generateKnownTravellers(getTravellerCount()); }
        function getTravellerCount() {
            let counter = $('.traveller-count');
            let count = counter.val();
            if (!isNaN(count) && count > 0) {
                return count;
            }
            counter.val(1);
            return 1;
        }
    </script>
@endpush

@push('footer-stack')
    <script type="text/template" data-template="additional-traveller">
        <div class="form-group col-12 col-xl-4">
            <label for="customers[${id}]-input">Additional Traveller</label>
            <div class="d-flex">
                <select class="form-control additional-traveller customers[${id}]-input" id="customers-${id}"
                        name="customers[${id}][id]"></select>
                <a href="{{ route('customers.create') }}" target="_blank"
                   class="btn btn-success d-inline ms-1">+</a>
                <a href="javascript:getUnknownCustomer('#customer-${id}')"
                   class="btn btn-info d-inline ms-1">{{ Icon::unknownCustomer() }}</a>
                @include('partials.fields.btn-checkbox', ['field' => 'customers[${id}][travelling]', 'icon' => 'plane', 'value' => 1,])
                @include('partials.fields.btn-checkbox', ['field' => 'customers[${id}][paying]', 'icon' => 'wallet', 'value' => 1,])
            </div>
        </div>
    </script>
@endpush

@section('form-body')
    @can('create', \App\Models\Tour\Tour::class)
        @include('partials.fields.selector.adder', ['name' => 'Tour', 'field' => 'tour_id', 'value' => 0,'route' => 'tours', 'createRoute' => route('tours.create'), 'width' => 4])
    @else
        @include('partials.fields.selector.default', ['name' => 'Tour', 'field' => 'tour_id', 'value' => 0, 'route' => 'tours', 'width' => 4])
    @endcan
    @can('create', \App\Models\Customer\Customer::class)
        @include('partials.fields.selector.traveller-adder',
                    ['name' => 'Lead Booker', 'id' => 'lead_selector', 'field' => 'lead_booker', 'value' => null,
                     'route' => 'customers', 'createRoute' => route('customers.create'), 'width' => 4])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Lead Booker', 'field' => 'lead_booker_id', 'value' => null,
                 'route' => 'customers', 'width' => 4])
    @endcan
    <x-livewire.input.select.currency name="currency_id" label="Currency" width="4" clearable />
    @include('partials.fields.text', ['name' => 'Deposit', 'field' => 'deposit', 'width' => 4 ])
    @include('partials.fields.text', ['name' => 'Booking Fee', 'field' => 'booking_fee', 'width' => 4 ])
    @include('partials.fields.datetime', ['name' => 'Ordered On', 'field' => 'ordered_on', 'width' => 2, 'value' => now(), ])
    <x-livewire.input.checkbox id="allow_backdated" name="allow_backdated" label="Allow Backdated" onchange="updateBackdated(this)" width="2" />
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'internal_notes', 'width' => 6 ])
    @include('partials.fields.textarea', ['name' => 'External Notes', 'field' => 'external_notes', 'width' => 6 ])
    @include('partials.fields.checkbox', ['name' => 'Send Booking Confirmation Email?', 'field' => 'should_invoice', 'value' => flag('order.manual.mail', false),])
    <livewire:admin.order.organization-selector />
    <hr class="splitter">
    <div class="customers-section row form-group">
        <div class="col-12 col-xl-9">
            <span class="font-bold fw-bold">Additional Travellers</span>
        </div>
        <div class="col-12 col-xl-1 form-group">
            <input type="number" name="traveller-count" class="traveller-count form-control" value="1" min="1" />
        </div>
        <div class="col-12 col-xl-1">
            <button class="btn btn-success" onclick="event.preventDefault();addUnknownTravellers();"><i class="icon-plus"></i> Unknown</button>
        </div>
        <div class="col-12 col-xl-1">
            <button class="btn btn-success" onclick="event.preventDefault();addKnownTravellers();"><i class="icon-plus"></i> Known</button>
        </div>
        <hr class="splitter">
    </div>
    <hr class="splitter">
    @include('partials.fields.submit')
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const orderedOn = document.getElementById('ordered_on-input');
        if (!orderedOn) return;
        orderedOn.min = appFormatDateTime(new Date());
    });
    function updateBackdated(obj) {
        const orderedOn = document.getElementById('ordered_on-input');
        if (obj.checked) {
            orderedOn.min = null;
        } else {
            orderedOn.min = appFormatDateTime(new Date());
        }
    }
</script>