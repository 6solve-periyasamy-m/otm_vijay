<tr>
    <td><a href="{{ route('operators.view', ['operator' => $operator,]) }}">{{ $name }}</a></td>
    <td>{{ $notes }}</td>
    <td>
        <a href="{{route('operators.edit', ['operator' => $operator,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('operator-{{ $operator->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="operator-{{ $operator->id }}-delete"
              action="{{ route('operators.delete', ['operator' => $operator,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
