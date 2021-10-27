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
        <a href="{{route('transports.edit', ['transport' => $transport,])}}" class="btn btn-sm btn-outline-success mb-1">            
            <i class="icon-note"></i>
        </a>
        <a href="#" class="btn btn-sm btn-outline-danger mb-1"
           onclick="event.preventDefault();document.getElementById('transport-{{ $transport->id }}-delete').submit();">
            <i class="icon-trash"></i>
        </a>
        <form id="transport-{{ $transport->id }}-delete"
              action="{{ route('transports.delete', ['transport' => $transport,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
