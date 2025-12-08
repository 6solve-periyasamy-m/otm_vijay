<table class="datatable table table-striped report-table">
    <thead>
    <tr>
        <th scope="col">Booking Reference</th>
        <th scope="col">Event</th>
        <th scope="col">Booking Travellers</th>
        <th scope="col">Customer Name</th>
        <th scope="col">Passport Name</th>
        <th scope="col">Order Internal Notes</th>
        <th scope="col">Order External Notes</th>
        <th scope="col">Operator</th>
        <th scope="col">Travel Class</th>
        <th scope="col">Transport Number</th>
        <th scope="col">Name</th>
        <th scope="col">Departure Date</th>
        <th scope="col">Departure Time</th>
        <th scope="col">Departure Address</th>
        <th scope="col">Arrival Date</th>
        <th scope="col">Arrival Time</th>
        <th scope="col">Arrival Address</th>
        <th scope="col">Purchase Currency</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Currency</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Component Type</th>
        <th scope="col">Customer Transport Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <th scope="row">{{ $row->reference }}</th>
            <td>{{ $row->event }}</td>
            <td>{{ $row->travellers }}</td>
            <td>{{ $row->customer }}</td>
            <td>{{ $row->passport }}</td>
            <td>{{ $row->orderInternal }}</td>
            <td>{{ $row->orderExternal }}</td>
            <td>{{ $row->operator }}</td>
            <td>{{ $row->ticket }}</td>
            <td>{{ $row->number }}</td>
            <td>{{ $row->transport }}</td>
            <td>{{ f_date($row->start)}}</td>
            <td>{{ f_time($row->start)}}</td>
            <td>{{ $row->departure->name }}</td>
            <td>{{ f_date($row->end)}}</td>
            <td>{{ f_time($row->end)}}</td>
            <td>{{ $row->arrival->name }}</td>
            <td>{{ $row->purchase_currency }}</td>
            <td>{{ fr_currency($row->purchase, $row->purchase_currency) }}</td>
            <td>{{ $row->sales_currency }}</td>
            <td>{{ f_currency($row->sales, $row->sales_currency) }}</td>
            <td>{{ $row->component }}</td>
            <td>{{ $row->notes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
