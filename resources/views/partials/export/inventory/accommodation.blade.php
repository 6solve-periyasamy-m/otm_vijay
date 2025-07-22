@php /** @var \App\Models\Accommodation\AccommodationInventory[] $inventories */ @endphp
<table>
    <thead>
    <tr>
        <th scope="col">id</th>
        <th scope="col">start</th>
        <th scope="col">start_confirmed</th>
        <th scope="col">end</th>
        <th scope="col">end_confirmed</th>
        <th scope="col">fit</th>
        <th scope="col">room_type</th>
        <th scope="col">room_size</th>
        <th scope="col">board_type</th>
        <th scope="col">category</th>
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
            <td>{{ $inventory->check_in->format('d-m-Y H:i') }}</td>
            <td>{{ strtoupper(f_bool($inventory->check_in_time_confirmed)) }}</td>
            <td>{{ $inventory->check_out->format('d-m-Y H:i') }}</td>
            <td>{{ strtoupper(f_bool($inventory->check_out_time_confirmed)) }}</td>
            <td>{{ strtoupper(f_bool($inventory->fit_selectable)) }}</td>
            <td>{{ $inventory->roomType->name }}</td>
            <td>{{ $inventory->roomType->maximum_occupancy }}</td>
            <td>{{ $inventory->boardType->name }}</td>
            <td>{{ $inventory->category->name }}</td>
            <td>{{ $inventory->stock }}</td>
            <td>{{ $inventory->purchase_price }}</td>
            <td>{{ $inventory->sales_price }}</td>
            <td>{{ $inventory->internal_notes }}</td>
            <td>{{ $inventory->external_notes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>