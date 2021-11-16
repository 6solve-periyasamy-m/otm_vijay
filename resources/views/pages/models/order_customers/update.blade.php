@extends('layout.form', ['action' => route('order-customers.update', ['order' => $order, 'orderCustomer' => $orderCustomer,]),])

@section('title', 'Update Orders Customer')

@section('form-body')
    @include('partials.models.order_customers.form', [
      'order_id' => $orderCustomer->order_id,
      'customer_id' => $orderCustomer->customer_id,
      'tour_cost' => $orderCustomer->tour_cost,
      'single_occupancy_surcharge' => $orderCustomer->single_occupancy_surcharge,
      'travel_insurer' => $orderCustomer->travel_insurer,
      'policy_number' => $orderCustomer->policy_number,
    ])
@endsection
