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
        @can('create', \App\Models\Transport::class)
            <a href="{{route('transports.return', ['transport' => $transport,])}}" class="btn btn-outline-blue btn-sm mb-1">
                <i class="icon-directions"></i>
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <i class="icon-directions"></i>
        </span>
        @endcan
        @can('update', \App\Models\Transport::class)
            <a href="{{route('transports.edit', ['transport' => $transport,])}}" class="btn btn-sm btn-outline-success mb-1">
                <i class="icon-note"></i>
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <i class="icon-note"></i>
        </span>
        @endcan
        @can('delete', \App\Models\Transport::class)
            <a href="#" class="btn btn-sm btn-outline-danger mb-1"
               onclick="event.preventDefault();document.getElementById('transport-{{ $transport->id }}-delete').submit();">
                <i class="icon-trash"></i>
            </a>
            <form id="transport-{{ $transport->id }}-delete"
                  action="{{ route('transports.delete', ['transport' => $transport,]) }}" method="POST"
                  style="display: none;">{{ csrf_field() }}</form>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <i class="icon-trash"></i>
        </span>
        @endcan
    </td>
</tr>
