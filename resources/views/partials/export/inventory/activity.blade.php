@php /** @var \App\Models\Activity\ActivityInventory[] $inventories */ @endphp
<table>
    <thead>
    <tr>
        <th scope="col">id</th>
        <th scope="col">description</th>
        <th scope="col">start</th>
        <th scope="col">end</th>
        <th scope="col">fit</th>
        <th scope="col">ticket_type</th>
        <th scope="col">stock</th>
        <th scope="col">purchase</th>
        <th scope="col">sales</th>
        <th scope="col">internal</th>
        <th scope="col">external</th>
    </tr>
    </thead>
    <tbody>
    @foreach($inventories as $inventory)
        <tr>
            <th scope="row">{{ $inventory->id }}</th>
            <td>{{ $inventory->description }}</td>
            <td>{{ $inventory->starts_at->format('d-m-Y H:i') }}</td>
            <td>{{ $inventory->ends_at->format('d-m-Y H:i') }}</td>
            <td>{{ strtoupper(f_bool($inventory->fit_selectable)) }}</td>
            <td>{{ $inventory->ticketType->name }}</td>
            <td>{{ $inventory->stock }}</td>
            <td>{{ $inventory->purchase_price }}</td>
            <td>{{ $inventory->sales_price }}</td>
            <td>{{ $inventory->internal_notes }}</td>
            <td>{{ $inventory->external_notes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>