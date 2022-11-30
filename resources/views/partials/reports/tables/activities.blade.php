<table class="table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">Booking Reference</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Activity</th>
            <th scope="col">Starts</th>
            <th scope="col">Ends</th>
            <th scope="col">Ticket Type</th>
            <th scope="col">Purchased On</th>
            <th scope="col">Cost to Customer</th>
            <th scope="col">Component Type</th>
            <th scope="col">Activity Notes</th>
            <th scope="col">Internal Order-Customer Notes</th>
            <th scope="col">External Order-Customer Notes</th>
            <th scope="col">Internal Customer Notes</th>
            <th scope="col">External Customer Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row">{{ $row->reference }}</th>
                <td>{{ $row->customer }}</td>
                <td>{{ $row->activity }}</td>
                <td>{{ f_datetime($row->starts) }}</td>
                <td>{{ f_datetime($row->ends) }}</td>
                <td>{{ $row->ticket }}</td>
                <td>{{ $row->purchased }}</td>
                <td>{{ f_currency($row->cost) }}</td>
                <td>{{ $row->component }}</td>
                <td>{{ $row->activity_notes }}</td>
                <td>{{ $row->order_customer_notes_internal }}</td>
                <td>{{ $row->order_customer_notes_external }}</td>
                <td>{{ $row->customer_notes_internal }}</td>
                <td>{{ $row->customer_notes_external }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
