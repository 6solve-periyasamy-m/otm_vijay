<table class="table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">Booking Reference</th>
            <th scope="col">Tour Name</th>
            <th scope="col">Lead Booker First Name</th>
            <th scope="col">Lead Booker Last Name</th>
            <th scope="col">Payment Method</th>
            <th scope="col">Payment Type</th>
            <th scope="col">Payment Value</th>
            <th scope="col">Paid On</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row">{{ $row->booking_reference }}</th>
                <td>{{ $row->tour_name }}</td>
                <td>{{ $row->lb_first_name }}</td>
                <td>{{ $row->lb_last_name }}</td>
                <td>{{ $row->payment_method }}</td>
                <td>{{ $row->payment_type }}</td>
                <td>{{ $row->amount }}</td>
                <td>{{ $row->paid_on }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
