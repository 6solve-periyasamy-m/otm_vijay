<tr>
    <td><a href="{{ route('locations.view', ['location' => $location,]) }}">{{ $region_id }}</a></td>
    <td>{{ $location_type_id }}</td>
    <td>{{ $name }}</td>
    <td>{{ $address }}</td>
    <td>
        <a href="{{route('locations.edit', ['location' => $location,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('location-{{ $location->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="location-{{ $location->id }}-delete"
              action="{{ route('locations.delete', ['location' => $location,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
