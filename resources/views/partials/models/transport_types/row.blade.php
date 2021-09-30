<tr>
  <td><a href="{{ route('transportTypes.view', ['transportType' => $transportType,]) }}">{{ $name }}</a></td>
  <td>
    <a href="{{route('transport_types.edit', ['transportType' => $transportType,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('transportType-{{ $transportType->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="transportType-{{ $transportType->id }}-delete" action="{{ route('transport_types.delete', ['transportType' => $transportType,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
