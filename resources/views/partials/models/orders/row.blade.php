<tr>
    <td><a href="{{ route('orders.view', ['order' => $order,]) }}">{{ $quote_id }}</a></td>
    <td>{{ $tour_id }}</td>
    <td>{{ $lead_booker_id }}</td>
    <td>{{ $token }}</td>
    <td>{{ $booking_reference }}</td>
    <td>{{ $ordered_on }}</td>
    <td>{{ $internal_notes }}</td>
    <td>{{ $external_notes }}</td>
    <td>
        <a href="{{route('orders.edit', ['order' => $order,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#" onclick="event.preventDefault();document.getElementById('order-{{ $order->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="order-{{ $order->id }}-delete" action="{{ route('orders.delete', ['order' => $order,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
