<tr>
    <td><a href="{{ route('travel-classes.view', ['travelClass' => $travelClass,]) }}">{{ $name }}</a></td>
    <td>
        <a href="{{route('travel-classes.edit', ['travelClass' => $travelClass,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('travelClass-{{ $travelClass->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="travelClass-{{ $travelClass->id }}-delete"
              action="{{ route('travel-classes.delete', ['travelClass' => $travelClass,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
