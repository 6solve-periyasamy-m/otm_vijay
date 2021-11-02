@extends('layout.main')

@section('title', 'View Orders Customer')

@section('content')
    Order Id: {{ $ordercustomer->order_id }}<br/>
    Customer Id: {{ $ordercustomer->customer_id }}<br/>
    Tour Cost: {{ $ordercustomer->tour_cost }}<br/>
    Single Occupancy Surcharge: {{ $ordercustomer->single_occupancy_surcharge }}<br/>
    Travel Insurer: {{ $ordercustomer->travel_insurer }}<br/>
    Policy Number: {{ $ordercustomer->policy_number }}<br/>
@endsection
