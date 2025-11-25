@php /** @var \App\Models\Order\Order[] $orders */ @endphp
<table class="table table-striped order-table">
    <thead>
    <tr>
        <th scope="col">Booking Reference</th>
        <th scope="col">Status</th>
        <th scope="col">Contact Name</th>
        <th scope="col">Contact Email</th>
        <th scope="col">Last Manual Reminder</th>
        <th scope="col">Reminder Type</th>
        <th scope="col">Amount Owed</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        @php $depositDue = $order->calculated_deposit < $order->paid; @endphp
        @php $next = $order->repository->getNextPaymentDetails() @endphp
        @continue($depositDue !== false && ($next === null || $next->remaining < setting('order.reminders.minimum', 1.0)))
        <tr order_id="{{ $order->id }}">
            <th scope="row">
                <a href="{{ route('orders.view', ['order' => $order,]) }}">
                    {{ $order->booking_reference }}
                </a>
            </th>
            <td>{{ $order->status->badge() }}</td>
            <td>{{ $order->lead_booker_name }}</td>
            <td>{{ $order->leadBooker->customer->email_address }}</td>
            <td>
                @if($order->last_manual_reminder !== null)
                    {{ f_datetime($order->last_manual_reminder) }}
                @else
                    Never
                @endif
            </td>
            <td>
                @if($next->id === null || $next->id === 0)
                    Final Payment {{ $next->due_on->isAfter(now()) ? 'Due' : 'Overdue' }}
                @else
                    Payment {{ $next->due_on->isAfter(now()) ? 'Due' : 'Overdue' }}
                @endif
            </td>
            <td>{{ f_currency($next->remaining) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>