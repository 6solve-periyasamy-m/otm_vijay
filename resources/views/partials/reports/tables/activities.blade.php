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
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row">{{ $row->reference }}</th>
                <td>{{ $row->customer }}</td>
                <td>{{ $row->activity }}</td>
                <td>{{ StringFormatter::formatDateTime($row->starts) }}</td>
                <td>{{ StringFormatter::formatDateTime($row->ends) }}</td>
                <td>{{ $row->ticket }}</td>

                <td>{{ $row->purchased }}</td>
                <td>{{ StringFormatter::formatCurrency($row->cost) }}</td>
                <td>{{ $row->component }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
