@extends('layout.master')

@section('title', 'View Order Customer')

@section('header-script')
<script type="text/javascript">
function addAccommodationAddon() {
    let id = $('#accommodation_id-input').find(':selected').val()
    if (id != null) {
        $.post('{{ route('api.order.addon.add.accommodation') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $orderCustomer->id }}', 'accommodation_id': id})
            .done(function (xhr, textStatus, errorThrown) {
                if (xhr.success) location.reload();
                else alert(xhr.message);
            })
            .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
    }
}

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
$(document).ready( function () {
    $('#accommodation-table').DataTable({fixedHeader: true});
    $('#activities-table').DataTable({fixedHeader: true});
    $('#flights-table').DataTable({fixedHeader: true});
    $('#transports-table').DataTable({fixedHeader: true});
    $('#merchandise-table').DataTable({fixedHeader: true});
    $('#customer-adjustment-table').DataTable({fixedHeader: true});
});
</script>
@endsection
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
            <h6 class="fw-bold">{{ StringFormatter::formatDate($orderCustomer->order->tour->date_from) . " to " . StringFormatter::formatDate($orderCustomer->order->tour->date_to) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Order Status</p>
            <h6 class="badge badge-{{ $orderCustomer->order->getStatus()['color'] }} fw-bold">{{ $orderCustomer->order->getStatus()['status'] }}</h6>
        </div>
        <div class="col-12">
            @can('update', \App\Models\Order::class)
                <a href="{{ route('orders.edit', ['order' => $orderCustomer->order,]) }}" class="btn btn-success">
                    <i class="icon-note"></i>
                    Edit Order
                </a>
            @endcan
            <a href="{{ route('orders.view', ['order' => $orderCustomer->order,]) }}" class="btn btn-amber">
                <i class="icon-home"></i>
                Return to Order
            </a>
        </div>
    </div>
</div>
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;">
<div class="heading pt-2 pb-md-3 pb-2">
    <h2 class="fw-bold">Customer</h2>        
</div>
<div class="otm-callout">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold">{{ $orderCustomer->customer->first_name }} {{ $orderCustomer->customer->middle_names ?? "" }} {{ $orderCustomer->customer->last_name }}</h4>
        </div>
        <div class="col-xl-4">
            <p>Date of Birth</p>
            <h6 class="fw-bold">{{ StringFormatter::formatDate($orderCustomer->customer->date_of_birth) }}</h6>
            <p>Passport Number</p>
            <h6 class="fw-bold">{{ $orderCustomer->customer->passport_number }}</h6>
            <p>Password Expire Date</p>
            <h6 class="fw-bold">{{ $orderCustomer->customer->passport_expiry_date }}</h6>
        </div>
        <div class="col-xl-8">
            @if ($orderCustomer->customer->homeAddress->address_line_1 != '' || $orderCustomer->customer->billingAddress->address_line_1 != '')
            <div class="row">
                <div class="col-xl-6">
                    <p>Street (Home Address)</p>
                    <h6 class="fw-bold">{{ $orderCustomer->customer->homeAddress->address_line_1 }}</h6>
                </div>
                <div class="col-xl-6">
                    <p>Street (Billing Address)</p>
                    <h6 class="fw-bold">{{ $orderCustomer->customer->billingAddress->address_line_1 }}</h6>
                </div>
            </div>
            @endif
            @if ($orderCustomer->customer->homeAddress->region != '' || $orderCustomer->customer->billingAddress->region != '')
            <div class="row">
                <div class="col-xl-6">
                    <p>Town (Home Address)</p>
                    <h6 class="fw-bold">{{ $orderCustomer->customer->homeAddress->region }}</h6>
                </div>
                <div class="col-xl-6">
                    <p>Town (Billing Address)</p>
                    <h6 class="fw-bold">{{ $orderCustomer->customer->billingAddress->region }}</h6>
                </div>
            </div>
            @endif
            @if ($orderCustomer->customer->homeAddress->country != '' || $orderCustomer->customer->billingAddress->country != '')
            <div class="row">
                <div class="col-xl-6">
                    <p>Country (Home Address)</p>
                    <h6 class="fw-bold">{{ $orderCustomer->customer->homeAddress->country }}</h6>
                </div>
                <div class="col-xl-6">
                    <p>Country (Billing Address)</p>
                    <h6 class="fw-bold">{{ $orderCustomer->customer->billingAddress->country }}</h6>
                </div>
            </div>
            @endif
            @if ($orderCustomer->customer->homeAddress->postcode != '' || $orderCustomer->customer->billingAddress->postcode != '')
            <div class="row">
                <div class="col-xl-6">
                    <p>Postcode (Home Address)</p>
                    <h6 class="fw-bold">{{ $orderCustomer->customer->homeAddress->postcode }}</h6>
                </div>
                <div class="col-xl-6">
                    <p>Postcode (Billing Address)</p>
                    <h6 class="fw-bold">{{ $orderCustomer->customer->billingAddress->postcode }}</h6>
                </div>
            </div>
            @endif
        </div>
        <div class="col-12">
            @can('create', \App\Models\OrderCustomerAdjustment::class)
            <a href="{{ route('order-customer-adjustments.create', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, ]) }}" class="btn btn-success mb-1">
                <i class="icon-plus"></i>
                Add Adjustment
            </a>
            @endcan
            @can('update', \App\Models\OrderCustomer::class)
            <a href="{{ route('order-customers.edit', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, ]) }}" class="btn btn-amber mb-1">
                <i class="icon-note"></i>
                Edit Order Customer
            </a>
            @endcan
            @can('delete', \App\Models\OrderCustomer::class)
                @if($orderCustomer->id !== $orderCustomer->order->lead_booker_id)
                    <a href="#" onclick="$('#customer-delete').submit()" class="btn btn-danger mb-1"><i class="icon-trash"></i>Remove Customer</a>
                    <form action="{{ route('order-customers.delete', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer]) }}" method="post" id="customer-delete">
                        @csrf
                    </form>
                @endcan
            @endcan
            @can('read', \App\Models\Customer::class)
                <a href="{{ route('customers.view', ['customer' => $orderCustomer->customer, ]) }}" class="btn btn-info mb-1">
                    <i class="icon-user"></i>
                    View Customer
                </a>
            @endcan
        </div>
    </div>
</div>
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;">

<div class="heading pt-2 pb-md-3 pb-2">
    <h2 class="fw-bold">Tour Components</h2>        
</div>

{{-- Components Section --}}
<div class="card">
    <div class="card-body">
        <ul class="nav nav-pills otm-tab">
            <li class="nav-item col-6 col-md-3">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">
                    <i class="icon-home"></i> Accommodation
                </button>
            </li>
            <li class="nav-item col-6 col-md-3">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">
                    <i class="icon-settings"></i> Activities
                </button>
            </li>
            <li class="nav-item col-6 col-md-3">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">
                    <i class="icon-plane"></i>
                    Flights
                </button>
            </li>
            <li class="nav-item col-6 col-md-3">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">
                    <i class="icon-directions"></i>
                    Transports
                </button>
            </li>
        </ul>

        {{-- Tables Definition --}}
        <div id="tables" class="tab-content otm-tab-content">
            {{-- Accommodation Table --}}
            <div id="accommodation" role="tabpanel" class="tab-pane fade show active">
                <div id="accommodation-new" class="d-flex justify-content-between mb-3 flex-wrap">
                    @include('partials.fields.selector.adder',
                        ['field' => 'accommodation_id', 'preselect' => false,
                        'fullRoute' => route('api.available-accommodation.select', ['orderCustomer' => $orderCustomer,]),
                        'createRoute' => '#', 'onclick' => 'addAccommodationAddon()', 'target' => ''])
                </div>
                <div id="accommodation-details">
                    <table id="accommodation-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Room Type</th>
                                <th scope="col">Board Type</th>
                                <th scope="col">Shared With</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Upgrades</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($orderCustomer->orderAccommodation() as $orderAccommodation)
                                <tr component="{{ $orderAccommodation->id }}">
                                    <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderAccommodation->accommodationInventory->check_in) }} to {{ StringFormatter::formatDateTime($orderAccommodation->accommodationInventory->check_out) }}</td>
                                    <td>{{ $orderAccommodation->accommodation->name }}</td>
                                    <td>{{ $orderAccommodation->accommodationInventory->roomType->name }}</td>
                                    <td>{{ $orderAccommodation->accommodationInventory->boardType->name }}</td>
                                    <td>{{ empty($orderAccommodation->group->getMembers($orderCustomer)) ? 'Not Shared' : $orderAccommodation->group->getMembers($orderCustomer) }}</td>
                                    <td>{{ $orderAccommodation->accommodationInventoryTour->tour_component_type }}</td>
                                    <td>
                                        @if($orderAccommodation->tourComponent->tour_component_type == 'Included')
                                            {{ StringFormatter::formatCurrency(0) }}
                                        @else
                                            {{ StringFormatter::formatCurrency($orderAccommodation->cost) }}
                                        @endif
                                    </td>
                                    <td style="width: 20%">
                                        @if($orderAccommodation->tourComponent->tour_component_type == 'Add-on')
                                            Not Available
                                        @else
                                            @if(count($orderAccommodation->tourComponent->getUpgradeKeyMap()) < 2)
                                                No Upgrades Available
                                            @else
                                                @include('partials.fields.selector.adder-preset',
                                                    ['field' => 'accommodation_' . $orderAccommodation->id . '_upgrade', 'preselect' => false,
                                                    'createRoute' => '#', 'onclick' => 'applyAccommodationUpgrade("accommodation_' . $orderAccommodation->id . '_upgrade-input", this)', 'target' => '',
                                                    'selected' => $orderAccommodation->tourComponent->id, 'options' => $orderAccommodation->tourComponent->getUpgradeKeyMap(),])
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('orderAccommodationDelete', ['id' => $orderAccommodation->id,]) }}" method="post">
                                            @csrf
                                            <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, ]) }}" />
                                            <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"><i class="icon-trash"></i></a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                </div>
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
                    <table id="activities-table" class="table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Activity Type</th>
                            <th scope="col">Ticket Type</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Upgrades</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($orderCustomer->orderActivities as $orderActivity)
                            <tr component="{{ $orderActivity->id }}">
                                <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderActivity->activityInventory->starts_at) }} to {{ StringFormatter::formatDateTime($orderActivity->activityInventory->ends_at) }}</td>
                                <td>{{ $orderActivity->activity->name }}</td>
                                <td>{{ $orderActivity->activity->activityType->name }}</td>
                                <td>{{ $orderActivity->activityInventory->ticketType->name }}</td>
                                <td>{{ $orderActivity->tourComponent->tour_component_type }}</td>
                                <td>
                                    @if($orderActivity->tourComponent->tour_component_type == 'Included')
                                        {{ StringFormatter::formatCurrency(0) }}
                                    @else
                                        {{ StringFormatter::formatCurrency($orderActivity->cost) }}
                                    @endif
                                </td>
                                <td style="width: 20%">
                                    @if($orderActivity->tourComponent->tour_component_type == 'Add-on')
                                        Not Available
                                    @else
                                        @if(count($orderActivity->tourComponent->getUpgradeKeyMap()) < 2)
                                            No Upgrades Available
                                        @else
                                            @include('partials.fields.selector.adder-preset',
                                                ['field' => 'activity_' . $orderActivity->id . '_upgrade', 'preselect' => false,
                                                'createRoute' => '#', 'onclick' => 'applyActivityUpgrade("activity_' . $orderActivity->id . '_upgrade-input", this)', 'target' => '',
                                                'selected' => $orderActivity->tourComponent->id, 'options' => $orderActivity->tourComponent->getUpgradeKeyMap(),])
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('orderActivityDelete', ['id' => $orderActivity->id,]) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]) }}" />
                                        <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"><i class="icon-trash"></i></a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
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
                    <table id="flights-table" class="table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Flight Details</th>
                            <th scope="col">Travel Class</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Upgrades</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($orderCustomer->orderFlights as $orderFlight)
                            <tr component="{{ $orderFlight->id }}">
                                <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderFlight->flightInventory->departs_at) }} to {{ StringFormatter::formatDateTime($orderFlight->flightInventory->arrives_at) }}</td>
                                <td>{{ $orderFlight->flightInventory->flight_number }}</td>
                                <td>{{ $orderFlight->flight->departureAirport->name }} to {{ $orderFlight->flight->arrivalAirport->name }}</td>
                                <td>{{ $orderFlight->flightInventory->travelClass->name }}</td>
                                <td>{{ $orderFlight->flightInventoryTour->tour_component_type }}</td>
                                <td>
                                    @if($orderFlight->tourComponent->tour_component_type == 'Included')
                                        {{ StringFormatter::formatCurrency(0) }}
                                    @else
                                        {{ StringFormatter::formatCurrency($orderFlight->cost) }}
                                    @endif
                                </td>
                                <td style="width: 20%">
                                    @if($orderFlight->tourComponent->tour_component_type == 'Add-on')
                                        Not Available
                                    @else
                                        @if(count($orderFlight->tourComponent->getUpgradeKeyMap()) < 2)
                                            No Upgrades Available
                                        @else
                                            @include('partials.fields.selector.adder-preset',
                                                ['field' => 'flight_' . $orderFlight->id . '_upgrade', 'preselect' => false,
                                                'createRoute' => '#', 'onclick' => 'applyFlightUpgrade("flight_' . $orderFlight->id . '_upgrade-input", this)', 'target' => '',
                                                'selected' => $orderFlight->tourComponent->id, 'options' => $orderFlight->tourComponent->getUpgradeKeyMap(),])
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('orderFlightDelete', ['id' => $orderFlight->id,]) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]) }}" />
                                        <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"><i class="icon-trash"></i></a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
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
                    <table id="transports-table" class="table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Transport Type</th>
                            <th scope="col">Transport Information</th>
                            <th scope="col">Travel Class</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Upgrades</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($orderCustomer->orderTransports as $orderTransport)
                            <tr component="{{ $orderTransport->id }}">
                                <td style="min-width: 200px">{{ StringFormatter::formatDateTime($orderTransport->transportInventory->departs_at) }} to {{ StringFormatter::formatDateTime($orderTransport->transportInventory->arrives_at) }}</td>
                                <td>{{ $orderTransport->transport->name }}</td>
                                <td>{{ $orderTransport->transport->transportType->name }}</td>
                                <td>{{ $orderTransport->transport->departureAddress->name }} to {{ $orderTransport->transport->arrivalAddress->name }}</td>
                                <td>{{ $orderTransport->transportInventory->travelClass->name }}</td>
                                <td>{{ $orderTransport->transportInventoryTour->tour_component_type }}</td>
                                <td>
                                    @if($orderTransport->tourComponent->tour_component_type == 'Included')
                                        {{ StringFormatter::formatCurrency(0) }}
                                    @else
                                        {{ StringFormatter::formatCurrency($orderTransport->cost) }}
                                    @endif
                                </td>
                                <td style="width: 20%">
                                    @if($orderTransport->tourComponent->tour_component_type == 'Add-on')
                                        Not Available
                                    @else
                                        @if(count($orderTransport->tourComponent->getUpgradeKeyMap()) < 2)
                                            No Upgrades Available
                                        @else
                                            @include('partials.fields.selector.adder-preset',
                                                ['field' => 'transport_' . $orderTransport->id . '_upgrade', 'preselect' => false,
                                                'createRoute' => '#', 'onclick' => 'applyTransportUpgrade("transport_' . $orderTransport->id . '_upgrade-input", this)', 'target' => '',
                                                'selected' => $orderTransport->tourComponent->id, 'options' => $orderTransport->tourComponent->getUpgradeKeyMap(),])
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('orderTransportDelete', ['id' => $orderTransport->id,]) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]) }}" />
                                        <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"><i class="icon-trash"></i></a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Merchandise Section --}}
<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">Merchandise</h4>
        </div>
        <div id="merchandise-new" class="d-flex justify-content-between mb-3 flex-wrap">
            @include('partials.fields.selector.adder',
                        ['field' => 'merchandise_id', 'preselect' => false,
                        'fullRoute' => route('api.available-merchandise.select', ['orderCustomer' => $orderCustomer,]),
                        'createRoute' => '#', 'onclick' => 'addMerchandiseAddon()', 'target' => ''])
        </div>
        <div id="merchandise-details">
            <table id="merchandise-table" class="table table-striped table-responsive-sm">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Component Type</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                @foreach($orderCustomer->orderMerchandise as $orderMerchandise)
                    <tr>
                        <td>{{ $orderMerchandise->merchandise->name }}</td>
                        <td>{{ StringFormatter::formatCurrency($orderMerchandise->merchandise->tour_sales_price) }}</td>
                        <td>{{ $orderMerchandise->merchandise->tour_component_type }}</td>
                        <td>
                            <form action="{{ route('orderMerchandiseDelete', ['id' => $orderMerchandise->id,]) }}" method="post">
                                @csrf
                                <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer,]) }}" />
                                <a href="#" onclick="this.parentNode.submit()" class="btn btn-outline-danger btn-sm"><i class="icon-trash"></i></a>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
{{-- Adjustments Section --}}
<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">Customer Adjustments</h4>
            @can('create', \App\Models\OrderCustomerAdjustment::class)
            <div class="pb-3 text-end">
                <a href="{{ route('order-customer-adjustments.create', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer]) }}" class="btn btn-success text-white">
                    <i class="icon-plus"></i>
                    Add Adjustment
                </a>
            </div>
            @endcan
        </div>
        <div>
            <table class="table table-striped" id="customer-adjustment-table">
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
                        <td>{{ StringFormatter::formatCurrency($adjustment->amount) }}</td>
                        <td>{{ $adjustment->reason }}</td>
                        <td>{{ StringFormatter::formatDate($adjustment->date) }}</td>
                        <td class="actions">
                            @can('update', \App\Models\OrderCustomerAdjustment::class)
                                <a href="{{ route('order-customer-adjustments.edit', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                            @endcan
                            @can('delete', \App\Models\OrderCustomerAdjustment::class)
                                <a href="#" onclick="$('#oadjustment-{{$adjustment->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                <form action="{{ route('order-customer-adjustments.delete', ['order' => $orderCustomer->order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}" method="post" id="oadjustment-{{$adjustment->id}}-delete">
                                    @csrf
                                </form>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-trash"></i>
                                            </span>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
{{-- Closing Container---}}
@endsection
