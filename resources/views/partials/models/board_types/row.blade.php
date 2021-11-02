<tr>
    <td><a href="{{ route('board-types.view', ['boardType' => $boardType,]) }}">{{ $name }}</a></td>
    <td>
        <a href="{{route('board-types.edit', ['boardType' => $boardType,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('boardType-{{ $boardType->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="boardType-{{ $boardType->id }}-delete"
              action="{{ route('board-types.delete', ['boardType' => $boardType,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
