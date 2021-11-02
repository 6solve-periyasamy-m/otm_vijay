@extends('layout.main')

@section('title', 'View Orders Customer')

@section('content')
    Order Id: {{ $orderCustomer->order_id }}<br/>
    Customer Id: {{ $orderCustomer->customer_id }}<br/>
    Tour Cost: {{ $orderCustomer->tour_cost }}<br/>
    Single Occupancy Surcharge: {{ $orderCustomer->single_occupancy_surcharge }}<br/>
    Travel Insurer: {{ $orderCustomer->travel_insurer }}<br/>
    Policy Number: {{ $orderCustomer->policy_number }}<br/>
@endsection
