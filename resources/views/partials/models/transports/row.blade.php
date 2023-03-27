<tr>
    <td><a href="{{ route('transports.view', ['transport' => $transport,]) }}">{{ $name }}</a></td>
    <td>{{ $transport->transportType->name }}</td>
    <td>{{ $transport->operator->name }}</td>
    <td>{{ $transport->departureAddress->name }}</td>
    <td>{{ $transport->arrivalAddress->name }}</td>
    <td>{{ $description }}</td>
    <td>{{ $currency }}</td>
    <td>{{ $is_domestic ? "Yes" : "No" }}</td>
    <td>{{ $notes }}</td>
    <td class="actions-3">
        @can('create', \App\Models\Transport\Transport::class)
            <a href="{{route('transports.return', ['transport' => $transport,])}}" class="btn btn-outline-blue btn-sm mb-1">
                {{ Icon::returnTrip() }}
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            {{ Icon::returnTrip() }}
        </span>
        @endcan
        @can('update', \App\Models\Transport\Transport::class)
            <a href="{{route('transports.edit', ['transport' => $transport,])}}" class="btn btn-sm btn-outline-success mb-1">
                {{ Icon::edit() }}
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            {{ Icon::edit() }}
        </span>
        @endcan
        @can('delete', \App\Models\Transport\Transport::class)
            <a href="#" class="btn btn-sm btn-outline-danger mb-1"
               onclick="event.preventDefault();document.getElementById('transport-{{ $transport->id }}-delete').submit();">
                {{ Icon::delete() }}
            </a>
            <form id="transport-{{ $transport->id }}-delete"
                  action="{{ route('transports.delete', ['transport' => $transport,]) }}" method="POST"
                  style="display: none;">{{ csrf_field() }}</form>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            {{ Icon::delete() }}
        </span>
        @endcan
    </td>
</tr>
