<tr>
    <td><a href="{{ route('airports.view', ['airport' => $airport,]) }}">{{ $name }}</a></td>
    <td>{{ $iata_code }}</td>
    <td class="actions">
        <a href="{{route('airports.edit', ['airport' => $airport,])}}" class="btn btn-outline-success mb-1 btn-sm">
            <i class="icon-note"></i>
        </a>
        <a href="#" class="btn btn-outline-danger mb-1 btn-sm"
           onclick="event.preventDefault();document.getElementById('airport-{{ $airport->id }}-delete').submit();">
            <i class="icon-trash"></i>
        </a>
        <form id="airport-{{ $airport->id }}-delete" action="{{ route('airports.delete', ['airport' => $airport,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
