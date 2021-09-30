@extends('layout.main')

@section('title', 'Update Orders Customers')

@section('content')
<a class="btn btn-primary" href="{{ route('orders_customers.create') }}">Create New</a><table id="ordersCustomer" style="width: 100%;" class="table table-striped">
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
  @foreach($ordersCustomers as $ordersCustomer)
    @include('partials.models.orders_customers.row', [
      'ordersCustomer' => $ordersCustomer,
      'order_id' => $ordersCustomer->order_id,
      'customer_id' => $ordersCustomer->customer_id,
      'tour_cost' => $ordersCustomer->tour_cost,
      'single_occupancy_surcharge' => $ordersCustomer->single_occupancy_surcharge,
      'travel_insurer' => $ordersCustomer->travel_insurer,
      'policy_number' => $ordersCustomer->policy_number,
    ])
  @endforeach
</table>
@endsection
