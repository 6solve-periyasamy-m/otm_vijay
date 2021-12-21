<tr>
    <td><a href="{{ route('merchandise.view', ['merchandise' => $merchandise,]) }}">{{ $name }}</a></td>
    <td>
        <a href="{{route('merchandise.edit', ['merchandise' => $merchandise,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('merchandise-{{ $merchandise->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="merchandise-{{ $merchandise->id }}-delete"
              action="{{ route('merchandise.delete', ['merchandise' => $merchandise,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
