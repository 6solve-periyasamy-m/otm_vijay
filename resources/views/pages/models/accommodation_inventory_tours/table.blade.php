@extends('layout.main')

@section('title', 'All Accommodation Inventory Tours')

@section('content')
    <a class="btn btn-primary" href="{{ route('accommodation-inventory-tours.create') }}">Create New</a>
    <table id="accommodationInventoryTour" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tour Id</th>
            <th scope="col">Accommodation Inventory Id</th>
            <th scope="col">Tour Component Type</th>
            <th scope="col">Tour Sales Price</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($accommodationInventoryTours as $accommodationInventoryTour)
            @include('partials.models.accommodation_inventory_tours.row', [
              'accommodationInventoryTour' => $accommodationInventoryTour,
              'tour_id' => $accommodationInventoryTour->tour_id,
              'accommodation_inventory_id' => $accommodationInventoryTour->accommodation_inventory_id,
              'tour_component_type' => $accommodationInventoryTour->tour_component_type,
              'tour_sales_price' => $accommodationInventoryTour->tour_sales_price,
            ])
        @endforeach
    </table>
@endsection
