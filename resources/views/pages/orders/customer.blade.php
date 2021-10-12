@extends('layout.main')

@section('content')
<style>
.header-cell {
    border: 1px solid black !important;
    font-size: 24px;
}
.customer-details {
    min-width: 40%;
    max-width: 40%;
    display:inline-block;
    vertical-align: bottom;
}
.header {
    max-width: 20%;
    font-size: 24px;
    border: 1px solid black;
    border-top: 0;
    border-left: 0;
    margin-bottom: 2px;
}
.select {
    min-width: 95%;
    max-width: 95%;
    display: inline-block;
}
.add-btn {
    min-width: 4%;
    max-width: 4%;
    display: inline-block;
}
.new-section {
    border-bottom: 1px solid black;
    margin-bottom: 5px;
}
</style>
<script type="text/javascript">
function updateAccommodationSelectFields() {
    $('#accommodation-select').find('option').remove().end().append('<option selected>Please choose an option</option>');
    $.get('{{ route('getAvailableAccommodationAddons', ['oCustomerId' => $order_customer->id,]) }}', function (data) {
        $.each(data, function (index, element) {
            $('#accommodation-select').append('<option value=' + element.id + '>' + element.name + ' | ' + element.room_type + '</option>');
        });
    });
}
function updateActivitySelectFields() {
    $('#activities-select').find('option').remove().end().append('<option selected>Please choose an option</option>');
    $.get('{{ route('getAvailableActivityAddons', ['oCustomerId' => $order_customer->id,]) }}', function (data) {
        $.each(data, function (index, element) {
            $('#activities-select').append('<option value=' + element.id + '>' + element.name + ' | ' + element.activity_type + '</option>');
        });
    });
}
function updateFlightSelectFields() {
    $('#flights-select').find('option').remove().end().append('<option selected>Please choose an option</option>');
    $.get('{{ route('getAvailableFlightAddons', ['oCustomerId' => $order_customer->id,]) }}', function (data) {
        $.each(data, function (index, element) {
            $('#flights-select').append('<option value=' + element.id + '>' + element.name + ' | ' + element.travel_class + '</option>');
        });
    });
}
function updateTransportSelectFields() {
    $('#transports-select').find('option').remove().end().append('<option selected>Please choose an option</option>');
    $.get('{{ route('getAvailableTransportAddons', ['oCustomerId' => $order_customer->id,]) }}', function (data) {
        $.each(data, function(index, element) {
            $('#transports-select').append('<option value=' + element.id + '>' + element.name + ' | ' + element.transport_type + '</option>');
        });
    });
}
function addAccommodationAddon() {
    let id = $('#accommodation-select').find(':selected').val()
    if (id != null) {
        $.post('{{ route('addAccommodationAddon') }}', { '_token': '{{ csrf_token() }}', 'customer_id': '{{ $order_customer->id }}', 'accommodation_id': id});
    }
    location.reload();
}
function addActivityAddon() {
    let id = $('#activities-select').find(':selected').val()
    if (id != null) {
        $.post('{{ route('addActivityAddon') }}', { '_token': '{{ csrf_token() }}', 'customer_id': '{{ $order_customer->id }}', 'activity_id': id});
    }
    location.reload();
}
function addFlightAddon() {
    let id = $('#flights-select').find(':selected').val()
    if (id != null) {
        $.post('{{ route('addFlightAddon') }}', { '_token': '{{ csrf_token() }}', 'customer_id': '{{ $order_customer->id }}', 'flight_id': id});
    }
    location.reload();
}
function addTransportAddon() {
    let id = $('#transports-select').find(':selected').val()
    if (id != null) {
        $.post('{{ route('addTransportAddon') }}', { '_token': '{{ csrf_token() }}', 'customer_id': '{{ $order_customer->id }}', 'transport_id': id});
    }
    location.reload();
}
$(document).ready( function () {
    $('#accommodation-table').DataTable({fixedHeader: true});
    $('#activities-table').DataTable({fixedHeader: true});
    $('#flights-table').DataTable({fixedHeader: true});
    $('#transports-table').DataTable({fixedHeader: true});
    updateAccommodationSelectFields();
    updateActivitySelectFields();
    updateFlightSelectFields();
    updateTransportSelectFields();
});
</script>
{{-- Header Details --}}
<div id="header-details">
    <table class="table" style="font-size: 32px">
        <tr>
            <td class="header-cell">{{ $order->booking_reference }}</td>
            <td class="header-cell">{{ $order->tour->title }}</td>
            <td class="header-cell">{{ $order->tour->date_from . " to " . $order->tour->date_to }}</td>
            <td class="header-cell" style="background-color: {{ true ? "#33ff99" : "#ffff99" }}">{{ true ? "Paid in Full" : "Balance Outstanding" }}</td>
        </tr>
    </table>
</div>
{{-- Customer Details Section --}}
<div id="section-1" style="margin-bottom: 5px; border: 1px solid black;">
    <table class="table table-striped customer-details" style="horiz-align: left">
        <thead>
        <tr>
            <th scope="col">Detail</th>
            <th scope="col">Value</th>
        </tr>
        </thead>
        <tr>
            <th scope="row">Full Name:</th>
            <td>{{ $customer->first_name }} {{ $customer->middle_names ?? "" }} {{ $customer->last_name }}</td>
        </tr>
        <tr>
            <th scope="row">Date of Birth:</th>
            <td>{{ $customer->date_of_birth }}</td>
        </tr>
        <tr>
            <th scope="row">Passport Number:</th>
            <td>{{ $customer->passport_number }}</td>
        </tr>
        <tr>
            <th scope="row">Full Name:</th>
            <td>{{ $customer->passport_expiry_date }}</td>
        </tr>
    </table>
    <table class="table table-striped customer-details" style="horiz-align: right">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Home Address</th>
                <th scope="col">Billing Address</th>
            </tr>
        </thead>
        <tr>
            <th scope="row">Street Address</th>
            <td>{{ $customer->homeAddress->address_line_1 }}</td>
            <td>{{ $customer->billingAddress->address_line_1 }}</td>
        </tr>
        <tr>
            <th scope="row">Town</th>
            <td>{{ $customer->homeAddress->region }}</td>
            <td>{{ $customer->billingAddress->region }}</td>
        </tr>
        <tr>
            <th scope="row">Country</th>
            <td>{{ $customer->homeAddress->country }}</td>
            <td>{{ $customer->billingAddress->country }}</td>
        </tr>
        <tr>
            <th scope="row">Postcode</th>
            <td>{{ $customer->homeAddress->postcode }}</td>
            <td>{{ $customer->billingAddress->postcode }}</td>
        </tr>
    </table>
</div>
{{-- Components Section --}}
<div id="billing-section" style="border: 1px solid black">
    <div id="billing-header" class="header">
        Tour Components
    </div>
    {{-- Tabs Definition --}}
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accommodation">Accommodation</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activities">Activities</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#flights">Flights</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transports">Transports</button>
        </li>
    </ul>
    {{-- Tables Definition --}}
    <div id="tables" class="tab-content" style="padding: 5px">
        {{-- Accommodation Table --}}
        <div id="accommodation" role="tabpanel" class="tab-pane fade show active">
            <div id="accommodation-new" class="new-section">
                <select id="accommodation-select" class="form-select form-select-lg select">
                </select>
                <button class="btn btn-success add-btn" onclick="addAccommodationAddon()">Add</button>
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
                                <td>{{ $accommodationEntry["inventory"]->check_in_date_time }} to {{ $accommodationEntry["inventory"]->check_out_date_time }}</td>
                                <td>{{ $accommodationEntry["component"]->title }}</td>
                                <td>{{ $accommodationEntry["inventory"]->roomType->room_type_name }}</td>
                                <td>TBI</td> {{-- TODO: Discuss and Implement--}}
                                <td>{{ $accommodationEntry["tour"]->tour_component_type }}</td>
                                <td>
                                    <form action="{{ route('orderAccommodationDelete', ['id' => $accommodationEntry['order']->id,]) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['id' => $order_customer->id]) }}" />
                                        <a href="#" onclick="this.parentNode.submit()" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon></a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
            </div>
        </div>
        {{-- Activities Table --}}
        <div id="activities" role="tabpanel" class="tab-pane fade">
            <div id="activities-new" class="new-section">
                <select id="activities-select" class="form-select form-select-lg select">
                </select>
                <button class="btn btn-success add-btn" onclick="addActivityAddon()">Add</button>
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
                            <td>{{ $activity["inventory"]->activity_start_date_time }} to {{ $activity["inventory"]->activity_end_date_time }}</td>
                            <td>{{ $activity["component"]->title }}</td>
                            <td>{{ $activity["component"]->activityType->activity_type_title }}</td>
                            <td>{{ $activity["tour"]->tour_component_type }}</td>
                            <td>
                                <form action="{{ route('orderActivityDelete', ['id' => $activity['order']->id,]) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['id' => $order_customer->id]) }}" />
                                    <a href="#" onclick="this.parentNode.submit()" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        {{-- Flights Table --}}
        <div id="flights" role="tabpanel" class="tab-pane fade">
            <div id="flights-new" class="new-section">
                <select id="flights-select" class="form-select form-select-lg select">
                </select>
                <button class="btn btn-success add-btn" onclick="addFlightAddon()">Add</button>
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
                            <td>{{ $flight["inventory"]->departure_date_time }} to {{ $flight["inventory"]->arrival_date_time }}</td>
                            <td>{{ $flight["inventory"]->flight_number }}</td>
                            <td>{{ $flight["inventory"]->travelClass->title }}</td>
                            <td>{{ $flight["tour"]->tour_component_type }}</td>
                            <td>
                                <form action="{{ route('orderFlightDelete', ['id' => $flight['order']->id,]) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['id' => $order_customer->id]) }}" />
                                    <a href="#" onclick="this.parentNode.submit()" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        {{-- Transports Table --}}
        <div id="transports" role="tabpanel" class="tab-pane fade">
            <div id="transports-new" class="new-section">
                <select id="transports-select" class="form-select form-select-lg select">
                </select>
                <button class="btn btn-success add-btn" onclick="addTransportAddon()">Add</button>
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
                            <td>{{ $transport["inventory"]->departure_date_time }} to {{ $transport["inventory"]->arrival_date_time }}</td>
                            <td>{{ $transport["component"]->name }}</td>
                            <td>{{ $transport["inventory"]->travelClass->title }}</td>
                            <td>{{ $transport["tour"]->tour_component_type }}</td>
                            <td>
                                <form action="{{ route('orderTransportDelete', ['id' => $transport['order']->id,]) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="redirect" value="{{ route(Route::currentRouteName(), ['id' => $order_customer->id]) }}" />
                                    <a href="#" onclick="this.parentNode.submit()" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Closing Container---}}
@endsection
