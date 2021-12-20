<tr>
    <td><a href="{{ route('tours.view', ['tour' => $tour->id,]) }}">{{ $tour->name }}</a></td>
    <td>{{ $event }}</td>
    <td>{{ isset($tour->category) ? $tour->category->name : "No Category" }}</td>
    <td>{{ $description }}</td>
    <td>{{ StringFormatter::formatDate($date_from) }}</td>
    <td>{{ StringFormatter::formatDate($date_to) }}</td>
    <td>{{ StringFormatter::formatCurrency($base_price_per_person) }}</td>
    <td>{{ StringFormatter::formatCurrency($margin) }}</td>
    <td>{{ StringFormatter::formatCurrency($deposit) }}</td>
    <td>{{ StringFormatter::formatCurrency($single_occupancy_surcharge) }}</td>
    <td>{{ $stock_control_active ? "Yes" : "No" }}</td>
    <td>{{ $stock }}</td>
    <td>{{ $booking_form_url }}</td>
    <td>{{ $is_active ? "Yes" : "No" }}</td>
    <td>{{ $notes }}</td>
    <td class="actions">
        <a href="{{route('tours.edit', ['tour' => $tour,])}}" class="btn btn-outline-success btn-sm mb-1">
            <i class="icon-note"></i>            
        </a>
        <a href="#" onclick="event.preventDefault();document.getElementById('tour-{{ $tour->id }}-delete').submit();" class="btn btn-outline-danger btn-sm mb-1">
            <i class="icon-trash"></i>
        </a>
        <form id="tour-{{ $tour->id }}-delete" action="{{ route('tours.delete', ['tour' => $tour,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
