<table class="table table-striped">
    <thead class="thead-dark">
    <tr >
        <th scope="col">Model</th>
        <th scope="col">Field</th>
        <th scope="col">Include</th>
    </tr>
    </thead>
    <tbody>
    @foreach($fieldList as $depth => $data)
        <tr>
            <td colspan="2"><h4 style="text-decoration: underline">{{ $data['class'] }}</h4></td>
            <td>
                {{ $depth }}
            </td>
        </tr>
        @foreach($data['fields'] as $field => $info)
            <tr>
                <td>{{ $data['class'] }}</td>
                <td>{{ $info['name'] }}</td>
                <td>
                    <input type="checkbox" name="{{ $field }}">
                </td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
@include('partials.fields.submit')
