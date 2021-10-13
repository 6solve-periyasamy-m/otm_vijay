<tr>
    <td><a href="{{ route('regions.view', ['region' => $region,]) }}">{{ $country_id }}</a></td>
    <td>{{ $name }}</td>
    <td>
        <a href="{{route('regions.edit', ['region' => $region,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('region-{{ $region->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="region-{{ $region->id }}-delete" action="{{ route('regions.delete', ['region' => $region,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
