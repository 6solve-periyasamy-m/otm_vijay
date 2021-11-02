<tr>
    <td><a href="{{ route('order-customers.view', ['order' => $order, 'ordercustomer' => $ordercustomer,]) }}">{{ $order_id }}</a></td>
    <td>{{ $customer_id }}</td>
    <td>{{ $tour_cost }}</td>
    <td>{{ $single_occupancy_surcharge }}</td>
    <td>{{ $travel_insurer }}</td>
    <td>{{ $policy_number }}</td>
    <td>
        <a href="{{route('order-customers.edit', ['order' => $order, 'ordercustomer' => $ordercustomer,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('ordercustomer-{{ $ordercustomer->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="ordercustomer-{{ $ordercustomer->id }}-delete"
              action="{{ route('order-customers.delete', ['order' => $order, 'ordercustomer' => $ordercustomer,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
