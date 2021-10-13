<tr>
    <td><a href="{{ route('addresses.view', ['address' => $address,]) }}">{{ $address_line_1 }}</a></td>
    <td>{{ $address_line_2 }}</td>
    <td>{{ $town }}</td>
    <td>{{ $region }}</td>
    <td>{{ $country }}</td>
    <td>{{ $postcode }}</td>
    <td>
        <a href="{{route('addresses.edit', ['address' => $address,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('address-{{ $address->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="address-{{ $address->id }}-delete" action="{{ route('addresses.delete', ['address' => $address,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
