<tr>
    <td><a href="{{ route('flights.view', ['flight' => $flight,]) }}">{{ $flight->airline->name }}</a></td>
    <td>{{ $flight->departureAirport->name }}</td>
    <td>{{ $flight->arrivalAirport->name }}</td>
    <td>{{ $is_domestic ? "Domestic" : "International" }}</td>
    <td>{{ StringFormatter::formatDate($available_after) }}</td>
    <td>{{ $notes }}</td>
    <td class="actions-3">
        @can('create', \App\Models\Flight::class)
            <a href="{{route('flights.return', ['flight' => $flight,])}}" class="btn btn-outline-blue btn-sm mb-1">
                <i class="icon-directions"></i>
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <i class="icon-directions"></i>
        </span>
        @endcan
        @can('update', \App\Models\Flight::class)
            <a href="{{route('flights.edit', ['flight' => $flight,])}}" class="btn btn-outline-success btn-sm mb-1">
                <i class="icon-note"></i>
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <i class="icon-note"></i>
        </span>
        @endcan
        @can('delete', \App\Models\Flight::class)
            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
               onclick="event.preventDefault();document.getElementById('flight-{{ $flight->id }}-delete').submit();">
                <i class="icon-trash"></i>
            </a>
            <form id="flight-{{ $flight->id }}-delete" action="{{ route('flights.delete', ['flight' => $flight,]) }}"
                  method="POST" style="display: none;">{{ csrf_field() }}</form>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <i class="icon-trash"></i>
        </span>
        @endcan
    </td>
</tr>
