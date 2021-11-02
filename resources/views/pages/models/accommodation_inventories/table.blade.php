@extends('layout.main')

@section('title', 'All Accommodation Inventories')

@section('content')
    <a class="btn btn-primary" href="{{ route('accommodation-inventories.create') }}">Create New</a>
    <table id="accommodationInventory" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Accommodation Id</th>
            <th scope="col">Room Type Id</th>
            <th scope="col">Board Type Id</th>
            <th scope="col">Check In Date Time</th>
            <th scope="col">Checkin Confirmed</th>
            <th scope="col">Check Out</th>
            <th scope="col">Checkout Confirmed</th>
            <th scope="col">Fit Selectable</th>
            <th scope="col">Stock</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Sales Price</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($accommodationInventories as $accommodationInventory)
            @include('partials.models.accommodation_inventories.row', [
              'accommodationInventory' => $accommodationInventory,
              'accommodation_id' => $accommodationInventory->accommodation_id,
              'room_type_id' => $accommodationInventory->room_type_id,
              'board_type_id' => $accommodationInventory->board_type_id,
              'check_in' => $accommodationInventory->check_in,
              'check_in_time_confirmed' => $accommodationInventory->check_in_time_confirmed,
              'check_out' => $accommodationInventory->check_out,
              'check_out_time_confirmed' => $accommodationInventory->check_out_time_confirmed,
              'fit_selectable' => $accommodationInventory->fit_selectable,
              'stock' => $accommodationInventory->stock,
              'purchase_price' => $accommodationInventory->purchase_price,
              'sales_price' => $accommodationInventory->sales_price,
              'notes' => $accommodationInventory->notes,
            ])
        @endforeach
    </table>
@endsection
