<tr>
    <td><a href="{{ route('transports.view', ['transport' => $transport,]) }}">{{ $transport_type_id }}</a></td>
    <td>{{ $operator_id }}</td>
    <td>{{ $departure_location_id }}</td>
    <td>{{ $arrival_location_id }}</td>
    <td>{{ $name }}</td>
    <td>{{ $description }}</td>
    <td>{{ $currency }}</td>
    <td>{{ $is_domestic }}</td>
    <td>{{ $notes }}</td>
    <td>
        <a href="{{route('transports.edit', ['transport' => $transport,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('transport-{{ $transport->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="transport-{{ $transport->id }}-delete"
              action="{{ route('transports.delete', ['transport' => $transport,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
