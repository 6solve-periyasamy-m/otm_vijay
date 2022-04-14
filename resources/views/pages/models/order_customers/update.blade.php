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
      'internal_notes' => $orderCustomer->internal_notes,
      'external_notes' => $orderCustomer->external_notes,
      'accommodation_notes' => $orderCustomer->accommodation_notes,
      'activity_notes' => $orderCustomer->activity_notes,
      'flight_notes' => $orderCustomer->flight_notes,
      'transport_notes' => $orderCustomer->transport_notes,
    ])
@endsection
