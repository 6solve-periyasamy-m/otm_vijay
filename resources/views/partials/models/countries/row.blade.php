<tr>
    <td><a href="{{ route('countries.view', ['country' => $country,]) }}">{{ $name }}</a></td>
    <td>{{ $numeric_code }}</td>
    <td>{{ $alpha_code }}</td>
    <td>{{ $dialing_code }}</td>
    <td>{{ $currency }}</td>
    <td>
        <a href="{{route('countries.edit', ['country' => $country,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('country-{{ $country->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="country-{{ $country->id }}-delete" action="{{ route('countries.delete', ['country' => $country,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
