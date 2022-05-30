<table class="table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">Flight Number</th>
            <th scope="col">Departure Airport</th>
            <th scope="col">Departs At</th>
            <th scope="col">Arrival Airport</th>
            <th scope="col">Arrives At</th>
            <th scope="col">Booking Reference</th>
            <th scope="col">Tour</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Is Lead?</th>
            <th scope="col">Flight Notes</th>
            <th scope="col">Internal Order-Customer Notes</th>
            <th scope="col">External Order-Customer Notes</th>
            <th scope="col">Internal Customer Notes</th>
            <th scope="col">External Customer Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row">{{ $row->flight_number }}</th>
                <td>{{ $row->departs }}</td>
                <td>{{ f_datetime($row->depart_time) }}</td>
                <td>{{ $row->arrival }}</td>
                <td>{{ f_datetime($row->arrive_time) }}</td>
                <td>{{ $row->reference }}</td>
                <td>{{ $row->tour }}</td>
                <td>{{ $row->customer }}</td>
                <td>{{ f_bool($row->is_lead) }}</td>
                <td>{{ $row->flight_notes }}</td>
                <td>{{ $row->order_customer_notes_internal }}</td>
                <td>{{ $row->order_customer_notes_external }}</td>
                <td>{{ $row->customer_notes_internal }}</td>
                <td>{{ $row->customer_notes_external }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
