<table class="datatable table table-striped report-table" data-order='[[ 0, "desc" ]]'>
    <thead>
        <tr>
            <th scope="col"><strong>Created</strong></th>
            <th scope="col"><strong>Tour</strong></th>
            <th scope="col"><strong>Event</strong></th>
            <th scope="col"><strong>Lead Traveller Name</strong></th>
            <th scope="col"><strong>Lead Traveller Email</strong></th>
            <th scope="col"><strong>Lead Traveller Phone</strong></th>
            <th scope="col"><strong>Travellers</strong></th>
            <th scope="col"><strong>Expected Cost</strong></th>
            <th scope="col"><strong>Step Abandoned</strong></th>
            <th scope="col"><strong>IP Address</strong></th>
            <th scope="col"><strong>Continue Link</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row">{{ $row->date }}</th>
                <td><a href="{{ route('tours.view', $row->id) }}" class="link link-primary">{{ $row->tour }}</a></td>
                <td>{{ $row->event }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->contact_email }}</td>
                <td>{{ $row->contact_number }}</td>
                <td>{{ $row->travellers }}</td>
                <td>{{ f_currency($row->expected) }}</td>
                <td>{{ $row->last_page }}</td>
                <td>{{ $row->created_ip }}</td>
                <td><a href="javascript:toClipboard('{{ $row->continue }}');">Copy to Clipboard</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    function toClipboard(tag) {
        navigator.clipboard.writeText(tag);
        alert('Copied to Clipboard')
    }
</script>
