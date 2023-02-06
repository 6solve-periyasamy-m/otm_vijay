<tr>
    <td><a href="{{ route('flights.view', ['flight' => $flight,]) }}">{{ $flight->airline->name }}</a></td>
    <td>{{ $flight->departureAirport->name }}</td>
    <td>{{ $flight->arrivalAirport->name }}</td>
    <td>{{ $is_domestic ? "Domestic" : "International" }}</td>
    <td>{{ f_date($available_from) }}</td>
    <td>{{ $notes }}</td>
    <td class="actions-3">
        @can('create', \App\Models\Flight\Flight::class)
            <a href="{{route('flights.return', ['flight' => $flight,])}}" class="btn btn-outline-blue btn-sm mb-1">
                <x-icon icon="directions" />
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <x-icon icon="directions" />
        </span>
        @endcan
        @can('update', \App\Models\Flight\Flight::class)
            <a href="{{route('flights.edit', ['flight' => $flight,])}}" class="btn btn-outline-success btn-sm mb-1">
                <x-icon icon="note" />
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <x-icon icon="note" />
        </span>
        @endcan
        @can('delete', \App\Models\Flight\Flight::class)
            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
               onclick="event.preventDefault();document.getElementById('flight-{{ $flight->id }}-delete').submit();">
                <x-icon icon="trash" />
            </a>
            <form id="flight-{{ $flight->id }}-delete" action="{{ route('flights.delete', ['flight' => $flight,]) }}"
                  method="POST" style="display: none;">{{ csrf_field() }}</form>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <x-icon icon="trash" />
        </span>
        @endcan
    </td>
</tr>
