@php
    $notes = $notes ?? true;
    $max_occupancies = 0;
    foreach ($data->data as $row) {
        $occupancies = explode('|', $row['travellers_groups']);
        $max_occupancies = max($max_occupancies, count($occupancies));
    }
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
            @for ($i = 1; $i <= $max_occupancies; $i++)
                <th scope="col">Occupants {{ $i }}</th>
            @endfor
            @if($notes)
                <th scope="col">Accommodation Notes</th>
                <th scope="col">Order Internal Notes</th>
                <th scope="col">Order External Notes</th>
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
                <td>{{ $row['from']->format('d-m-Y') }}</td>
                <td>{{ $row['from']->format('H:i:s') }}</td>
                <td>{{ $row['to']->format('d-m-Y') }}</td>
                <td>{{ $row['to']->format('H:i:s') }}</td>
                <td>{{ f_currency($row['purchase']) }}</td>
                <td>{{ f_currency($row['sales']) }}</td>
                {{-- Exporter strips 0 values for some reason, hence formatting with decimal place --}}
                <td>{{ $row['occupants'] == 0 ? number_format(0, 2) : $row['occupants'] }}</td>
                <td>{{ $row['empty_beds'] == 0 ? number_format(0, 2) : $row['empty_beds'] }}</td>
                <td>{{ $row['reference'] }}</td>
                @php
                    $occupancies = explode('|', $row['travellers_groups']);
                @endphp
                @foreach ($occupancies as $occupancy)
                    <td>{{ trim($occupancy) }}</td>
                @endforeach
                @for ($i = count($occupancies); $i < $max_occupancies; $i++)
                    <td></td>
                @endfor
                @if($notes)
                    @php
                        // Initialize variables to store notes
                        $accommodation_notes = '';
                        $internal_notes = '';
                        $external_notes = '';
                    @endphp
                    @for($x = 0; $x < $data->largest; $x++)
                        @php
                            /** @var \App\Models\Order\OrderCustomer $traveller */
                            $traveller = $row['travellers']->get($x);
                        @endphp
                        @isset($traveller)
                            {{-- Collecting Accommodation, Internal, and External notes --}}
                            @if(!empty($traveller->accommodation_notes))
                                @php
                                    $accommodation_notes .= "Customer Accommodation Notes:<br />" . nl2br(e($traveller->accommodation_notes)) . "<br />";
                                @endphp
                            @endif
                            @if(!empty($traveller->order->internal_notes))
                                @php
                                    $internal_notes .= "Order Internal Notes:<br />" . nl2br(e($traveller->order->internal_notes)) . "<br />";
                                @endphp
                            @endif
                            @if(!empty($traveller->order->external_notes))
                                @php
                                    $external_notes .= "Order External Notes:<br />" . nl2br(e($traveller->order->external_notes)) . "<br />";
                                @endphp
                            @endif
                            @if(!empty($traveller->customer?->internal_notes))
                                @php
                                    $internal_notes .= ">Internal Customer Notes:<br />" . nl2br(e($traveller->customer?->internal_notes)) . "<br />";
                                @endphp
                            @endif
                            @if(!empty($traveller->customer?->external_notes))
                                @php
                                    $external_notes .= "External Customer Notes:<br />" . nl2br(e($traveller->customer?->external_notes)) . "<br />";
                                @endphp
                            @endif
                            @if(!empty($traveller->internal_notes))
                                @php
                                    $internal_notes .= "Internal Order Customer Notes:<br />" . nl2br(e($traveller->internal_notes)) . "<br />";
                                @endphp
                            @endif
                            @if(!empty($traveller->external_notes))
                                @php
                                    $external_notes .= "External Order Customer Notes:<br />" . nl2br(e($traveller->external_notes)) . "<br />";
                                @endphp
                            @endif
                        @endisset
                    @endfor
                    {{-- Render Notes under their respective columns --}}
                    <td>{!! $accommodation_notes !!}</td>
                    <td>{!! $internal_notes !!}</td>
                    <td>{!! $external_notes !!}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>