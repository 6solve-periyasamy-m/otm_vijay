<tr>
    <td><a href="{{ route('accommodations.view', ['accommodation' => $accommodation,]) }}">{{ $name }}</a></td>
    <td>{{ $description }}</td>
    <td>{{ f_date($audit_date) }}</td>
    <td>{{ $address }}</td>
    <td>{{ $currency }}</td>
    <td class="actions">
        @can('update', \App\Models\Accommodation\Accommodation::class)
            <a href="{{route('accommodations.edit', ['accommodation' => $accommodation,])}}" class="btn btn-outline-success btn-sm mb-1">
                <x-icon icon="note" />
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
                <x-icon icon="note" />
            </span>
        @endcan
        @can('delete', \App\Models\Accommodation\Accommodation::class)
            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
               onclick="event.preventDefault();document.getElementById('accommodation-{{ $accommodation->id }}-delete').submit();">
               <x-icon icon="trash" />
            </a>
            <form id="accommodation-{{ $accommodation->id }}-delete"
                  action="{{ route('accommodations.delete', ['accommodation' => $accommodation,]) }}" method="POST"
                  style="display: none;">{{ csrf_field() }}</form>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
                <x-icon icon="trash" />
            </span>
        @endcan
    </td>
</tr>
