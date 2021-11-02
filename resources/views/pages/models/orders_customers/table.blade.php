@extends('layout.main')

@section('title', 'All Orders Customers')

@section('content')
    <a class="btn btn-primary" href="{{ route('order-customers.create') }}">Create New</a>
    <table id="orderCustomer" style="width: 100%;" class="table table-striped">
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
        @foreach($orderCustomers as $orderCustomer)
            @include('partials.models.order_customers.row', [
              'orderCustomer' => $orderCustomer,
              'order_id' => $orderCustomer->order_id,
              'customer_id' => $orderCustomer->customer_id,
              'tour_cost' => $orderCustomer->tour_cost,
              'single_occupancy_surcharge' => $orderCustomer->single_occupancy_surcharge,
              'travel_insurer' => $orderCustomer->travel_insurer,
              'policy_number' => $orderCustomer->policy_number,
            ])
        @endforeach
    </table>
@endsection
