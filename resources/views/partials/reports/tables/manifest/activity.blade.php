<table class="datatable table table-striped report-table">
    <thead>
    <tr>
        <th scope="col">Booking Reference</th>
        <th scope="col">Customer Name</th>
        <th scope="col">Passport Name</th>
        <th scope="col">Email Address</th>
        <th scope="col">Activity</th>
        <th scope="col">Type</th>
        <th scope="col">Ticket</th>
        <th scope="col">Component Type</th>
        <th scope="col">Start Date</th>
        <th scope="col">Start Time</th>
        <th scope="col">End Date</th>
        <th scope="col">End Time</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Customer Activity Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <th scope="row">{{ $row->reference }}</th>
            <td>{{ $row->customer }}</td>
            <td>{{ $row->passport }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->activity }}</td>
            <td>{{ $row->type }}</td>
            <td>{{ $row->ticket }}</td>
            <td>{{ $row->component }}</td>
            <td>{{ f_date($row->start)}}</td>
            <td>{{ f_time($row->start)}}</td>
            <td>{{ f_date($row->end)}}</td>
            <td>{{ f_time($row->end)}}</td>
            <td>{{ f_currency($row->purchase) }}</td>
            <td>{{ f_currency($row->sales) }}</td>
            <td>{{ $row->notes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
