<tr>
    <td><a href="{{ route('hat-sizes.view', ['hatSize' => $hatSize,]) }}">{{ $name }}</a></td>
    <td>
        <a href="{{route('hat-sizes.edit', ['hatSize' => $hatSize,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('hatSize-{{ $hatSize->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="hatSize-{{ $hatSize->id }}-delete" action="{{ route('hat-sizes.delete', ['hatSize' => $hatSize,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
