@extends('layout.main')

@section('title', 'All Transport Inventories')

@section('content')
    <a class="btn btn-primary" href="{{ route('transport-inventories.create') }}">Create New</a>
    <table id="transportInventory" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Transport Id</th>
            <th scope="col">Travel Class Id</th>
            <th scope="col">Departure Date Time</th>
            <th scope="col">Departure Confirmed</th>
            <th scope="col">Arrival Date Time</th>
            <th scope="col">Arrival Confirmed</th>
            <th scope="col">Fit Selectable</th>
            <th scope="col">Stock</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Sales Price</th>
            <th scope="col">Currency</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($transportInventories as $transportInventory)
            @include('partials.models.transport_inventories.row', [
              'transportInventory' => $transportInventory,
              'transport_id' => $transportInventory->transport_id,
              'travel_class_id' => $transportInventory->travel_class_id,
              'departs_at' => $transportInventory->departs_at,
              'departure_time_confirmed' => $transportInventory->departure_time_confirmed,
              'arrives_at' => $transportInventory->arrives_at,
              'arrival_time_confirmed' => $transportInventory->arrival_time_confirmed,
              'fit_selectable' => $transportInventory->fit_selectable,
              'stock' => $transportInventory->stock,
              'purchase_price' => $transportInventory->purchase_price,
              'sales_price' => $transportInventory->sales_price,
              'currency' => $transportInventory->currency,
              'notes' => $transportInventory->notes,
            ])
        @endforeach
    </table>
@endsection
