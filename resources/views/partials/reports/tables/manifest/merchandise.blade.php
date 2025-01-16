<table class="datatable table table-striped report-table">
    <thead>
    <tr>
        <th scope="col">Booking Reference</th>
        <th scope="col">Customer Name</th>
        <th scope="col">Passport Name</th>
        <th scope="col">Email Address</th>
        <th scope="col">Merchandise</th>
        <th scope="col">Type</th>
        <th scope="col">Variant</th>
        <th scope="col">Size</th>
        <th scope="col">Component Type</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <th scope="row">{{ $row->reference }}</th>
            <td>{{ $row->customer }}</td>
            <td>{{ $row->passport }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->merchandise }}</td>
            <td>{{ $row->type }}</td>
            <td>{{ $row->variant }}</td>
            <td>{{ $row->size }}</td>
            <td>{{ $row->component }}</td>
            <td>{{ f_currency($row->purchase) }}</td>
            <td>{{ f_currency($row->sales) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
