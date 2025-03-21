<table class="datatable table table-striped report-table">
    <thead>
        <tr>
            <th scope="col">From Currency Code</th>
            <th scope="col">To Currency Code</th>
            <th scope="col">Rate</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $row)
            <tr>
                <td>{{ $row['from_currency_code'] }}</td>
                <td>{{ $row['to_currency_code'] }}</td>
                <td>{{ number_format($row['rate'], 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No conversion rates available</td>
            </tr>
        @endforelse
    </tbody>
</table>
