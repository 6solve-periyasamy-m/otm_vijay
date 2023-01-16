@php /** @var \App\Models\Order\Order $order */ $order = $order ?? null; @endphp
@if($order === null)
    @push('header-stack')
        <script type="text/javascript">
            var customerCount = 0;
            const selector = `<div class="form-group col-12 col-xl-6"><label for="customer[%id%]-input">Additional Traveller</label><div class="d-flex"><select class="form-control customer[%id%]-input" id="customer-%id%" name="customers[%id%]"></select><a href="{{ route('customers.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a></div></div>`;
            function addCustomer() {
                $('.customers-section').append(selector.replaceAll('%id%', '' + customerCount));
                $(document).ready(function () {
                    console.log('#customer-' + customerCount)
                    $('#customer-' + customerCount).select2({
                        placeholder: "Please Select a Value",
                        ajax: {
                            url: '{{route('api.customers.select')}}',
                            data: function (params) {
                                return {
                                    filter: params.term,
                                    __api_token: '{{ Auth::user()->getCurrentToken()->token }}',
                                };
                            },
                            type: 'post',
                        }
                    });
                    customerCount++;
                });
            }
        </script>
    @endpush
@endif
@if($order === null)
    @can('create', \App\Models\Tour\Tour::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Tour', 'field' => 'tour_id', 'value' => 0,
                     'route' => 'tours', 'createRoute' => route('tours.create'), 'width' => 6])
    @else
        @include('partials.fields.selector.default',
                  ['name' => 'Tour', 'field' => 'tour_id', 'value' => 0,
                   'route' => 'tours', 'width' => 6])
    @endcan
    @can('create', \App\Models\Customer\Customer::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Lead Booker', 'field' => 'lead_booker_id', 'value' => null,
                     'route' => 'customers', 'createRoute' => route('customers.create'), 'width' => 6])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Lead Booker', 'field' => 'lead_booker_id', 'value' => null,
                 'route' => 'customers', 'width' => 6])
    @endcan
@endif
@include('partials.fields.text', ['name' => 'Deposit', 'field' => 'deposit', 'value' => $order?->deposit, 'width' => 4 ])
@include('partials.fields.text', ['name' => 'Booking Fee', 'field' => 'booking_fee', 'value' => $order?->booking_fee, 'width' => 4 ])
@include('partials.fields.datetime', ['name' => 'Ordered On', 'field' => 'ordered_on', 'value' => $order?->ordered_on, 'width' => 4 ])
@include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'internal_notes', 'value' => $order?->internal_notes, 'width' => 6 ])
@include('partials.fields.textarea', ['name' => 'External Notes', 'field' => 'external_notes', 'value' => $order?->external_notes, 'width' => 6 ])
@if($order !== null)
    @include('partials.fields.ckeditor', ['name' => 'Invoice Footer', 'field' => 'invoice_footer', 'value' => $order->invoice_footer, ])
@endif
@if($order === null)
<hr class="splitter">
<div class="customers-section row form-group">
    <div class="col-12 col-xl-10">
        <span class="font-bold fw-bold">Additional Travellers</span>
    </div>
    <div class="col-12 col-xl-2">
        <button class="btn btn-success" onclick="event.preventDefault();addCustomer();">Add Additional Traveller</button>
    </div>
</div>
<hr class="splitter">
@endif
@include('partials.fields.submit')
