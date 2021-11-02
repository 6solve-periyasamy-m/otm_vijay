<tr>
    <td><a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $orderCustomer,]) }}">{{ $order_id }}</a></td>
    <td>{{ $customer_id }}</td>
    <td>{{ $tour_cost }}</td>
    <td>{{ $single_occupancy_surcharge }}</td>
    <td>{{ $travel_insurer }}</td>
    <td>{{ $policy_number }}</td>
    <td>
        <a href="{{route('order-customers.edit', ['order' => $order, 'orderCustomer' => $orderCustomer,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('orderCustomer-{{ $orderCustomer->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="orderCustomer-{{ $orderCustomer->id }}-delete"
              action="{{ route('order-customers.delete', ['order' => $order, 'orderCustomer' => $orderCustomer,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
