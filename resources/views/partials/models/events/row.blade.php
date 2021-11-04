<tr>
    <td><a href="{{ route('events.view', ['event' => $event,]) }}">{{ $name }}</a></td>
    <td>{{ $description }}</td>
    <td>{{ $starts_at }}</td>
    <td>{{ $ends_at }}</td>
    <td>{{ $booking_url }}</td>
    <td>{{ $notes }}</td>
    <td>
        <a href="{{route('events.edit', ['event' => $event,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#" onclick="event.preventDefault();document.getElementById('event-{{ $event->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="event-{{ $event->id }}-delete" action="{{ route('events.delete', ['event' => $event,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
