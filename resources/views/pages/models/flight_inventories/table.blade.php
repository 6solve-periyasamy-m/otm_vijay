@extends('layout.main')

@section('title', 'All Flight Inventories')

@section('content')
    <a class="btn btn-primary" href="{{ route('flight-inventories.create') }}">Create New</a>
    <table id="flightInventory" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Flight Id</th>
            <th scope="col">Travel Class Id</th>
            <th scope="col">Flight Number</th>
            <th scope="col">Check In Date Time</th>
            <th scope="col">Departure Date Time</th>
            <th scope="col">Arrival Date Time</th>
            <th scope="col">Fit Selectable</th>
            <th scope="col">Stock</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Sales Price</th>
            <th scope="col">Currency</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($flightInventories as $flightInventory)
            @include('partials.models.flight_inventories.row', [
              'flightInventory' => $flightInventory,
              'flight_id' => $flightInventory->flight_id,
              'travel_class_id' => $flightInventory->travel_class_id,
              'flight_number' => $flightInventory->flight_number,
              'check_in_date_time' => $flightInventory->check_in_date_time,
              'departure_date_time' => $flightInventory->departure_date_time,
              'arrival_date_time' => $flightInventory->arrival_date_time,
              'fit_selectable' => $flightInventory->fit_selectable,
              'stock' => $flightInventory->stock,
              'purchase_price' => $flightInventory->purchase_price,
              'sales_price' => $flightInventory->sales_price,
              'currency' => $flightInventory->currency,
              'notes' => $flightInventory->notes,
            ])
        @endforeach
    </table>
@endsection
