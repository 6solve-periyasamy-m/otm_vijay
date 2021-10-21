@extends('layout.main')

@section('title', 'Update Orders Customer')

@section('content')
    @include('partials.models.orders_customers.form', ['action' => route('orders-customers.update', ['order' => $order, 'ordersCustomer' => $ordersCustomer,]),
      'order_id' => $ordersCustomer->order_id,
      'customer_id' => $ordersCustomer->customer_id,
      'tour_cost' => $ordersCustomer->tour_cost,
      'single_occupancy_surcharge' => $ordersCustomer->single_occupancy_surcharge,
      'travel_insurer' => $ordersCustomer->travel_insurer,
      'policy_number' => $ordersCustomer->policy_number,
    ])
@endsection
