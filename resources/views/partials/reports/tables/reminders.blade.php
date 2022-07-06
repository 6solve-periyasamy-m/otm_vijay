<table class="table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">Booking Reference</th>
            <th scope="col">Lead Booker</th>
            <th scope="col">Email Address</th>
            <th scope="col">Phone Number</th>
            <th scope="col">Days Until</th>
            <th scope="col">Due On</th>
            <th scope="col">Amount</th>
            <th scope="col">Reminded?</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row">{{ $row->order->booking_reference }}</th>
                <td>{{ $row->order->lead_booker_name }}</td>
                <td>{{ $row->order->leadBooker->customer->email_address }}</td>
                <td>{{ $row->order->leadBooker->customer->mobile_number }}</td>
                <td>{{ $row->days }}</td>
                <td>{{ f_date($row->next->due_on) }}</td>
                <td>{{ f_currency($row->next->amount) }}</td>
                <td>{{ f_bool($row->reminded) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
