<tr>
    <td><a href="{{ route('tours.view', ['tour' => $tour,]) }}">{{ $title }}</a></td>
    <td>{{ $event }}</td>
    <td>{{ $description }}</td>
    <td>{{ $date_from }}</td>
    <td>{{ $date_to }}</td>
    <td>{{ $base_price_per_person }}</td>
    <td>{{ $margin }}</td>
    <td>{{ $single_occupancy_surcharge }}</td>
    <td>{{ $stock_control_active ? "Yes" : "No" }}</td>
    <td>{{ $stock }}</td>
    <td>{{ $booking_form_url }}</td>
    <td>{{ $is_active ? "Yes" : "No" }}</td>
    <td>{{ $notes }}</td>
    <td>
        <a href="{{route('tours.edit', ['tour' => $tour,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#" onclick="event.preventDefault();document.getElementById('tour-{{ $tour->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="tour-{{ $tour->id }}-delete" action="{{ route('tours.delete', ['tour' => $tour,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
