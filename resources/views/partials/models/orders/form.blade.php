@if(!isset($update))
    @push('header-stack')
        <script type="text/javascript">
            var customerCount = 0;
            function addCustomer() {
                let rd = render(template('additional-traveller'), {id: customerCount});
                $('.customers-section').append(rd);
                $(document).ready(function () {
                    $('#customers-' + customerCount).select2({
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
            function getUnknownCustomer(selector) {
                $.ajax({
                    url: '{{ route('api.order.unknown-traveller') }}',
                    type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }
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
    @push('footer-stack')
        <script type="text/template" data-template="additional-traveller">
            <div class="form-group col-12 col-xl-4">
                <label for="customers[${id}]-input">Additional Traveller</label>
                <div class="d-flex">
                    <select class="form-control customers[${id}]-input" id="customers-${id}" name="customers[${id}][id]"></select>
                    <a href="{{ route('customers.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    <a href="javascript:getUnknownCustomer('#customer-${id}')" class="btn btn-info d-inline ms-1"><x-icon icon="user" /></a>
                    @include('partials.fields.btn-checkbox', ['field' => 'customers[${id}][travelling]', 'icon' => 'plane', 'value' => 1,])
                    @include('partials.fields.btn-checkbox', ['field' => 'customers[${id}][paying]', 'icon' => 'wallet', 'value' => 1,])
                </div>
            </div>
        </script>
    @endpush
@endif
@if(!isset($update))
    @can('create', \App\Models\Tour\Tour::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Tour', 'field' => 'tour_id', 'value' => $tour_id ?? 0,
                     'route' => 'tours', 'createRoute' => route('tours.create'), 'width' => 6])
    @else
        @include('partials.fields.selector.default',
                  ['name' => 'Tour', 'field' => 'tour_id', 'value' => $tour_id ?? 0,
                   'route' => 'tours', 'width' => 6])
    @endcan
    @can('create', \App\Models\Customer\Customer::class)
        @include('partials.fields.selector.traveller-adder',
                    ['name' => 'Lead Booker', 'id' => 'lead_selector', 'field' => 'lead_booker', 'value' => null,
                     'route' => 'customers', 'createRoute' => route('customers.create'), 'width' => 6])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Lead Booker', 'field' => 'lead_booker_id', 'value' => null,
                 'route' => 'customers', 'width' => 6])
    @endcan
@endif
@include('partials.fields.text', ['name' => 'Deposit', 'field' => 'deposit', 'value' => $deposit ?? null, 'width' => 6 ])
@include('partials.fields.datetime', ['name' => 'Ordered On', 'field' => 'ordered_on', 'value' => $ordered_on ?? null, 'width' => 6 ])
@include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'internal_notes', 'value' => $internal_notes ?? null, 'width' => 6 ])
@include('partials.fields.textarea', ['name' => 'External Notes', 'field' => 'external_notes', 'value' => $external_notes ?? null, 'width' => 6 ])
@if(isset($update))
    @include('partials.fields.ckeditor', ['name' => 'Invoice Footer', 'field' => 'invoice_footer', 'value' => $order->invoice_footer, ])
@endif
@if(!isset($update))
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
