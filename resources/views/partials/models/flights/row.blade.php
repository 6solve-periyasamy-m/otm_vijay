<tr>
    <td><a href="{{ route('flights.view', ['flight' => $flight,]) }}">{{ $flight->airline->name }}</a></td>
    <td>{{ $flight->departureAirport->name }}</td>
    <td>{{ $flight->arrivalAirport->name }}</td>
    <td>{{ $is_domestic ? "Domestic" : "International" }}</td>
    <td>{{ $available_after }}</td>
    <td>{{ $notes }}</td>
    <td>
        <a href="{{route('flights.edit', ['flight' => $flight,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('flight-{{ $flight->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="flight-{{ $flight->id }}-delete" action="{{ route('flights.delete', ['flight' => $flight,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
