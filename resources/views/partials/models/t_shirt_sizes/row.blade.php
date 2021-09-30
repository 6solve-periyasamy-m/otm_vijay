<tr>
  <td><a href="{{ route('tShirtSizes.view', ['tShirtSize' => $tShirtSize,]) }}">{{ $name }}</a></td>
  <td>
    <a href="{{route('t_shirt_sizes.edit', ['tShirtSize' => $tShirtSize,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('tShirtSize-{{ $tShirtSize->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="tShirtSize-{{ $tShirtSize->id }}-delete" action="{{ route('t_shirt_sizes.delete', ['tShirtSize' => $tShirtSize,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
