<tr>
    <td><a href="{{ route('airlines.view', ['airline' => $airline,]) }}">{{ $name }}</a></td>
    <td>
        <a href="{{route('airlines.edit', ['airline' => $airline,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('airline-{{ $airline->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="airline-{{ $airline->id }}-delete" action="{{ route('airlines.delete', ['airline' => $airline,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
