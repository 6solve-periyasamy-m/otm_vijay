<tr>
    <td><a href="{{ route('tour-categories.view', ['tourCategory' => $tourCategory,]) }}">{{ $name }}</a></td>
    <td>
        <a href="{{route('tour-categories.edit', ['tourCategory' => $tourCategory,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('tourCategory-{{ $tourCategory->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="tourCategory-{{ $tourCategory->id }}-delete" action="{{ route('tour-categories.delete', ['tourCategory' => $tourCategory,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
