<table class="datatable table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">Order Date</th>
            <th scope="col">Booking Reference</th>
            <th scope="col">Lead Booker First Name</th>
            <th scope="col">Lead Booker Last Name</th>
            <th scope="col">Lead Booker Email</th>
            <th scope="col">Passenger Count</th>
            <th scope="col">Tour Name</th>
            <th scope="col">Event Name</th>
            <th scope="col">Total Order Value</th>
            <th scope="col">Balance Paid</th>
            <th scope="col">Balance Outstanding</th>
            <th scope="col">Final Payment Due</th>
            <th scope="col">Final Payment Amount</th>
            <th scope="col">Final Payment Paid</th>
            <th scope="col">Final Payment Remaining</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->ordered_on}}</td>
                <th scope="row">{{ $row->booking_reference }}</th>
                <td>{{ $row->lb_first_name }}</td>
                <td>{{ $row->lb_last_name }}</td>
                <td>{{ $row->lb_email }}</td>
                <td>{{ $row->customer_count }}</td>
                <td>{{ $row->tour_name }}</td>
                <td>{{ $row->event_name }}</td>
                <td>{{ $row->total_order_value }}</td>
                <td>{{ $row->balance_paid }}</td>
                <td>{{ $row->balance_outstanding }}</td>
                <td>{{ f_date($row->due) }}</td>
                <td>{{ $row->final_amount }}</td>
                <td>{{ $row->final_paid }}</td>
                <td>{{ $row->final_remaining }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
