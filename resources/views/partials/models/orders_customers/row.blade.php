<tr>
  <td><a href="{{ route('ordersCustomers.view', ['ordersCustomer' => $ordersCustomer,]) }}">{{ $order_id }}</a></td>
  <td>{{ $customer_id }}</td>
  <td>{{ $tour_cost }}</td>
  <td>{{ $single_occupancy_surcharge }}</td>
  <td>{{ $travel_insurer }}</td>
  <td>{{ $policy_number }}</td>
  <td>
    <a href="{{route('orders_customers.edit', ['ordersCustomer' => $ordersCustomer,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('ordersCustomer-{{ $ordersCustomer->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="ordersCustomer-{{ $ordersCustomer->id }}-delete" action="{{ route('orders_customers.delete', ['ordersCustomer' => $ordersCustomer,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
