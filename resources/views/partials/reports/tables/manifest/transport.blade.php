<table class="datatable table table-striped report-table">
    <thead>
    <tr>
        <th scope="col">Booking Reference</th>
        <th scope="col">Customer Name</th>
        <th scope="col">Passport Name</th>
        <th scope="col">Operator</th>
        <th scope="col">Travel Class</th>
        <th scope="col">Transport Number</th>
        <th scope="col">Departure Date</th>
        <th scope="col">Departure Time</th>
        <th scope="col">Departure Address</th>
        <th scope="col">Arrival Date</th>
        <th scope="col">Arrival Time</th>
        <th scope="col">Arrival Address</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Component Type</th>
        <th scope="col">Customer Transport Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <th scope="row">{{ $row->reference }}</th>
            <td>{{ $row->customer }}</td>
            <td>{{ $row->passport }}</td>
            <td>{{ $row->operator }}</td>
            <td>{{ $row->ticket }}</td>
            <td>{{ $row->number }}</td>
            <td>{{ f_date($row->start)}}</td>
            <td>{{ f_time($row->start)}}</td>
            <td>{{ $row->departure->name }}</td>
            <td>{{ f_date($row->end)}}</td>
            <td>{{ f_time($row->end)}}</td>
            <td>{{ $row->arrival->name }}</td>
            <td>{{ f_currency($row->purchase) }}</td>
            <td>{{ f_currency($row->sales) }}</td>
            <td>{{ $row->component }}</td>
            <td>{{ $row->notes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
