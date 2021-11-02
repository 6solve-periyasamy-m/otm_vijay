@extends('layout.app')

@section('page_header')
<h1 class="page-title">
    Customer Order Components
</h1>
<a href="{{ URL::previous() }}"><button type="button" class="btn btn-danger">Return to last page</button></a>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>Accommodations</h4>
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <th>
                                    Accommodation Name
                                </th>
                                <th>
                                    Check-In Date & Time
                                </th>
                                <th>
                                    Check-Out Date & Time
                                </th>
                                <th>
                                    Room Type
                                </th>
                                <th>
                                    Board Type
                                </th>
                            </thead>
                            <tbody>
                                @foreach($orderAccommodations as $accommodation)
                                <td>
                                    {{($accommodation->accommodation->name)}}
                                </td>
                                <td>
                                    {{Carbon\Carbon::parse($accommodation->accommodationInventory->check_in)->format('d-m-Y H:i')}}
                                </td>
                                <td>
                                    {{Carbon\Carbon::parse($accommodation->accommodationInventory->check_out)->format('d-m-Y H:i')}}
                                </td>
                                <td>
                                    {{ $accommodation->accommodationInventory->roomType->name }}
                                </td>
                                <td>
                                    {{ $accommodation->accommodationInventory->boardType->name }}
                                </td>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>Activities</h4>
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <th>
                                    Activity Name
                                </th>
                                <th>
                                    Activity Start Date & Time
                                </th>
                                <th>
                                    Activity End Date & Time
                                </th>
                                <th>
                                    Ticket Type
                                </th>
                            </thead>
                            <tbody>
                                @foreach($orderActivities as $activity)
                                <tr>
                                    <td>
                                        {{($activity->activity->name)}}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($activity->activityInventory->starts_at)->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($activity->activityInventory->ends_at)->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $activity->activityInventory->ticketType->name }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>Flights</h4>
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <th>
                                    Flight Name
                                </th>
                                <th>
                                    Departure Airport
                                </th>
                                <th>
                                    Departure Date & Time
                                </th>
                                <th>
                                    Arrival Airport
                                </th>
                                <th>
                                    Arrival Date & Time
                                </th>
                            </thead>
                            <tbody>
                                @foreach($orderFlights as $flight)
                                <tr>
                                    <td>
                                        {{($flight->flight->airline->name)}}
                                    </td>
                                    <td>
                                        {{ $flight->departureAirport->name }}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($flight->flightInventory->departure_time.$flight->flight->departure_date)->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $flight->arrivalAirport->name }}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($flight->flightInventory->arrival_time.$flight->flight->arrival_date)->format('d-m-Y H:i') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{--
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>Transport</h4>
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <th>
                                    Transport Name
                                </th>
                                <th>
                                    Departure Location
                                </th>
                                <th>
                                    Departure Date & Time
                                </th>
                                <th>
                                    Arrival Location
                                </th>
                                <th>
                                    Arrival Date & Time
                                </th>
                            </thead>
                            <tbody>
                                @foreach($orderTransports as $transport)
                                <tr>
                                    <td>
                                        {{($transport->transport->name)}}
                                    </td>
                                    <td>
                                        {{ $transport->transport->departureLocation->name }}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($transport->transportInventory->departs_at)->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $transport->transport->departureLocation->name }}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($transport->transportInventory->arrives_at)->format('d-m-Y H:i') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
--}}
@stop
