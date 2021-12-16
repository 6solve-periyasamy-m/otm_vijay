@extends('layout.master')

@section('title', 'View Order Customer')

@section('header-script')
<script type="text/javascript">
    {{-- TODO: Upgrade to Select2 --}}
function updateAccommodationSelectFields() {
    $('#accommodation-select').find('option').remove().end().append('<option selected>Please choose an option</option>');
    $.get('{{ route('api.order.addon.get.accommodation', ['oCustomerId' => $order_customer->id,]) }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}'}, function (data) {
        $.each(data, function (index, element) {
            $('#accommodation-select').append('<option value=' + element.id + '>' + element.name + ' | ' + element.room_type + '</option>');
        });
    });
}
function updateActivitySelectFields() {
    $('#activities-select').find('option').remove().end().append('<option selected>Please choose an option</option>');
    $.get('{{ route('api.order.addon.get.activity', ['oCustomerId' => $order_customer->id,]) }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}'}, function (data) {
        $.each(data, function (index, element) {
            $('#activities-select').append('<option value=' + element.id + '>' + element.name + ' | ' + element.activity_type + '</option>');
        });
    });
}
function updateFlightSelectFields() {
    $('#flights-select').find('option').remove().end().append('<option selected>Please choose an option</option>');
    $.get('{{ route('api.order.addon.get.flight', ['oCustomerId' => $order_customer->id,]) }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}'}, function (data) {
        $.each(data, function (index, element) {
            $('#flights-select').append('<option value=' + element.id + '>' + element.name + ' | ' + element.travel_class + '</option>');
        });
    });
}
function updateTransportSelectFields() {
    $('#transports-select').find('option').remove().end().append('<option selected>Please choose an option</option>');
    $.get('{{ route('api.order.addon.get.transport', ['oCustomerId' => $order_customer->id,]) }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}'}, function (data) {
        $.each(data, function(index, element) {
            $('#transports-select').append('<option value=' + element.id + '>' + element.name + ' | ' + element.transport_type + '</option>');
        });
    });
}
function addAccommodationAddon() {
    let id = $('#accommodation-select').find(':selected').val()
    if (id != null) {
        $.post('{{ route('api.order.addon.add.accommodation') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $order_customer->id }}', 'accommodation_id': id})
            .done(function () { location.reload();})
            .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
    }
}
function addActivityAddon() {
    let id = $('#activities-select').find(':selected').val()
    if (id != null) {
        $.post('{{ route('api.order.addon.add.activity') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $order_customer->id }}', 'activity_id': id})
            .done(function () { location.reload();})
            .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
    }
}
function addFlightAddon() {
    let id = $('#flights-select').find(':selected').val()
    if (id != null) {
        $.post('{{ route('api.order.addon.add.flight') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $order_customer->id }}', 'flight_id': id})
            .done(function () { location.reload();})
            .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
    }
}
function addTransportAddon() {
    let id = $('#transports-select').find(':selected').val()
    if (id != null) {
        $.post('{{ route('api.order.addon.add.transport') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $order_customer->id }}', 'transport_id': id})
            .done(function () { location.reload();})
            .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
    }
}
function addMerchandiseAddon() {
    let id = $('#merchandise_id-input').find(':selected').val()
    if (id != null) {
        $.post('{{ route('api.order.addon.add.merchandise') }}', { '__api_token': '{{ Auth::user()->getCurrentToken()->token }}', '_token': '{{ csrf_token() }}', 'customer_id': '{{ $order_customer->id }}', 'merchandise_id': id})
            .done(function () { location.reload();})
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
    updateAccommodationSelectFields();
    updateActivitySelectFields();
    updateFlightSelectFields();
    updateTransportSelectFields();
});
</script>
@endsection
@section('content')
{{-- Header Details --}}
<div class="otm-callout" id="header-details">
    <div class="row">
        <div class="col-12 col-xl-6">
            <p>Booking Reference</p>
            <h6 class="fw-bold">{{ $order->booking_reference }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Tour</p>
            <h6 class="fw-bold">{{ $order->tour->name }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Tour Date</p>
            <h6 class="fw-bold">{{ StringFormatter::formatDate($order->tour->date_from) . " to " . StringFormatter::formatDate($order->tour->date_to) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Order Status</p>
            <h6 class="badge badge-{{ $status['color'] }} fw-bold">{{ $status['status'] }}</h6>
        </div>
        <div class="col-12">
            @can('update', \App\Models\Order::class)
                <a href="{{ route('orders.edit', ['order' => $order,]) }}" class="btn btn-success">
                    <i class="icon-note"></i>
                    Edit Order
                </a>
            @endcan
            <a href="{{ route('orders.view', ['order' => $order,]) }}" class="btn btn-amber">
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
            <h4 class="fw-bold">{{ $customer->first_name }} {{ $customer->middle_names ?? "" }} {{ $customer->last_name }}</h4>
        </div>
        <div class="col-xl-4">
            <p>Date of Birth</p>
            <h6 class="fw-bold">{{ StringFormatter::formatDateTime($customer->date_of_birth) }}</h6>
            <p>Passport Number</p>
            <h6 class="fw-bold">{{ $customer->passport_number }}</h6>
            <p>Password Expire Date</p>
            <h6 class="fw-bold">{{ $customer->passport_expiry_date }}</h6>
        </div>
        <div class="col-xl-8">
            @if ($customer->homeAddress->address_line_1 != '' || $customer->billingAddress->address_line_1 != '')
            <div class="row">
                <div class="col-xl-6">
                    <p>Street (Home Address)</p>
                    <h6 class="fw-bold">{{ $customer->homeAddress->address_line_1 }}</h6>
                </div>
                <div class="col-xl-6">
                    <p>Street (Billing Address)</p>
                    <h6 class="fw-bold">{{ $customer->billingAddress->address_line_1 }}</h6>
                </div>
            </div>
            @endif
            @if ($customer->homeAddress->region != '' || $customer->billingAddress->region != '')
            <div class="row">
                <div class="col-xl-6">
                    <p>Town (Home Address)</p>
                    <h6 class="fw-bold">{{ $customer->homeAddress->region }}</h6>
                </div>
                <div class="col-xl-6">
                    <p>Town (Billing Address)</p>
                    <h6 class="fw-bold">{{ $customer->billingAddress->region }}</h6>
                </div>
            </div>
            @endif
            @if ($customer->homeAddress->country != '' || $customer->billingAddress->country != '')
            <div class="row">
                <div class="col-xl-6">
                    <p>Country (Home Address)</p>
                    <h6 class="fw-bold">{{ $customer->homeAddress->country }}</h6>
                </div>
                <div class="col-xl-6">
                    <p>Country (Billing Address)</p>
                    <h6 class="fw-bold">{{ $customer->billingAddress->country }}</h6>
                </div>
            </div>
            @endif
            @if ($customer->homeAddress->postcode != '' || $customer->billingAddress->postcode != '')
            <div class="row">
                <div class="col-xl-6">
                    <p>Postcode (Home Address)</p>
                    <h6 class="fw-bold">{{ $customer->homeAddress->postcode }}</h6>
                </div>
                <div class="col-xl-6">
                    <p>Postcode (Billing Address)</p>
                    <h6 class="fw-bold">{{ $customer->billingAddress->postcode }}</h6>
                </div>
            </div>
            @endif
        </div>
        <div class="col-12">
            @can('create', \App\Models\OrderCustomerAdjustment::class)
            <a href="{{ route('order-customer-adjustments.create', ['order' => $order, 'orderCustomer' => $order_customer, ]) }}" class="btn btn-success mb-1">
                <i class="icon-plus"></i>
                Add Adjustment
            </a>
            @endcan
            @can('update', \App\Models\OrderCustomer::class)
            <a href="{{ route('order-customers.edit', ['order' => $order, 'orderCustomer' => $order_customer, ]) }}" class="btn btn-amber mb-1">
                <i class="icon-note"></i>
                Edit Order Customer
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
                    <select id="accommodation-select" class="form-select select mb-1">
                    </select>
                    <button class="btn btn-primary text-white" onclick="addAccommodationAddon()">
                        <i class="icon-plus"></i>
                        Add Component
                    </button>
                </div>
                <div id="accommodation-details">
                    <table id="accommodation-table" class="table table-striped table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Room Type</th>
                                <th scope="col">Shared With</th>
                                <th scope="col">Component Type</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($accommodation as $accommodationEntry)
                                <tr>
                                    <td style="min-width: 200px">{{ StringFormatter::formatDateTime($accommodationEntry["inventory"]->check_in) }} to {{ StringFormatter::formatDateTime($accommodationEntry["inventory"]->check_out) }}</td>
                                    <td>{{ $accommodationEntry["component"]->name }}</td>
                                    <td>{{ $accommodationEntry["inventory"]->roomType->name }}</td>
                                    <td>TBI</td> {{-- TODO: Discuss and Implement--}}
                                    <td>{{ $accommodationEntry["tour"]->tour_component_type }}</td>
                                    <td>
                                        <form action="{{ route('orderAccommodationDelete', ['id' => $accommodationEntry['order']->id,]) }}" method="post">
                                            @csrf
                                            <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $order, 'orderCustomer' => $order_customer, ]) }}" />
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
                    <select id="activities-select" class="form-select select mb-1">
                    </select>
                    <button class="btn btn-primary text-white" onclick="addActivityAddon()">
                        <i class="icon-plus"></i>
                        Add Component
                    </button>
                </div>
                <div id="activities-details">
                    <table id="activities-table" class="table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Activity Type</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($activities as $activity)
                            <tr>
                                <td style="min-width: 200px">{{ StringFormatter::formatDateTime($activity["inventory"]->starts_at) }} to {{ StringFormatter::formatDateTime($activity["inventory"]->ends_at) }}</td>
                                <td>{{ $activity["component"]->name }}</td>
                                <td>{{ $activity["component"]->activityType->name }}</td>
                                <td>{{ $activity["tour"]->tour_component_type }}</td>
                                <td>
                                    <form action="{{ route('orderActivityDelete', ['id' => $activity['order']->id,]) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $order, 'orderCustomer' => $order_customer,]) }}" />
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
                    <select id="flights-select" class="form-select select mb-1">
                    </select>
                    <button class="btn btn-primary text-white" onclick="addFlightAddon()">
                        <i class="icon-plus"></i>
                        Add Component
                    </button>
                </div>
                <div id="flights-details">
                    <table id="flights-table" class="table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Travel Class</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($flights as $flight)
                            <tr>
                                <td style="min-width: 200px">{{ StringFormatter::formatDateTime($flight["inventory"]->departs_at) }} to {{ StringFormatter::formatDateTime($flight["inventory"]->arrives_at) }}</td>
                                <td>{{ $flight["inventory"]->flight_number }}</td>
                                <td>{{ $flight["inventory"]->travelClass->name }}</td>
                                <td>{{ $flight["tour"]->tour_component_type }}</td>
                                <td>
                                    <form action="{{ route('orderFlightDelete', ['id' => $flight['order']->id,]) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $order, 'orderCustomer' => $order_customer,]) }}" />
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
                    <select id="transports-select" class="form-select select mb-1">
                    </select>
                    <button class="btn btn-primary text-white" onclick="addTransportAddon()">
                        <i class="icon-plus"></i>
                        Add Component
                    </button>
                </div>
                <div id="transports-details">
                    <table id="transports-table" class="table table-striped table-responsive-sm">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Travel Class</th>
                            <th scope="col">Component Type</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($transports as $transport)
                            <tr>
                                <td style="min-width: 200px">{{ StringFormatter::formatDateTime($transport["inventory"]->departs_at) }} to {{ StringFormatter::formatDateTime($transport["inventory"]->arrives_at) }}</td>
                                <td>{{ $transport["component"]->name }}</td>
                                <td>{{ $transport["inventory"]->travelClass->name }}</td>
                                <td>{{ $transport["tour"]->tour_component_type }}</td>
                                <td>
                                    <form action="{{ route('orderTransportDelete', ['id' => $transport['order']->id,]) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $order, 'orderCustomer' => $order_customer,]) }}" />
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
                        'fullRoute' => route('api.available-merchandise.select', ['orderCustomer' => $customer,]),
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
                @foreach($merchandise as $merch)
                    <tr>
                        <td>{{ $merch["tour"]->name }}</td>
                        <td>{{ StringFormatter::formatCurrency($merch["tour"]->tour_sales_price) }}</td>
                        <td>{{ $merch["tour"]->tour_component_type }}</td>
                        <td>
                            <form action="{{ route('orderMerchandiseDelete', ['id' => $merch['order']->id,]) }}" method="post">
                                @csrf
                                <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['order' => $order, 'orderCustomer' => $order_customer,]) }}" />
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
                <a href="{{ route('order-customer-adjustments.create', ['order' => $order, 'orderCustomer' => $order_customer]) }}" class="btn btn-success text-white">
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
                @foreach($order_customer->adjustments as $adjustment)
                    <tr>
                        <td>{{ StringFormatter::formatCurrency($adjustment->amount) }}</td>
                        <td>{{ $adjustment->reason }}</td>
                        <td>{{ StringFormatter::formatDate($adjustment->date) }}</td>
                        <td class="actions">
                            @can('update', \App\Models\OrderCustomerAdjustment::class)
                                <a href="{{ route('order-customer-adjustments.edit', ['order' => $order, 'orderCustomer' => $ordersCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                            @endcan
                            @can('delete', \App\Models\OrderCustomerAdjustment::class)
                                <a href="#" onclick="$('#oadjustment-{{$adjustment->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                <form action="{{ route('order-customer-adjustments.delete', ['order' => $order, 'orderCustomer' => $ordersCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}" method="post" id="oadjustment-{{$adjustment->id}}-delete">
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
