<tr>
    <td><a href="{{ route('orders-customers.view', ['order' => $order, 'ordersCustomer' => $ordersCustomer,]) }}">{{ $order_id }}</a></td>
    <td>{{ $customer_id }}</td>
    <td>{{ $tour_cost }}</td>
    <td>{{ $single_occupancy_surcharge }}</td>
    <td>{{ $travel_insurer }}</td>
    <td>{{ $policy_number }}</td>
    <td>
        <a href="{{route('orders-customers.edit', ['order' => $order, 'ordersCustomer' => $ordersCustomer,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('ordersCustomer-{{ $ordersCustomer->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="ordersCustomer-{{ $ordersCustomer->id }}-delete"
              action="{{ route('orders-customers.delete', ['order' => $order, 'ordersCustomer' => $ordersCustomer,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
