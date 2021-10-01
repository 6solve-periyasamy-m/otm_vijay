@extends('layout.main')

@section('title', 'Update Flights')

@section('content')
    <a class="btn btn-primary" href="{{ route('flights.create') }}">Create New</a>
    <table id="flight" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Airline Id</th>
            <th scope="col">Departure Airport Id</th>
            <th scope="col">Arrival Airport Id</th>
            <th scope="col">Is Domestic</th>
            <th scope="col">Notes</th>
            <th scope="col">Available After</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($flights as $flight)
            @include('partials.models.flights.row', [
              'flight' => $flight,
              'airline_id' => $flight->airline_id,
              'departure_airport_id' => $flight->departure_airport_id,
              'arrival_airport_id' => $flight->arrival_airport_id,
              'is_domestic' => $flight->is_domestic,
              'notes' => $flight->notes,
              'available_after' => $flight->available_after,
            ])
        @endforeach
    </table>
@endsection
