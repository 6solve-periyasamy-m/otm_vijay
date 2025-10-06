@extends('layout.master')

@section('title', 'View Order Customer')

@push('footer-stack')
    <script type="text/javascript">
        @if ($orderCustomer->is_travelling)
        function addActivityAddon() {
            let id = $('#activity_id-input').find(':selected').val()
            if (id != null) {
                $.post('{{ route('api.order.addon.add.activity') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $orderCustomer->id }}', 'activity_id': id})
                    .done(function () { location.reload();})
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function addFlightAddon() {
            let id = $('#flight_id-input').find(':selected').val()
            if (id != null) {
                $.post('{{ route('api.order.addon.add.flight') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $orderCustomer->id }}', 'flight_id': id})
                    .done(function () { location.reload();})
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function addTransportAddon() {
            let id = $('#transport_id-input').find(':selected').val()
            if (id != null) {
                $.post('{{ route('api.order.addon.add.transport') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $orderCustomer->id }}', 'transport_id': id})
                    .done(function () { location.reload();})
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function addMerchandiseAddon() {
            let id = $('#merchandise_id-input').find(':selected').val()
            if (id != null) {
                $.post('{{ route('api.order.addon.add.merchandise') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $orderCustomer->id }}', 'merchandise_id': id})
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) location.reload();
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function applyAccommodationUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.accommodation.upgrade') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) location.reload();
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function applyActivityUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.activity.upgrade') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) location.reload();
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
            console.log(upgrade_id);
            console.log(component_id);
        }
        function applyFlightUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.flight.upgrade') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) location.reload();
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        function applyTransportUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.order.transport.upgrade') }}',
                    { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) location.reload();
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
        @endif
    </script>
@endpush
@section('content')
    {{-- Header Details --}}
    <div class="otm-callout" id="header-details">
        <div class="row">
            <div class="col-12 col-xl-6">
                <p>Booking Reference</p>
                <h6 class="fw-bold">{{ $orderCustomer->order->booking_reference }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Tour</p>
                <h6 class="fw-bold">{{ $orderCustomer->order->tour->name }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Tour Date</p>
                <h6 class="fw-bold">{{ f_date($orderCustomer->order->tour->date_from) . " to " . f_date($orderCustomer->order->tour->date_to) }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Order Status</p>
                <h6 class="badge badge-{{ $orderCustomer->order->status->color() }} fw-bold">{{ $orderCustomer->order->status->description() }}</h6>
            </div>
            <div class="col-12">
                @can('update', \App\Models\Order\Order::class)
                    <a href="{{ route('orders.edit', ['order' => $orderCustomer->order,]) }}" class="btn btn-success">
                        {{ Icon::edit() }}
                        Edit Order
                    </a>
                @endcan
                <a href="{{ route('orders.view', ['order' => $orderCustomer->order,]) }}" class="btn btn-amber">
                    {{ Icon::home() }}
                    Return to Order
                </a>
            </div>
        </div>
    </div>
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;">

   @php
        $customerIds = $orderCustomers->pluck('id')->values();
        $currentIndex = $customerIds->search($orderCustomer->id);
        $prevId = $customerIds->get($currentIndex - 1);
        $nextId = $customerIds->get($currentIndex + 1);

        $prevCustomer = $prevId ? $orderCustomers->firstWhere('id', $prevId) : null;
        $nextCustomer = $nextId ? $orderCustomers->firstWhere('id', $nextId) : null;

        $prevCustomerName = $prevCustomer && $prevCustomer->customer 
            ? $prevCustomer->customer->first_name . ' ' . $prevCustomer->customer->last_name 
            : '';
        $nextCustomerName = $nextCustomer && $nextCustomer->customer 
            ? $nextCustomer->customer->first_name . ' ' . $nextCustomer->customer->last_name 
            : '';
    @endphp

    <div class="d-flex justify-content-between align-items-center pt-2 pb-md-3 pb-2 heading mb-3">
        <h2 class="fw-bold mb-0"><i class="fas fa-user me-2 text-primary"></i>Customer</h2>
        <div class="d-flex gap-2">
            @if ($prevCustomer)
                <a href="{{ route('order-customers.view', ['order' => $orderCustomer->order, 'orderCustomer' => $prevCustomer]) }}"
                class="btn btn-link text-decoration-none text-primary fw-semibold px-2"
                title="The previous customer - {{ $prevCustomerName }}">
                    <i class="fas fa-arrow-left me-1"></i> Previous
                </a>
            @endif
            @if ($nextCustomer)
                <a href="{{ route('order-customers.view', ['order' => $orderCustomer->order, 'orderCustomer' => $nextCustomer]) }}"
                class="btn btn-link text-decoration-none text-primary fw-semibold px-2"
                title="Next customer - {{ $nextCustomerName }}">
                    Next <i class="fas fa-arrow-right ms-1"></i>
                </a>
            @endif
        </div>
    </div>


    <div class="otm-callout">
        <div class="row">
            <!-- Customer Details -->
            @php
                $payingCount = $orderCustomer->order->orderCustomers->count();
                $customer = $orderCustomer->customer;
            @endphp            
            <div class="col-12">
                <h4 class="fw-bold">{{ $orderCustomer->customer->first_name }} {{ $orderCustomer->customer->middle_names ?? "" }} {{ $orderCustomer->customer->last_name }}</h4>
            </div>        
            <div class="col-xl-4">
                @if(in_array('email', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Email Address', 'value' => $customer->email_address, 'isLink' => true, 'linkPrefix' => 'mailto:', 'col' => 'col-4' ])
                @endif
                @if(in_array('mobile_number', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Phone Number', 'value' => $customer->mobile_number, 'isLink' => true, 'linkPrefix' => 'tel:', 'col' => 'col-4' ])
                @endif
                @if(in_array('date_of_birth', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Date of Birth', 'value' => f_date($customer->date_of_birth), 'col' => 'col-4' ])
                @endif
                @include('partials.fields.groupfields.field', ['label' => 'Insurance Policy', 'value' => $orderCustomer->policy_number ?? 'No Insurance Policy', 'col' => 'col-4' ])
            </div>
            <div class="col-xl-8">
                <div class="row">
                @if(in_array('home_address', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Street (Home Address)', 'value' => $customer->homeAddress->address_line_1, 'col' => 'col-xl-6' ])
                @endif
                @if(in_array('billing_address', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Street (Billing Address)', 'value' => $customer->billingAddress->address_line_1, 'col' => 'col-xl-6' ])
                @endif
                </div>
                <div class="row">
                @if(in_array('home_address', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Town (Home Address)', 'value' => $customer->homeAddress->region, 'col' => 'col-xl-6' ])
                @endif
                @if(in_array('billing_address', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Town (Billing Address)', 'value' => $customer->billingAddress->region, 'col' => 'col-xl-6' ])
                @endif
                </div>
                <div class="row">
                @if(in_array('home_address', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Country (Home Address)', 'value' => $customer->homeAddress->country, 'col' => 'col-xl-6' ])
                @endif
                @if(in_array('billing_address', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Country (Billing Address)', 'value' => $customer->billingAddress->country, 'col' => 'col-xl-6' ])
                @endif
                </div>
                <div class="row">
                @if(in_array('home_address', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Postcode (Home Address)', 'value' => $customer->homeAddress->postcode, 'col' => 'col-xl-6' ])
                @endif
                @if(in_array('billing_address', $customerFields))
                    @include('partials.fields.groupfields.field', ['label' => 'Postcode (Billing Address)', 'value' => $customer->billingAddress->postcode, 'col' => 'col-xl-6' ])
                @endif
                </div>  
            </div>
            @if(in_array('first_name', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'First Name', 'value' => $customer->first_name, 'col' => 'col-4' ])
            @endif
            @if(in_array('middle_names', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Middle Name', 'value' => $customer->middle_names, 'col' => 'col-4' ])
            @endif
            @if(in_array('last_name', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Last Name', 'value' => $customer->last_name, 'col' => 'col-4' ])
            @endif
            @if(in_array('passport_first_name', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Passport First Name', 'value' => $customer->passport_first_name , 'col' => 'col-4' ])
            @endif

            @if(in_array('passport_middle_names', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Passport Middle Name', 'value' => $customer->passport_middle_names, 'col' => 'col-4' ])
            @endif

            @if(in_array('passport_last_name', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Passport Last Name', 'value' => $customer->passport_last_name , 'col' => 'col-4' ])
            @endif

            @if(in_array('passport_number', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Passport Number', 'value' => $customer->passport_number, 'col' => 'col-4' ])
            @endif

            @if(in_array('passport_expiry_date', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Passport Expires', 'value' => f_date($customer->passport_expiry_date), 'col' => 'col-4' ])
            @endif

            @if(in_array('emergency_contact_name', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Contact Name (Emergency)', 'value' => $customer->emergency_contact_name , 'col' => 'col-4' ])
            @endif
            @if(in_array('emergency_contact_relationship', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Contact Relationship (Emergency)', 'value' => $customer->emergency_contact_relationship , 'col' => 'col-4' ])
            @endif
            @if(in_array('emergency_contact_telephone', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Contact telephone (Emergency)', 'value' => $customer->emergency_contact_telephone , 'isLink' => true, 'linkPrefix' => 'tel:', 'col' => 'col-4' ])
            @endif

            @if(in_array('loyalty_number', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Loyalty Number', 'value' => $customer->loyalty_number , 'col' => 'col-4' ])
            @endif
            @if(in_array('t_shirt_size', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'T Shirt Size', 'value' => $customer->t_shirt_size , 'col' => 'col-4' ])
            @endif
            @if(in_array('hat_size', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Hat Size', 'value' => $customer->hat_size , 'col' => 'col-4' ])
            @endif
            <!-- @if(in_array('registered', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Has Account', 'value' => $customer->registered , 'col' => 'col-4' ])
            @endif -->
            @if(in_array('dietary_notes', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Dietary Requirements', 'value' => $customer->dietary_notes, 'col' => 'col-4' ])
            @endif

            @if(in_array('mobility_notes', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Mobility Requirements', 'value' => $customer->mobility_notes, 'col' => 'col-4' ])
            @endif

            @if(in_array('internal_notes', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'Internal Customer Notes', 'value' => $customer->internal_notes, 'col' => 'col-4' ])
            @endif

            @if(in_array('external_notes', $customerFields))
                @include('partials.fields.groupfields.field', ['label' => 'External Customer Notes', 'value' => $customer->external_notes, 'col' => 'col-4' ])
            @endif
            @include('partials.fields.groupfields.field', ['label' => 'Internal Order Customer Notes', 'value' => $orderCustomer->internal_notes, 'col' => 'col-4' ])
            @include('partials.fields.groupfields.field', ['label' => 'External Order Customer Notes', 'value' => $orderCustomer->external_notes, 'col' => 'col-4' ])            
            <!-- End of the customer details -->
            <div class="col-12">
                @can('create', \App\Models\Order\Adjustment\OrderCustomerAdjustment::class)
                    <a href="{{ route('order-customer-adjustments.create', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, ]) }}"
                       class="btn btn-success mb-1">
                        {{ Icon::create() }}
                        Add Adjustment
                    </a>
                @endcan
                @can('update', \App\Models\Order\OrderCustomer::class)
                    <a href="{{ route('order-customers.edit', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, ]) }}"
                       class="btn btn-amber mb-1">
                        {{ Icon::edit() }}
                        Edit Order Customer
                    </a>
                @endcan
                @can('delete', \App\Models\Order\OrderCustomer::class)
                    @if($orderCustomer->id !== $orderCustomer->order->lead_booker_id)
                        <a href="#" onclick="$('#customer-delete').submit()"
                           class="btn btn-danger mb-1">{{ Icon::delete() }}Remove Customer</a>
                        <form action="{{ route('order-customers.delete', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer]) }}"
                              method="post" id="customer-delete">
                            @csrf
                        </form>
                    @endcan
                @endcan
                @can('read', \App\Models\Customer\Customer::class)
                    <a href="{{ route('customers.view', ['customer' => $orderCustomer->customer, ]) }}"
                       class="btn btn-info mb-1" target="_blank">
                        {{ Icon::customer() }}
                        View Customer
                    </a>
                @endcan
            </div>
        </div>
    </div>
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;">
    @if($orderCustomer->is_travelling || $orderCustomer->repository->hasComponents())
        <div class="heading pt-2 pb-md-3 pb-2">
            <h2 class="fw-bold">Tour Components</h2>
        </div>

        {{-- Components Section --}}
        <x-admin.section.card>
            <ul class="nav nav-pills otm-tab">
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">
                        {{ Icon::accommodation() }} Accommodation
                    </button>
                </li>
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                        {{ Icon::activity() }} Activities
                    </button>
                </li>
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                        {{ Icon::flight() }}
                        Flights
                    </button>
                </li>
                <li class="nav-item col-6 col-md-3">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">
                        {{ Icon::transport() }}
                        Transport
                    </button>
                </li>
            </ul>

            {{-- Tables Definition --}}
            <div id="tables" class="tab-content otm-tab-content">
                {{-- Accommodation Table --}}
                <div id="accommodation" role="tabpanel" class="tab-pane fade show active">
                    <div id="accommodation-new" class="d-flex justify-content-between mb-3 flex-wrap">
                        <span class="fw-bold">Accommodation components are managed through the <a href="{{ route('orders.occupancy', ['order' => $orderCustomer->order,]) }}">Occupancy Manager</a>.</span>
                    </div>
                    <div id="accommodation-details">
                        <table id="accommodation-table" class="datatable table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Room Type</th>
                                <th scope="col">Board Type</th>
                                <th scope="col">Shared With</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Purchase Price</th>
                                <th scope="col">Updated Date</th>
                                <th scope="col">Upgrades</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderAccommodation as $orderAccommodation)
                                <tr component="{{ $orderAccommodation->id }}">
                                    <td style="min-width: 200px">{{ f_datetime($orderAccommodation->tourComponent->inventory->check_in) }} to {{ f_datetime($orderAccommodation->tourComponent->inventory->check_out) }}</td>
                                    <td>{{ $orderAccommodation->tourComponent->inventory->accommodation->name }}</td>
                                    <td>{{ $orderAccommodation->tourComponent->inventory->roomType->name }}</td>
                                    <td>{{ $orderAccommodation->tourComponent->inventory->boardType->name }}</td>
                                    <td>{{ empty($orderAccommodation->group->getMembers($orderCustomer)) ? 'Not Shared' : $orderAccommodation->group->getMembers($orderCustomer) }}</td>
                                    <td>{{ $orderAccommodation->tourComponent->tour_component_type }}</td>
                                    <td>
                                        @if($orderAccommodation->tourComponent->tour_component_type == 'Included')
                                            {{ f_currency(0) }}
                                        @else
                                            {{ f_currency($orderAccommodation->cost) }}
                                        @endif
                                    </td>
                                    <td>{{ $orderAccommodation->tourComponent->inventory->repository->getPurchasePriceString() }}</td>
                                    <td>
                                        {{ fr_currency($orderAccommodation->tourComponent->inventory->purchase_price, $orderAccommodation->tourComponent->inventory->repository->getCurrency()) }}
                                        ({{ fr_currency($orderAccommodation->purchase_price) }} @includeWhen($orderAccommodation->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', []))
                                    </td>
                                    <td>{{ f_date($orderAccommodation->updated_at) }}</td>
                                    <td style="width: 20%">
                                        @if($orderAccommodation->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderAccommodation->tourComponent->repository->getUpgradeKeyMap()) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'accommodation_' . $orderAccommodation->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyAccommodationUpgrade("accommodation_' . $orderAccommodation->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => $orderAccommodation->tourComponent->repository->getUpgradeId(), 'options' => $orderAccommodation->tourComponent->repository->getUpgradeKeyMap(0, true),])
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @can('update', \App\Models\Order\Component\OrderAccommodation::class)
                                        <button onclick="openModal('admin.order.component.order-accommodation-form', {'component': {{$orderAccommodation->id}}})" class="btn btn-sm btn-outline-warning">
                                            {{ Icon::edit() }}
                                        </button>
                                        @endcan
                                        <form action="{{ route('orderAccommodationDelete', ['id' => $orderAccommodation->id,]) }}"
                                              method="post">
                                            @csrf
                                            <input type="hidden" name="redirect"
                                                   value="{{ route(Route::currentRouteName(), ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, ]) }}"/>
                                            <a href="#" onclick="this.parentNode.submit()"
                                               class="btn btn-outline-danger btn-sm">{{ Icon::delete() }}</a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    @if(!empty($orderCustomer->accommodation_notes))
                        <hr class="splitter">
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold">Accommodation Notes</h6>
                            <p>{{ $orderCustomer->accommodation_notes }}</p>
                        </div>
                    @endif
                </div>
                {{-- Activities Table --}}
                <div id="activities" role="tabpanel" class="tab-pane fade">
                    <div id="activities-new" class="d-flex justify-content-between mb-3 flex-wrap">
                        @include('partials.fields.selector.adder',
                            ['field' => 'activity_id', 'preselect' => false,
                            'fullRoute' => route('api.available-activities.select', ['orderCustomer' => $orderCustomer,]),
                            'createRoute' => '#', 'onclick' => 'addActivityAddon()', 'target' => ''])
                    </div>
                    <div id="activities-details">
                        <table id="activities-table" class="datatable table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Activity Type</th>
                                <th scope="col">Ticket Type</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Purchase Price</th>
                                <th scope="col">Updated Date</th>
                                <th scope="col">Upgrades</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderActivities as $orderActivity)
                                <tr component="{{ $orderActivity->id }}">
                                    <td style="min-width: 200px">{{ f_datetime($orderActivity->activity_inventory->starts_at) }} to {{ f_datetime($orderActivity->activity_inventory->ends_at) }}</td>
                                    <td>{{ $orderActivity->activity->name }}</td>
                                    <td>{{ $orderActivity->activity->activityType->name }}</td>
                                    <td>{{ $orderActivity->activity_inventory->ticketType->name }}</td>
                                    <td>{{ $orderActivity->tourComponent->tour_component_type }}</td>
                                    <td>
                                        @if($orderActivity->tourComponent->tour_component_type == 'Included')
                                            {{ f_currency(0) }}
                                        @else
                                            {{ f_currency($orderActivity->cost) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ fr_currency($orderActivity->tourComponent->inventory->purchase_price, $orderActivity->tourComponent->inventory->repository->getCurrency()) }}
                                        ({{ fr_currency($orderActivity->purchase_price) }} @includeWhen($orderActivity->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', []))
                                    </td>
                                    <td>{{ f_date($orderActivity->updated_at) }}</td>
                                    <td style="width: 20%">
                                        @if($orderActivity->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderActivity->tourComponent->repository->getUpgradeKeyMap(0, true)) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'activity_' . $orderActivity->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyActivityUpgrade("activity_' . $orderActivity->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => $orderActivity->tourComponent->repository->getUpgradeId(), 'options' => $orderActivity->tourComponent->repository->getUpgradeKeyMap(0, true),])
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @can('update', \App\Models\Order\Component\OrderActivity::class)
                                        <button onclick="openModal('admin.order.component.order-activity-form', {'component': {{$orderActivity->id}}})" class="btn btn-sm btn-outline-warning">
                                            {{ Icon::edit() }}
                                        </button>
                                        @endif
                                        <form action="{{ route('orderActivityDelete', ['id' => $orderActivity->id,]) }}"
                                              method="post">
                                            @csrf
                                            <input type="hidden" name="redirect"
                                                   value="{{ route(Route::currentRouteName(), ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]) }}"/>
                                            <a href="#" onclick="this.parentNode.submit()"
                                               class="btn btn-outline-danger btn-sm">{{ Icon::delete() }}</a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    @if(!empty($orderCustomer->activity_notes))
                        <hr class="splitter">
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold">Activity Notes</h6>
                            <p>{{ $orderCustomer->activity_notes }}</p>
                        </div>
                    @endif
                </div>
                {{-- Flights Table --}}
                <div id="flights" role="tabpanel" class="tab-pane fade">
                    <div id="flights-new" class="d-flex justify-content-between mb-3 flex-wrap">
                        @include('partials.fields.selector.adder',
                            ['field' => 'flight_id', 'preselect' => false,
                            'fullRoute' => route('api.available-flights.select', ['orderCustomer' => $orderCustomer,]),
                            'createRoute' => '#', 'onclick' => 'addFlightAddon()', 'target' => ''])
                    </div>
                    <div id="flights-details">
                        <table id="flights-table" class="datatable table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Flight Details</th>
                                <th scope="col">Travel Class</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Purchase Price</th>
                                <th scope="col">Updated Date</th>
                                <th scope="col">Upgrades</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderFlights as $orderFlight)
                                <tr component="{{ $orderFlight->id }}">
                                    <td style="min-width: 200px">{{ f_datetime($orderFlight->flight_inventory->departs_at) }} to {{ f_datetime($orderFlight->flight_inventory->arrives_at) }}</td>
                                    <td>{{ $orderFlight->flight_number }}</td>
                                    <td>{{ $orderFlight->flight->departureAirport->name }} to {{ $orderFlight->flight->arrivalAirport->name }}</td>
                                    <td>{{ $orderFlight->flight_inventory->travelClass->name }}</td>
                                    <td>{{ $orderFlight->flightInventoryTour->tour_component_type }}</td>
                                    <td>
                                        @if($orderFlight->tourComponent->tour_component_type == 'Included')
                                            {{ f_currency(0) }}
                                        @else
                                            {{ f_currency($orderFlight->cost) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ fr_currency($orderFlight->tourComponent->inventory->purchase_price, $orderFlight->tourComponent->inventory->repository->getCurrency()) }}
                                        ({{ fr_currency($orderFlight->purchase_price) }} @includeWhen($orderFlight->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', []))
                                    </td>
                                    <td>{{ f_date($orderFlight->updated_at) }}</td>
                                    <td style="width: 20%">
                                        @if($orderFlight->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderFlight->tourComponent->repository->getUpgradeKeyMap(0, true)) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'flight_' . $orderFlight->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyFlightUpgrade("flight_' . $orderFlight->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => $orderFlight->tourComponent->repository->getUpgradeId(), 'options' => $orderFlight->tourComponent->repository->getUpgradeKeyMap(0, true),])
                                            @endif
                                        @endif
                                    </td>
                                    <td class="actions">
                                        @can('update', \App\Models\Order\Component\OrderFlight::class)
                                        <button onclick="openModal('admin.order.component.order-flight-form', {'component': {{$orderFlight->id}}})" class="btn btn-sm btn-outline-warning">
                                            {{ Icon::edit() }}
                                        </button>
                                        @endcan
                                        <form style="display:inline-block;" action="{{ route('orderFlightDelete', ['id' => $orderFlight->id,]) }}"
                                              method="post">
                                            @csrf
                                            <input type="hidden" name="redirect"
                                                   value="{{ route(Route::currentRouteName(), ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]) }}"/>
                                            <a href="#" onclick="this.parentNode.submit()"
                                               class="btn btn-outline-danger btn-sm">{{ Icon::delete() }}</a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    @if(!empty($orderCustomer->flight_notes))
                        <hr class="splitter">
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold">Flight Notes</h6>
                            <p>{{ $orderCustomer->flight_notes }}</p>
                        </div>
                    @endif
                </div>
                {{-- Transports Table --}}
                <div id="transports" role="tabpanel" class="tab-pane fade">
                    <div id="transports-new" class="d-flex justify-content-between mb-3 flex-wrap">
                        @include('partials.fields.selector.adder',
                            ['field' => 'transport_id', 'preselect' => false,
                            'fullRoute' => route('api.available-transports.select', ['orderCustomer' => $orderCustomer,]),
                            'createRoute' => '#', 'onclick' => 'addTransportAddon()', 'target' => ''])
                    </div>
                    <div id="transports-details">
                        <table id="transports-table" class="datatable table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Transport Type</th>
                                <th scope="col">Transport Information</th>
                                <th scope="col">Travel Class</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Purchase Price</th>
                                <th scope="col">Updated Date</th>
                                <th scope="col">Upgrades</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderTransports as $orderTransport)
                                @php $inventory = $orderTransport->transport_inventory; @endphp
                                {{-- TODO: Should still show if it's in the order. --}}
                                {{-- @continue($inventory && $inventory->hasSufficientOccupancy($payingCount)) --}}
                                <tr component="{{ $orderTransport->id }}">
                                    <td style="min-width: 200px">{{ f_datetime($orderTransport->repository->getStartTime()) }} to {{ f_datetime($orderTransport->repository->getEndTime()) }}</td>
                                    <td>{{ $orderTransport->transport->name }}</td>
                                    <td>{{ $orderTransport->transport->transportType->name }}</td>
                                    <td>{{ $orderTransport->transport->departureAddress->name }} to {{ $orderTransport->transport->arrivalAddress->name }}</td>
                                    <td>{{ $orderTransport->transport_inventory->travelClass->name }}</td>
                                    <td>{{ $orderTransport->transportInventoryTour->tour_component_type }}</td>
                                    <td>
                                        @if($orderTransport->tourComponent->tour_component_type == 'Included')
                                            {{ f_currency(0) }}
                                        @else
                                            {{ f_currency($orderTransport->cost) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ fr_currency($orderFlight->tourComponent->inventory->purchase_price, $orderFlight->tourComponent->inventory->repository->getCurrency()) }}
                                        ({{ fr_currency($orderFlight->purchase_price) }} @includeWhen($orderFlight->estimated_purchase_price === null, 'partials.admin.order.component.epp-calculated', []))
                                    </td>
                                    <td>{{ f_date($orderTransport->updated_at) }}</td>
                                    <td style="width: 20%">
                                        @if($orderTransport->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderTransport->tourComponent->repository->getUpgradeKeyMap(0, true)) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'transport_' . $orderTransport->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyTransportUpgrade("transport_' . $orderTransport->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => $orderTransport->tourComponent->repository->getUpgradeId(), 'options' => $orderTransport->tourComponent->repository->getUpgradeKeyMap(0, true),])
                                            @endif
                                        @endif
                                    </td>
                                    <td class="actions">
                                        @can('update', \App\Models\Order\Component\OrderTransport::class)
                                        <button onclick="openModal('admin.order.component.order-transport-form', {'component': {{$orderTransport->id}}})" class="btn btn-sm btn-outline-warning">
                                            {{ Icon::edit() }}
                                        </button>
                                        @endcan
                                        <form style="display:inline-block;" action="{{ route('orderTransportDelete', ['id' => $orderTransport->id,]) }}"
                                              method="post">
                                            @csrf
                                            <input type="hidden" name="redirect"
                                                   value="{{ route(Route::currentRouteName(), ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]) }}"/>
                                            <a href="#" onclick="this.parentNode.submit()"
                                               class="btn btn-outline-danger btn-sm">{{ Icon::delete() }}</a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    @if(!empty($orderCustomer->transport_notes))
                        <hr class="splitter">
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold">Transport Notes</h6>
                            <p>{{ $orderCustomer->transport_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </x-admin.section.card>
        {{-- Merchandise Section --}}
        <x-admin.section.card>
            <x-slot:title>
                Merchandise
            </x-slot:title>
            <div id="merchandise-new" class="d-flex justify-content-between mb-3 flex-wrap">
                @include('partials.fields.selector.adder',
                            ['field' => 'merchandise_id', 'preselect' => false,
                            'fullRoute' => route('api.available-merchandise.select', ['orderCustomer' => $orderCustomer,]),
                            'createRoute' => '#', 'onclick' => 'addMerchandiseAddon()', 'target' => ''])
            </div>
            <div id="merchandise-details">
                <table id="merchandise-table" class="datatable table table-striped table-responsive-sm">
                    <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Cost</th>
                        <th scope="col">Component Type</th>
                        <th scope="col">Fulfilled</th>
                        <th scope="col">Updated Date</th>
                        <th scope="col" class="actions">Actions</th>
                    </tr>
                    </thead>
                    @foreach($orderCustomer->orderMerchandise()->with('tourComponent', 'tourComponent.inventory', 'tourComponent.inventory.component')->get() as $orderMerchandise)
                        <tr>
                            <td><img src="{{ $orderMerchandise->tourComponent->inventory->asset }}" class="image tiny"/>
                            </td>
                            <td>{{ $orderMerchandise->tourComponent->inventory->component->name }}
                                ({{ $orderMerchandise->tourComponent->inventory->variant->name }})
                                ({{ $orderMerchandise->tourComponent->inventory->size?->name ?? 'No Size'  }})
                            </td>
                            <td>{{ f_currency($orderMerchandise->tourComponent->tour_sales_price) }}</td>
                            <td>{{ $orderMerchandise->tourComponent->tour_component_type }}</td>
                            <td>{{ f_bool($orderMerchandise->fulfilled) }}</td>
                            <td>{{ f_date($orderMerchandise->updated_at) }}</td>
                            <td class="actions">
                                <a href="{{ route('merchandise.inventory.tour.order.fulfil', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, 'orderMerchandise' => $orderMerchandise]) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    {{ Icon::fulfil() }}
                                </a>
                                <a href="javascript:$('#m-{{$orderMerchandise->id}}-delete').submit()"
                                   class="btn btn-outline-danger btn-sm">{{ Icon::delete() }}</a>
                                <form action="{{ route('orderMerchandiseDelete', ['id' => $orderMerchandise->id,]) }}"
                                      method="post" id="m-{{$orderMerchandise->id}}-delete" class="d-none">
                                    @csrf
                                    <input type="hidden" name="redirect"
                                           value="{{ route(Route::currentRouteName(), ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]) }}"/>

                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </x-admin.section.card>
    @endif
    {{-- Adjustments Section --}}
    <x-admin.section.card>
        <x-slot:header>
            <h4 class="fw-bold">Customer Adjustments</h4>
            @can('create', \App\Models\Order\Adjustment\OrderCustomerAdjustment::class)
                <div class="pb-3 text-end">
                    <a href="{{ route('order-customer-adjustments.create', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer]) }}" class="btn btn-success text-white">
                        {{ Icon::create() }}
                        Add Adjustment
                    </a>
                </div>
            @endcan
        </x-slot:header>
        <div>
            <table class="datatable table table-striped" id="customer-adjustment-table">
                <thead>
                <tr>
                    <th scope="col">Amount</th>
                    <th scope="col">Reason</th>
                    <th scope="col">Date</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                @foreach($orderCustomer->adjustments as $adjustment)
                    <tr>
                        <td>{{ f_currency($adjustment->amount) }}</td>
                        <td>{{ $adjustment->reason }}</td>
                        <td>{{ f_date($adjustment->date) }}</td>
                        <td class="actions">
                            @can('update', \App\Models\Order\Adjustment\OrderCustomerAdjustment::class)
                                <a href="{{ route('order-customer-adjustments.edit', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}"
                                   class="btn btn-outline-primary btn-sm mb-1">{{ Icon::edit() }}</a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                                {{ Icon::edit() }}
                                            </span>
                            @endcan
                            @can('delete', \App\Models\Order\Adjustment\OrderCustomerAdjustment::class)
                                <a href="#" onclick="$('#oadjustment-{{$adjustment->id}}-delete').submit()"
                                   class="btn btn-outline-danger btn-sm mb-1">{{ Icon::delete() }}</a>
                                <form action="{{ route('order-customer-adjustments.delete', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}"
                                      method="post" id="oadjustment-{{$adjustment->id}}-delete">
                                    @csrf
                                </form>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                                {{ Icon::delete() }}
                                            </span>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </x-admin.section.card>
    {{-- Closing Container---}}
@endsection
