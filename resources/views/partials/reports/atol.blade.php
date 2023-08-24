<table class="datatable table table-striped" id="report">
    <thead>
    <tr>
        <td>Order Date</td>
        <td>Booking Reference</td>
        <td>Lead Booker</td>
        <td>Passengers</td>
        <td>Tour Name</td>
        <td>Total Order Value</td>
        <td>Balance Paid</td>
        <td>Balance Outstanding</td>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ !isset($format) || $format ? f_datetime($order->ordered_on) : $order->ordered_on }}</td>
            <td>{{ $order->booking_reference }}</td>
            <td>{{ $order->lead_booker_name }}</td>
            <td>{{ $order->cancelled ? 'Cancelled' : $order->customer_count }}</td>
            <td>{{ $order->tour->name }}</td>
            <td>{{ !isset($format) || $format ? f_currency($order->total) : $order->total }}</td>
            <td>{{ !isset($format) || $format ? f_currency($order->paid) : $order->remaining }}</td>
            <td>{{ !isset($format) || $format ? f_currency($order->remaining) : $order->remaining}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
