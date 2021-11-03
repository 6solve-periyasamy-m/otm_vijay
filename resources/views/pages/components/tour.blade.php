@extends('layout.app')

@section('page_header')
<h1 class="page-title">
    Tour Components
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
                        <form method="post" action={{ route('tourComponentUpdate') }}>
                            @csrf
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
                                    <th>
                                        Sales Price
                                    </th>
                                    <th>
                                        Component Type
                                    </th>
                                    <th>

                                    </th>
                                </thead>
                                <tbody>
                                    @foreach($accommodationInventories as $accommodation)
                                    <td>
                                        {{$accommodation->accommodation->name . ' - ' . $accommodation->accommodation->region->name}}
                                    </td>
                                    <td>
                                        {{Carbon\Carbon::parse($accommodation->check_in)->format('d-m-Y H:i')}}
                                    </td>
                                    <td>
                                        {{Carbon\Carbon::parse($accommodation->check_out)->format('d-m-Y H:i')}}
                                    </td>
                                    <td>
                                        {{ $accommodation->roomType->name }}
                                    </td>
                                    <td>
                                        {{ $accommodation->boardType->name }}
                                    </td>
                                    <td>
                                        <input type="text"
                                            placeholder="{{ $accommodation->tour[0]->pivot->sales_price}}"
                                            class="form-control"> </input>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-lg">
                                            @foreach ($componentTypes as $componentType)
                                            <option value="{{ $componentType->id }}"
                                                {{ $componentType->id == $accommodation->component_type->id ? 'selected="selected"' : ''}}>
                                                {{ $componentType->component_type_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-success">Submit</button>
                                    </td>
                                    @endforeach
                        </form>
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
                        <form method="post" action={{ route('tourComponentUpdate') }}>
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
                                    <th>
                                        Sales Price
                                    </th>
                                    <th>
                                        Component Type
                                    </th>
                                    <th>

                                    </th>
                                </thead>
                                <tbody>
                                    @foreach($activityInventories as $activity)
                                    <tr>
                                        <td>
                                            {{($activity->activity->name)}}
                                        </td>
                                        <td>
                                            {{ Carbon\Carbon::parse($activity->starts_at)->format('d-m-Y H:i') }}
                                        </td>
                                        <td>
                                            {{ Carbon\Carbon::parse($activity->ends_at)->format('d-m-Y H:i') }}
                                        </td>
                                        <td>
                                            {{ $activity->ticketType->name }}
                                        </td>
                                        <td>
                                            <input type="text"
                                                placeholder="{{ $activity->tour[0]->pivot->sales_price}}"
                                                class="form-control"> </input>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-lg">
                                                @foreach ($componentTypes as $componentType)
                                                <option value="{{ $componentType->id }}"
                                                    {{$componentType->id == $activity->component_type->id ? 'selected="selected"' : ''}}>
                                                    {{ $componentType->component_type_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn btn-success">Submit</button>
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
                                <th>
                                    Sales Price
                                </th>
                                <th>
                                    Component Type
                                </th>
                                <th>

                                </th>
                            </thead>
                            <tbody>
                                @foreach($flightInventories as $flightInventory)
                                <?php //dump( $flightInventory, $flightInventory->flight, $flightInventory->flight->departureAirport); ?>
                                 <tr>
                                    <td>
                                        {{ $flightInventory->flight->airline->name }}
                                    </td>
                                    <td>
                                        {{ $flightInventory->flight->departureAirport->name }}
                                    </td>
                                    
                                    <td>
                                        {{ Carbon\Carbon::parse($flightInventory->flight->departure_time . $flightInventory->flight->departure_date)->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $flightInventory->flight->arrivalAirport->name }}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($flightInventory->flight->arrival_time . $flightInventory->flight->arrival_date)->format('d-m-Y H:i') }}
                                    </td>
                                    <td>
                                        <input type="text"
                                            placeholder="{{ $flightInventory->sales_price }}"
                                            class="form-control" /> 
                                    </td>
                                    <td>
                                        <button class="btn btn-success">Submit</button>
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
                                @foreach($transportInventories as $transport)
                                <tr>
                                    <td>
                                        {{($transport->transport->name)}}
</td>
<td>
    {{ $transport->departureLocation }}
</td>
<td>
    {{ Carbon\Carbon::parse($transport->departs_at)->format('d-m-Y H:i') }}
</td>
<td>
    {{ $transport->arrivalLocation }}
</td>
<td>
    {{ Carbon\Carbon::parse($transport->arrives_at)->format('d-m-Y H:i') }}
</td>
<td>
    <input type="text"
        placeholder="{{ $transport->sales_price }}"
        class="form-control"> </input>
</td>
<td>
    {{ dd($transport->component_type) }}
    <select class="form-control form-control-lg">
        @foreach ($componentTypes as $componentType)
        <option value="{{ $componentType->id }}"
            {{ $componentType->id == $transport->component_type->id ? 'selected="selected"' : '' }}>
            {{ $componentType->component_type_name }}
        </option>
        @endforeach
    </select>
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
@stop
