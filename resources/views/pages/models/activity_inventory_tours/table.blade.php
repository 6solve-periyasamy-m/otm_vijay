@extends('layout.main')

@section('title', 'All Activity Inventory Tours')

@section('content')
    <a class="btn btn-primary" href="{{ route('activity-inventory-tours.create') }}">Create New</a>
    <table id="activityInventoryTour" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tour Id</th>
            <th scope="col">Activity Inventory Id</th>
            <th scope="col">Tour Component Type</th>
            <th scope="col">Tour Sales Price</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($activityInventoryTours as $activityInventoryTour)
            @include('partials.models.activity_inventory_tours.row', [
              'activityInventoryTour' => $activityInventoryTour,
              'tour_id' => $activityInventoryTour->tour_id,
              'activity_inventory_id' => $activityInventoryTour->activity_inventory_id,
              'tour_component_type' => $activityInventoryTour->tour_component_type,
              'tour_sales_price' => $activityInventoryTour->tour_sales_price,
            ])
        @endforeach
    </table>
@endsection
