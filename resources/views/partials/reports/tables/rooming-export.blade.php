@php $notes = $notes ?? true; @endphp
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
            <th scope="col" colspan="{{$data->largest}}">Occupants</th>
            @if($notes)
            <th scope="col" colspan="{{$data->largest}}">Accommodation Notes</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp
        @foreach($data->data as $row)
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $row->tour }}</td>
                <td>{{ $row->event }}</td>
                <td>{{ $row->hotel }}</td>
                <td>{{ $row->room }}</td>
                <td>{{ $row->board }}</td>
                <td>{{ $row->from->format('Y-m-d') }}</td>
                <td>{{ $row->from->format('H:i:s') }}</td>
                <td>{{ $row->to->format('Y-m-d') }}</td>
                <td>{{ $row->to->format('H:i:s') }}</td>
                <td>{{ f_currency($row->purchase) }}</td>
                <td>{{ f_currency($row->sales) }}</td>
                {{-- Exporter strips 0 values for some reason, hence formatting with decimal place --}}
                <td>{{ $row->occupants == 0 ? number_format(0, 2) : $row->occupants }}</td>
                <td>{{ $row->empty_beds == 0 ? number_format(0, 2) : $row->empty_beds }}</td>
                <td>{{ $row->reference }}</td>

                @for($x = 0; $x < $data->largest; $x++)
                    @php
                        /** @var \App\Models\Order\OrderCustomer $traveller */
                        $traveller = $row->travellers->get($x);
                    @endphp
                    @isset($traveller)
                        <td>{{ $traveller->customer?->first_name ?? 'Redacted' }} {{ $traveller->customer?->last_name ?? 'Redacted' }}</td>
                    @else
                        <td></td>
                    @endisset
                @endfor

                @if($notes)
                    @for($x = 0; $x < $data->largest; $x++)
                        @php
                            /** @var \App\Models\Order\OrderCustomer $traveller */
                            $traveller = $row->travellers->get($x);
                        @endphp
                        @isset($traveller)
                            <td>
                                @if(!empty($traveller->accommodation_notes))
                                Customer Accommodation Notes: <br /><br />
                                {{ $traveller->accommodation_notes }} <br /><br />
                                @endif
                                @if(!empty($traveller->order->internal_notes))
                                Order Internal Notes: <br />
                                {{ $traveller->order->internal_notes ?? '' }} <br /><br />
                                @endif
                                @if(!empty($traveller->order->external_notes))
                                Order External Notes: <br />
                                {{ $traveller->order->external_notes ?? '' }} <br /><br />
                                @endif
                                @if(!empty($traveller->customer?->internal_notes))
                                Internal Customer Notes: <br /><br />
                                {{ $traveller->customer?->internal_notes }} <br /><br />
                                @endif
                                @if(!empty($traveller->customer?->external_notes))
                                External Customer Notes: <br /><br />
                                {{ $traveller->customer?->external_notes }} <br /><br />
                                @endif
                                @if(!empty($traveller->internal_notes))
                                Internal Order Customer Notes: <br /><br />
                                {{ $traveller->internal_notes }} <br /><br />
                                @endif
                                @if(!empty($traveller->external_notes))
                                External Order Customer Notes: <br /><br />
                                {{ $traveller->external_notes }} <br /><br />
                                @endif
                            </td>
                        @else
                            <td></td>
                        @endisset
                    @endfor
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
