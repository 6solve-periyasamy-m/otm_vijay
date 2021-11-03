@extends('layout.master')

@section('title', 'Update Orders Customer')

@section('content')
    @include('partials.models.orders_customers.form', ['action' => route('orders-customers.update', ['order' => $order, 'orderCustomer' => $orderCustomer,]),
      'order_id' => $orderCustomer->order_id,
      'customer_id' => $orderCustomer->customer_id,
      'tour_cost' => $orderCustomer->tour_cost,
      'single_occupancy_surcharge' => $orderCustomer->single_occupancy_surcharge,
      'travel_insurer' => $orderCustomer->travel_insurer,
      'policy_number' => $orderCustomer->policy_number,
    ])
@endsection
