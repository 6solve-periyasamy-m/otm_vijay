<tr>
    <td><a href="{{ route('tours.view', ['tour' => $tour->id,]) }}">{{ $tour->name }}</a></td>
    <td>{{ isset($tour->event) ? $tour->event->name : "No Event" }}</td>
    <td>{{ isset($tour->category) ? $tour->category->name : "No Category" }}</td>
    <td>{{ $description }}</td>
    <td>{{ f_date($date_from) }}</td>
    <td>{{ f_date($date_to) }}</td>
    <td>{{ f_currency($base_price_per_person) }}</td>
    <td>{{ f_currency($margin) }}</td>
    <td>{{ f_currency($deposit) }}</td>
    <td>{{ f_currency($single_occupancy_surcharge) }}</td>
    <td>{{ $stock_control_active ? "Yes" : "No" }}</td>
    <td>
        @if($stock_control_active)
            {{$tour->stock - $tour->getUsedStock()}}/{{ $tour->stock }}<br/>
            ({{$tour->getUsedStock()}} Sold)
        @else
            {{$tour->getUsedStock()}} Sold
        @endif
    </td>
    <td>{{ $booking_form_url }}</td>
    <td>{{ $is_active ? "Yes" : "No" }}</td>
    <td>{{ $notes }}</td>
    <td class="actions-3">
        <a href="{{route('tours.duplicate', ['tour' => $tour,])}}" class="btn btn-outline-info btn-sm mb-1">
            <i class="icon-layers"></i>
        </a>
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
