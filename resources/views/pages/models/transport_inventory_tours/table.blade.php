@extends('layout.main')

@section('title', 'All Transport Inventory Tours')

@section('content')
    <a class="btn btn-primary" href="{{ route('transport-inventory-tours.create') }}">Create New</a>
    <table id="transportInventoryTour" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tour Id</th>
            <th scope="col">Transport Inventory Id</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($transportInventoryTours as $transportInventoryTour)
            @include('partials.models.transport_inventory_tours.row', [
              'transportInventoryTour' => $transportInventoryTour,
              'tour_id' => $transportInventoryTour->tour_id,
              'transport_inventory_id' => $transportInventoryTour->transport_inventory_id,
            ])
        @endforeach
    </table>
@endsection
