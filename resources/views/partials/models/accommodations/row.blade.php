<tr>
    <td><a href="{{ route('accommodations.view', ['accommodation' => $accommodation,]) }}">{{ $name }}</a></td>
    <td>{{ $description }}</td>
    <td>{{ StringFormatter::formatDate($audit_date) }}</td>
    <td>{{ $address }}</td>
    <td>{{ $currency }}</td>
    <td class="actions">
        @can('update', \App\Models\Accommodation::class)
            <a href="{{route('accommodations.edit', ['accommodation' => $accommodation,])}}" class="btn btn-outline-success btn-sm mb-1">
                <i class="icon-note"></i>
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
                <i class="icon-note"></i>
            </span>
        @endcan
        @can('delete', \App\Models\Accommodation::class)
            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
               onclick="event.preventDefault();document.getElementById('accommodation-{{ $accommodation->id }}-delete').submit();">
               <i class="icon-trash"></i>
            </a>
            <form id="accommodation-{{ $accommodation->id }}-delete"
                  action="{{ route('accommodations.delete', ['accommodation' => $accommodation,]) }}" method="POST"
                  style="display: none;">{{ csrf_field() }}</form>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
                <i class="icon-trash"></i>
            </span>
        @endcan
    </td>
</tr>
