<tr>
    <td><a href="{{ route('accommodations.view', ['accommodation' => $accommodation,]) }}">{{ $region_id }}</a></td>
    <td>{{ $title }}</td>
    <td>{{ $description }}</td>
    <td>{{ $audit_date }}</td>
    <td>{{ $address }}</td>
    <td>{{ $currency }}</td>
    <td>
        <a href="{{route('accommodations.edit', ['accommodation' => $accommodation,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('accommodation-{{ $accommodation->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="accommodation-{{ $accommodation->id }}-delete"
              action="{{ route('accommodations.delete', ['accommodation' => $accommodation,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
