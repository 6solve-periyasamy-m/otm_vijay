<tr>
    <td><a href="{{ route('t-shirt-sizes.view', ['tShirtSize' => $tShirtSize,]) }}">{{ $name }}</a></td>
    <td>
        <a href="{{route('t-shirt-sizes.edit', ['tShirtSize' => $tShirtSize,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('tShirtSize-{{ $tShirtSize->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="tShirtSize-{{ $tShirtSize->id }}-delete"
              action="{{ route('t-shirt-sizes.delete', ['tShirtSize' => $tShirtSize,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
