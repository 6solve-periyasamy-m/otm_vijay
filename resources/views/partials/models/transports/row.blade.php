<tr>
    <td><a href="{{ route('transports.view', ['transport' => $transport,]) }}">{{ $name }}</a></td>
    <td>{{ $transport->transportType->name }}</td>
    <td>{{ $transport->operator->name }}</td>
    <td>{{ $transport->departureLocation->name }}</td>
    <td>{{ $transport->arrivalLocation->name }}</td>
    <td>{{ $description }}</td>
    <td>{{ $currency }}</td>
    <td>{{ $is_domestic ? "Yes" : "No" }}</td>
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
