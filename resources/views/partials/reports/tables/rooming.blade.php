<table class="table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tour</th>
            <th scope="col">Hotel</th>
            <th scope="col">Room Type</th>
            <th scope="col">Board Type</th>
            <th scope="col">Check In</th>
            <th scope="col">Check Out</th>
            <th scope="col">Occupant Count</th>
            <th scope="col">Empty Beds</th>
            <th scope="col">Occupants</th>
            <th scope="col">Accommodation Notes</th>
            <th scope="col">Internal Customer Notes</th>
            <th scope="col">External Customer Notes</th>
            <th scope="col">Internal Order Customer Notes</th>
            <th scope="col">External Order Customer Notes</th>
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp
        @foreach($data->data as $row)
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $row->tour }}</td>
                <td>{{ $row->hotel }}</td>
                <td>{{ $row->room }}</td>
                <td>{{ $row->board }}</td>
                <td>{{ $row->from }}</td>
                <td>{{ $row->to }}</td>
                <td>{{ $row->occupants }}</td>
                <td>{{ $row->empty_beds }}</td>

                @php
                    $travellers = "";
                    $acc_notes = "";
                    $internal_c = "";
                    $external_c = "";
                    $internal_oc = "";
                    $external_oc = "";
                    /** @var \App\Models\Order\OrderCustomer $traveller */
                    foreach ($row->travellers as $traveller) {
                        $travellers .= ($traveller->customer?->first_name ?? 'Redacted') . " " . ($traveller->customer?->last_name ?? 'Redacted') . ',';
                        $acc_notes .= ($traveller->accommodation_notes ?? "No Notes") . "\n";
                        $internal_c .= ($traveller->customer?->internal_notes ?? 'No Notes') . "\n";
                        $external_c .= ($traveller->customer?->external_notes ?? 'No Notes') . "\n";
                        $internal_oc .= ($traveller->internal_notes ?? 'No Notes') . "\n";
                        $external_oc .= ($traveller->external_notes ?? 'No Notes') . "\n";
                    }
                @endphp
                <td>
                    {{ $travellers }}
                </td>
                <td>
                    {!! nl2br(e($acc_notes)) !!}
                </td>
                <td>
                    {!! nl2br(e($internal_c)) !!}
                </td>
                <td>
                    {!! nl2br(e($external_c)) !!}
                </td>
                <td>
                    {!! nl2br(e($internal_oc)) !!}
                </td>
                <td>
                    {!! nl2br(e($external_oc)) !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
