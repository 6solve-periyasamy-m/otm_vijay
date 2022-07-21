<table class="table table-striped report-table">
    <thead>
    <tr>
        <th scope="col">Merchandise Name</th>
        <th scope="col">Variant</th>
        <th scope="col">Size</th>
        <th scope="col">Tour</th>
        <th scope="col">Customer Name</th>
        <th scope="col">Shipping Address</th>
        <th scope="col">Cost to Customer</th>
        <th scope="col">Ordered On</th>
        <th scope="col">Fulfilled</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr id="{{ $row->id }}">
            <td>{{ $row->name }}</td>
            <td>{{ $row->variant }}</td>
            <td>{{ $row->size }}</td>
            <td>{{ $row->tour }}</td>
            <td>{{ $row->ordered_on }}</td>
            <td>@if(!$row->has_address)(Lead Address)@endif {{ $row->address }}</td>
            <td>{{ $row->cost }}</td>
            <td>{{ f_datetime($row->ordered_on) }}</td>
            <td>{{ f_bool($row->fulfilled) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
