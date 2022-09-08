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
            <th scope="col">Occupants</th>
        </tr>
    </thead>
    <tbody>
        @php $count = 0; @endphp
        @foreach($data as $row)
            @php $count++; @endphp
            <tr>
                <td>{{ $count }}</td>
                <td>{{ $row->tour }}</td>
                <td>{{ $row->hotel }}</td>
                <td>{{ $row->room }}</td>
                <td>{{ $row->board }}</td>
                <td>{{ $row->from }}</td>
                <td>{{ $row->to }}</td>
                <td>{{ $row->occupants }}</td>
                <td>
                    @php /** @var \App\Models\Order\OrderCustomer $traveller */ @endphp
                    @foreach($row->travellers as $traveller)
                        {{ $traveller->customer?->first_name ?? 'Redacted' }} {{ $traveller->customer?->last_name ?? 'Redacted' }}@isset($traveller->customer?->email_address) ({{ $traveller->customer?->email_address }})@endisset,
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
