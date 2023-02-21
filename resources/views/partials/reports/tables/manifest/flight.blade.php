<table class="table table-striped report-table">
    <thead>
    <tr>
        <th scope="col">Booking Reference</th>
        <th scope="col">Customer Name</th>
        <th scope="col">Airline</th>
        <th scope="col">Flight Number</th>
        <th scope="col">Travel Class</th>
        <th scope="col">Departure Date</th>
        <th scope="col">Departure Time</th>
        <th scope="col">Departure Airport</th>
        <th scope="col">Arrival Date</th>
        <th scope="col">Arrival Time</th>
        <th scope="col">Arrival Airport</th>
        <th scope="col">Component Type</th>
        <th scope="col">Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <th scope="row">{{ $row->reference }}</th>
            <td>{{ $row->customer }}</td>
            <td>{{ $row->airline }}</td>
            <td>{{ $row->number }}</td>
            <td>{{ $row->ticket }}</td>
            <td>{{ f_date($row->start)}}</td>
            <td>{{ f_time($row->start)}}</td>
            <td>{{ $row->departure }}</td>
            <td>{{ f_date($row->end)}}</td>
            <td>{{ f_time($row->end)}}</td>
            <td>{{ $row->arrival }}</td>
            <td>{{ $row->component }}</td>
            <td>{{ $row->notes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
