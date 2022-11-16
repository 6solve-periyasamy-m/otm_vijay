<table class="table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tour</th>
            <th scope="col">Hotel</th>
            <th scope="col">Room Type</th>
            <th scope="col">Board Type</th>
            <th scope="col">Check In</th>
            <th scope="col">Check Out</th>
            <th scope="col">Occupant Count</th>
            <th scope="col">Empty Beds</th>
            <th scope="col" colspan="{{$data->largest}}">Occupants</th>
            <th scope="col" colspan="{{$data->largest}}">Accommodation Notes</th>
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp
        @foreach($data->data as $row)
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $row->tour }}</td>
                <td>{{ $row->hotel }}</td>
                <td>{{ $row->room }}</td>
                <td>{{ $row->board }}</td>
                <td>{{ $row->from }}</td>
                <td>{{ $row->to }}</td>
                {{-- Exporter strips 0 values for some reason, hence formatting with decimal place --}}
                <td>{{ $row->occupants == 0 ? number_format(0, 2) : $row->occupants }}</td>
                <td>{{ $row->empty_beds == 0 ? number_format(0, 2) : $row->empty_beds }}</td>
                @foreach($row->travellers as $traveller)
                    <td>{{ $traveller->customer?->first_name ?? 'Redacted' }} {{ $traveller->customer?->last_name ?? 'Redacted' }}</td>
                @endforeach
                @foreach($row->travellers as $traveller)
                    <td>{{ $traveller->accommodation_notes ?? '' }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
