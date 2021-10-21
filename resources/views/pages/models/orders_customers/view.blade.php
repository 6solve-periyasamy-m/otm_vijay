@extends('layout.main')

@section('title', 'View Orders Customer')

@section('content')
    Order Id: {{ $ordersCustomer->order_id }}<br/>
    Customer Id: {{ $ordersCustomer->customer_id }}<br/>
    Tour Cost: {{ $ordersCustomer->tour_cost }}<br/>
    Single Occupancy Surcharge: {{ $ordersCustomer->single_occupancy_surcharge }}<br/>
    Travel Insurer: {{ $ordersCustomer->travel_insurer }}<br/>
    Policy Number: {{ $ordersCustomer->policy_number }}<br/>
@endsection
