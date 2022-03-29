<table class="table table-striped" id="report">
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
            <td>{{ !isset($format) || $format ? StringFormatter::formatDateTime($order->ordered_on) : $order->ordered_on }}</td>
            <td>{{ $order->booking_reference }}</td>
            <td>{{ $order->lead_booker_name }}</td>
            <td>{{ $order->cancelled ? 'Cancelled' : $order->getCustomerCount() }}</td>
            <td>{{ $order->tour->name }}</td>
            <td>{{ !isset($format) || $format ? StringFormatter::formatCurrency($order->total) : $order->total }}</td>
            <td>{{ !isset($format) || $format ? StringFormatter::formatCurrency($order->paid) : $order->remaining }}</td>
            <td>{{ !isset($format) || $format ? StringFormatter::formatCurrency($order->remaining) : $order->remaining}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
