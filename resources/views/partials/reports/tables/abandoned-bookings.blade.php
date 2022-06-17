<table class="table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">Customer Name</th>
            <th scope="col">Tour</th>
            <th scope="col">Travellers</th>
            <th scope="col">Expected Cost</th>
            <th scope="col">Contact Email</th>
            <th scope="col">Contact Phone</th>
            <th scope="col">Continue Link</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row">{{ $row->name }}</th>
                <td>{{ $row->tour }}</td>
                <td>{{ $row->travellers }}</td>
                <td>{{ f_currency($row->expected) }}</td>
                <td>{{ $row->contact_email }}</td>
                <td>{{ $row->contact_number }}</td>
                <td><a href="{{ $row->continue }}" onclick="event.preventDefault();toClipboard(this);">{{ $row->continue }}</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    function toClipboard(tag) {
        navigator.clipboard.writeText($(tag).prop('href'));
        alert('Copied to Clipboard')
    }
</script>
