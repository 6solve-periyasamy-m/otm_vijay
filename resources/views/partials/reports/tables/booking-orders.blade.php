<table class="datatable table table-striped report-table" data-order='[[ 0, "desc" ]]'>
    <thead>
        <tr>
            <th scope="col"><strong>Ordered On</strong></th>
            <th scope="col"><strong>Booking Reference</strong></th>
            <th scope="col"><strong>Tour</strong></th>
            <th scope="col"><strong>Event</strong></th>
            <th scope="col"><strong>Lead Traveller Name</strong></th>
            <th scope="col"><strong>Lead Traveller Email</strong></th>
            <th scope="col"><strong>Passengers</strong></th>
            <th scope="col"><strong>Order Total</strong></th>
            <th scope="col"><strong>Balance Paid</strong></th>
            <th scope="col"><strong>Profit</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            @php $leadTraveller = $row->lb_first_name . " " . $row->lb_last_name; @endphp
            <tr>
                <th scope="row">{{ $row->ordered_on }}</th>
                <td><a href="{{route('orders.view', ['order' => $row->order_id,])}}" class="link link-primary">{{ $row->booking_reference }}</a></td>
                <td>{{ $row->tour_name }}</td>
                <td>{{ $row->event_name }}</td>
                <td>{{ $leadTraveller }}</td>
                <td>{{ $row->lb_email }}</td>
                <td>{{ $row->customer_count }}</td>
                <td>{{ f_currency($row->total_order_value) }}</td>
                <td>{{ f_currency($row->balance_paid) }}</td>
                <td>{{ f_currency($row->profit) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>