<table class="table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">Tour Name</th>
            <th scope="col">Event Name</th>
            <th scope="col">Total Stock</th>
            <th scope="col">Used Stock</th>
            <th scope="col">Available Stock</th>
            <th scope="col">% Used</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row">{{ $row->name }}</th>
                <td>{{ $row->event }}</td>
                <td>{{ $row->stock }}</td>
                <td>{{ $row->booked }}</td>
                <td>{{ $row->available }}</td>
                <td>{{ $row->percentage }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
