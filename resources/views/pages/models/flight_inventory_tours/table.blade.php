@extends('layout.main')

@section('title', 'All Flight Inventory Tours')

@section('content')
    <a class="btn btn-primary" href="{{ route('flight-inventory-tours.create') }}">Create New</a>
    <table id="flightInventoryTour" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tour Id</th>
            <th scope="col">Flight Inventory Id</th>
            <th scope="col">Tour Component Type</th>
            <th scope="col">Flight Type</th>
            <th scope="col">Tour Sales Price</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($flightInventoryTours as $flightInventoryTour)
            @include('partials.models.flight_inventory_tours.row', [
              'flightInventoryTour' => $flightInventoryTour,
              'tour_id' => $flightInventoryTour->tour_id,
              'flight_inventory_id' => $flightInventoryTour->flight_inventory_id,
              'tour_component_type' => $flightInventoryTour->tour_component_type,
              'flight_type' => $flightInventoryTour->flight_type,
              'tour_sales_price' => $flightInventoryTour->tour_sales_price,
            ])
        @endforeach
    </table>
@endsection
