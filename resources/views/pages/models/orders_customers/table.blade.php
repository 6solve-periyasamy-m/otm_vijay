@extends('layout.main')

@section('title', 'All Orders Customers')

@section('content')
    <a class="btn btn-primary" href="{{ route('order-customers.create') }}">Create New</a>
    <table id="ordercustomer" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Order Id</th>
            <th scope="col">Customer Id</th>
            <th scope="col">Tour Cost</th>
            <th scope="col">Single Occupancy Surcharge</th>
            <th scope="col">Travel Insurer</th>
            <th scope="col">Policy Number</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($ordercustomers as $ordercustomer)
            @include('partials.models.order_customers.row', [
              'ordercustomer' => $ordercustomer,
              'order_id' => $ordercustomer->order_id,
              'customer_id' => $ordercustomer->customer_id,
              'tour_cost' => $ordercustomer->tour_cost,
              'single_occupancy_surcharge' => $ordercustomer->single_occupancy_surcharge,
              'travel_insurer' => $ordercustomer->travel_insurer,
              'policy_number' => $ordercustomer->policy_number,
            ])
        @endforeach
    </table>
@endsection
