@extends('layout.master')

@section('title', 'All Flights')

@section('content')
@can('create', \App\Models\Flight\Flight::class)
<div class="card">
    <div class="card-body">
        <a class="btn btn-primary float-end" href="{{ route('flights.create') }}">
            {{ Icon::create() }}
            <span>Create New</span>
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-body">
        <table id="flight" style="width: 100%;" class="datatable table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Airline</th>
                <th scope="col">Departure Airport</th>
                <th scope="col">Arrival Airport</th>
                <th scope="col">Is Domestic</th>
                <th scope="col">Available From</th>
                <th scope="col">Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($flights as $flight)
                <tr>
                    <td><a href="{{ route('flights.view', ['flight' => $flight,]) }}">{{ $flight->airline->name }}</a></td>
                    <td>{{ $flight->departureAirport->name }}</td>
                    <td>{{ $flight->arrivalAirport->name }}</td>
                    <td>{{ $flight->is_domestic ? "Domestic" : "International" }}</td>
                    <td>{{ f_date($flight->available_from) }}</td>
                    <td>{{ $flight->internal_notes }}</td>
                    <td class="actions-3">
                        @can('create', \App\Models\Flight\Flight::class)
                            <a href="{{route('flights.return', ['flight' => $flight,])}}" class="btn btn-outline-blue btn-sm mb-1">
                                {{ Icon::returnTrip() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::returnTrip() }}
                            </span>
                        @endcan
                        @can('update', \App\Models\Flight\Flight::class)
                            <a href="{{route('flights.edit', ['flight' => $flight,])}}" class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::edit() }}
                            </span>
                        @endcan
                        @can('delete', \App\Models\Flight\Flight::class)
                            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                               onclick="event.preventDefault();document.getElementById('flight-{{ $flight->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="flight-{{ $flight->id }}-delete" action="{{ route('flights.delete', ['flight' => $flight,]) }}"
                                  method="POST" style="display: none;">{{ csrf_field() }}</form>
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
</div>
@endsection
