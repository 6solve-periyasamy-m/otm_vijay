@php
    $notes = $notes ?? true;
@endphp
<table class="datatable table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tour</th>
            <th scope="col">Event</th>
            <th scope="col">Hotel</th>
            <th scope="col">Room Type</th>
            <th scope="col">Board Type</th>
            <th scope="col">Check In Date</th>
            <th scope="col">Check In Time</th>
            <th scope="col">Check Out Date</th>
            <th scope="col">Check Out Time</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Sales Price</th>
            <th scope="col">Occupant Count</th>
            <th scope="col">Empty Beds</th>
            <th scope="col">Reference</th>
            @for($x = 1; $x <= $data->largest; $x++)
                <th scope="col">Occupant {{ $x }}</th>
            @endfor
            @if($notes)
                <th scope="col">Accommodation Internal Notes</th>
                <th scope="col">Order Internal Notes</th>
                <th scope="col">Order External Notes</th>
                <th scope="col">Customer Internal Notes</th>
                <th scope="col">Customer External Notes</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp
        @foreach($data->data as $row)
            <tr>
            <td>{{ $count++ }}</td>
                <td>{{ $row['tour'] }}</td>
                <td>{{ $row['event'] }}</td>
                <td>{{ $row['hotel'] }}</td>
                <td>{{ $row['room'] }}</td>
                <td>{{ $row['board'] }}</td>
                <td>{{ $row['from']->format('Y-m-d') }}</td>
                <td>{{ $row['from']->format('H:i:s') }}</td>
                <td>{{ $row['to']->format('Y-m-d') }}</td>
                <td>{{ $row['to']->format('H:i:s') }}</td>
                <td>{{ f_currency($row['purchase']) }}</td>
                <td>{{ f_currency($row['sales']) }}</td>
                <td>{{ $row['occupants'] == 0 ? number_format(0, 2) : $row['occupants'] }}</td>
                <td>{{ $row['empty_beds'] == 0 ? number_format(0, 2) : $row['empty_beds'] }}</td>
                <td>{{ $row['reference'] }}</td>
                @for($x = 0; $x < $data->largest; $x++)
                    @php
                        $traveller = $row['travellers'][$x] ?? null;
                    @endphp
                    <td>
                        {{ $traveller ? ($traveller->customer?->first_name . ' ' . $traveller->customer?->last_name) : '' }}
                    </td>
                @endfor
                @if($notes)
                    @php
                        $accommodation_notes = false;
                        $order_internal_notes = false;
                        $order_external_notes = false;
                        $customer_internal_notes = false;
                        $customer_external_notes = false;
                    @endphp
                    @for($x = 0; $x < $data->largest; $x++)
                        @php
                            /** @var \App\Models\Order\OrderCustomer $traveller */
                            $traveller = $row['travellers']->get($x);
                        @endphp
                        @isset($traveller)
                            <td>
                                @if(!$accommodation_notes && !empty($traveller->accommodation_notes))
                                    {{ $traveller->accommodation_notes }}
                                    @php $accommodation_notes = true; @endphp
                                    displayedOrderCustomerExternalNotes
                                @endif
                            </td>
                            <td>
                                @if(!$order_internal_notes && !empty($traveller->order->internal_notes))
                                    {{ $traveller->order->internal_notes }}
                                    @php $order_internal_notes = true; @endphp
                                @endif
                            </td>
                            <td>
                                @if(!$order_external_notes && !empty($traveller->order->external_notes))
                                    {{ $traveller->order->external_notes }}
                                    @php $order_external_notes = true; @endphp
                                @endif
                            </td>
                            <td>
                                @if(!$customer_internal_notes && !empty($traveller->customer?->internal_notes))
                                    {{ $traveller->customer?->internal_notes }}
                                    @php $customer_internal_notes = true; @endphp
                                @endif
                            </td>
                            <td>
                                @if(!$customer_external_notes && !empty($traveller->customer?->external_notes))
                                    {{ $traveller->customer?->external_notes }}
                                    @php $customer_external_notes = true; @endphp
                                @endif
                            </td>
                        @else
                            <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
                        @endisset
                    @endfor
                @endif

            </tr>
        @endforeach
    </tbody>
</table>