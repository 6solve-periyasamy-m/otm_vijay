@php /** @var \App\Models\Order\Component\OrderAccommodation[] $data */ @endphp
<table class="datatable table table-striped report-table">
    <thead>
    <tr>
        <th scope="col">Order Reference</th>
        <th scope="col">Lead Traveller</th>
        <th scope="col">Order Created</th>
        <th scope="col">Currency</th>
        <th scope="col">Event</th>
        <th scope="col">Tour</th>
        <th scope="col">Accommodation</th>
        <th scope="col">Start</th>
        <th scope="col">End</th>
        <th scope="col">Room</th>
        <th scope="col">Board</th>
        <th scope="col">Category</th>
        <th scope="col">Customer</th>
        <th scope="col">Accommodation Currency</th>
        <th scope="col">Accommodation Purchase Price</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Accommodation Created</th>
        <th scope="col">Has Issue?</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $component)
        @continue($component->group?->order === null || $component->orderCustomers->count() === 0)
        @php $componentCurrency = $component->tourComponent?->repository->getCurrency(); @endphp
        @php $hasIssue = $componentCurrency?->id !== Settings::currency()?->id && $component->tourComponent?->inventory?->purchase_price === $component->estimated_purchase_price; @endphp
        <tr>
            <th scope="row">
                @if($component->group?->order !== null)
                    <a href="{{ route('orders.view', ['order' => $component->group?->order,]) }}">
                        {{ $component->group?->order?->booking_reference }}
                    </a>
                @else
                    Order Not Found
                @endif
            </th>
            <td>{{ $component->group?->order?->lead_booker_name }}</td>
            <td>{{ f_datetime($component->group?->order?->created_at) }}</td>
            <td>{{ ($component->group?->order?->currency ?? Settings::currency())?->code }}</td>
            <td>{{ $component->group?->order?->tour?->event?->name }}</td>
            <td>{{ $component->group?->order?->tour?->name }}</td>
            <td>{{ $component->tourComponent?->inventory?->component?->name }}</td>
            <td>{{ f_datetime($component->tourComponent?->inventory?->check_in) }}</td>
            <td>{{ f_datetime($component->tourComponent?->inventory?->check_out) }}</td>
            <td>{{ $component->tourComponent?->inventory?->roomType }}</td>
            <td>{{ $component->tourComponent?->inventory?->boardType }}</td>
            <td>{{ $component->tourComponent?->inventory?->category }}</td>
            <td>
                @if($component->group?->order !== null)
                    <a href="{{ route('order-customers.view', ['order' => $component->group->order, 'orderCustomer' => $component->orderCustomers->first(),]) }}">
                        {{ $component->group->getMembers() }}
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