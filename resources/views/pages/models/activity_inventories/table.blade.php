@extends('layout.main')

@section('title', 'All Activity Inventories')

@section('content')
    <a class="btn btn-primary" href="{{ route('activity-inventories.create') }}">Create New</a>
    <table id="activityInventory" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Activity Id</th>
            <th scope="col">Ticket Type Id</th>
            <th scope="col">Activity Start Date Time</th>
            <th scope="col">Activity End Date Time</th>
            <th scope="col">Fit Selectable</th>
            <th scope="col">Stock</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Sales Price</th>
            <th scope="col">Currency</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($activityInventories as $activityInventory)
            @include('partials.models.activity_inventories.row', [
              'activityInventory' => $activityInventory,
              'activity_id' => $activityInventory->activity_id,
              'ticket_type_id' => $activityInventory->ticket_type_id,
              'starts_at' => $activityInventory->starts_at,
              'ends_at' => $activityInventory->ends_at,
              'fit_selectable' => $activityInventory->fit_selectable,
              'stock' => $activityInventory->stock,
              'purchase_price' => $activityInventory->purchase_price,
              'sales_price' => $activityInventory->sales_price,
              'currency' => $activityInventory->currency,
              'notes' => $activityInventory->notes,
            ])
        @endforeach
    </table>
@endsection
