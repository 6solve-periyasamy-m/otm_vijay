@include('partials.fields.text', ['name' => 'Name', 'field' => 'report_name', 'value' => $name ?? null, 'width' => 2,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'report_description', 'value' => $description ?? null, 'width' => 8,])
<div class="form-group col-12 col-xl-2">
    <label for="submit">Apply Changes</label>
    <button id="submit" type="submit" class="form-control btn btn-primary">Submit</button>
</div>

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
