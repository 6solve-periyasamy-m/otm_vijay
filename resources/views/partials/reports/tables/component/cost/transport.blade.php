@php /** @var \App\Models\Order\Component\OrderTransport[] $data */ @endphp
<table class="datatable table table-striped report-table">
    <thead>
    <tr>
        <th scope="col">Order Reference</th>
        <th scope="col">Lead Traveller</th>
        <th scope="col">Order Created</th>
        <th scope="col">Currency</th>
        <th scope="col">Event</th>
        <th scope="col">Tour</th>
        <th scope="col">Transport</th>
        <th scope="col">Start</th>
        <th scope="col">End</th>
        <th scope="col">Class</th>
        <th scope="col">Customer</th>
        <th scope="col">Transport Currency</th>
        <th scope="col">Transport Purchase Price</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Transport Created</th>
        <th scope="col">Has Issue?</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $component)
        @php $componentCurrency = $component->tourComponent?->repository->getCurrency(); @endphp
        @php $hasIssue = $componentCurrency?->id !== Settings::currency()?->id && $component->tourComponent?->inventory?->purchase_price === $component->estimated_purchase_price; @endphp
        <tr>
            <th scope="row">
                @if($component->orderCustomer?->order !== null)
                    <a href="{{ route('orders.view', ['order' => $component->orderCustomer?->order,]) }}">
                        {{ $component->orderCustomer?->order?->booking_reference }}
                    </a>
                @else
                    Order Not Found
                @endif
            </th>
            <td>{{ $component->orderCustomer?->order?->lead_booker_name }}</td>
            <td>{{ f_datetime($component->orderCustomer?->order?->created_at) }}</td>
            <td>{{ ($component->orderCustomer?->order?->currency ?? Settings::currency())?->code }}</td>
            <td>{{ $component->orderCustomer?->order?->tour?->event?->name }}</td>
            <td>{{ $component->orderCustomer?->order?->tour?->name }}</td>
            <td>{{ $component->tourComponent?->inventory?->component?->name }}</td>
            <td>{{ f_datetime($component->tourComponent?->inventory?->departs_at) }}</td>
            <td>{{ f_datetime($component->tourComponent?->inventory?->arrives_at) }}</td>
            <td>{{ $component->tourComponent?->inventory?->travelClass }}</td>
            <td>
                @if($component->orderCustomer?->order !== null)
                    <a href="{{ route('order-customers.view', ['order' => $component->orderCustomer->order_id, 'orderCustomer' => $component->order_customer_id,]) }}">
                        {{ $component->orderCustomer?->customer?->full_name }}
                    </a>
                @else
                    Order Not Found
                @endif
            </td>
            <td>{{ ($componentCurrency)?->code }}</td>
            <td>{{ fr_currency($component->tourComponent?->inventory?->purchase_price, $componentCurrency) }}</td>
            <td>{{ fr_currency($component->estimated_purchase_price) }}</td>
            <td>{{ f_datetime($component->created_at) }}</td>
            <td>{{ f_bool($hasIssue) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>