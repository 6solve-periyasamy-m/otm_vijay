@php /** @var App\Repository\Reporting\Manifest\Storage\OrderRow[] $data **/ @endphp
<table class="table datatable table-striped">
    <thead>
        <tr>
            <th scope="col">Reference</th>
            <th scope="col">Event</th>
            <th scope="col">Ordered</th>
            <th scope="col">Status</th>
            <th scope="col">Organization</th>
            <th scope="col">Agent</th>
            <th scope="col">Consultant</th>
            <th scope="col">Lead Booker</th>
            <th scope="col">Location</th>
            <th scope="col">Type</th>
            <th scope="col">Tour Component Type</th>
            <th scope="col">Description</th>
            <th scope="col">Start</th>
            <th scope="col">End</th>
            <th scope="col">Travellers</th>
            <th scope="col">Quantity</th>
            <th scope="col">Nights</th>
            <th scope="col">Currency</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Currency</th>
            <th scope="col">Sales Price</th>
            <th scope="col">Internal Notes</th>
            <th scope="col">External Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row">{{ $row->reference }}</th>
                <td>{{ $row->event }}</td>
                <td>{{ $row->ordered->format('d/m/Y') }}</td>
                <td>{{ $row->status->description() }}</td>
                <td>{{ $row->organization }}</td>
                <td>{{ $row->agent }}</td>
                <td>{{ $row->consultant }}</td>
                <td>{{ $row->leadBooker }}</td>
                <td>{{ $row->location }}</td>
                <td>{{ $row->component }}</td>
                <td>{{ $row->tourComponentType }}</td>
                <td>{{ $row->description }}</td>
                <td>{{ $row->start?->format('d/m/Y') }}</td>
                <td>{{ $row->end?->format('d/m/Y') }}</td>
                <td>{{ $row->travellers ?? '-' }}</td>
                <td>{{ $row->quantity }}</td>
                <td>{{ $row->nights }}</td>
                <td>{{ $row->currency }}</td>
                <td>{{ $row->purchasePrice }}</td>
                <td>{{ \Settings::currency() }}</td>
                <td>{{ $row->salesPrice }}</td>
                <td>{{ $row->internalNotes }}</td>
                <td>{{ $row->externalNotes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
