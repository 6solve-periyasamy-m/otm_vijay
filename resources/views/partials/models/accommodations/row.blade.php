<tr>
    <td><a href="{{ route('accommodations.view', ['accommodation' => $accommodation,]) }}">{{ $title }}</a></td>
    <td>{{ $accommodation->region->name }}</td>
    <td>{{ $description }}</td>
    <td>{{ $audit_date }}</td>
    <td>{{ $address }}</td>
    <td>{{ $currency }}</td>
    <td>
        <a href="{{route('accommodations.edit', ['accommodation' => $accommodation,])}}" class="btn btn-outline-success btn-sm mb-1">
            <i class='icon-note'></i>   
        </a>
        <a href="#" class="btn btn-outline-danger btn-sm mb-1"
           onclick="event.preventDefault();document.getElementById('accommodation-{{ $accommodation->id }}-delete').submit();">
           <i class="icon-trash"></i>
        </a>
        <form id="accommodation-{{ $accommodation->id }}-delete"
              action="{{ route('accommodations.delete', ['accommodation' => $accommodation,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
