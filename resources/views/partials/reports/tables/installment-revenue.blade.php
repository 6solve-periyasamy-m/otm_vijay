<table class="table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Count</th>
            <th scope="col">Expected Revenue</th>
            <th scope="col">Currently Paid</th>
            <th scope="col">Remaining</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row" data-order="{{$row->date->unix()}}">{{ f_date($row->date) }}</th>
                <td>{{ $row->amount }}</td>
                <td>{{ f_currency($row->expected) }}</td>
                <td>{{ f_currency($row->paid) }}</td>
                <td>{{ f_currency($row->expected - $row->paid) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
