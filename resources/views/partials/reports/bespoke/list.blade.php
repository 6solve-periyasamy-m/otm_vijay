@include('partials.fields.text', ['name' => 'Name', 'field' => 'report_name', 'value' => isset($report) ? $report->name : null, 'width' => 2,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'report_description', 'value' => isset($report) ? $report->description : null, 'width' => 8,])
<div class="form-group col-12 col-xl-2">
    <label for="submit">Apply Changes</label>
    <button id="submit" type="submit" class="form-control btn btn-primary">Submit</button>
</div>

<table class="datatable table table-striped">
    <thead class="thead-dark">
    <tr >
        <th scope="col">Model</th>
        <th scope="col">Field</th>
        <th scope="col">Include</th>
    </tr>
    </thead>
    <tbody>
    @foreach($fieldList as $depth => $data)
        @php
            $class = explode('\\', $data['class']);
            $className = camel_to_text(end($class));
        @endphp
        <tr>
            <td colspan="2"><h4 style="text-decoration: underline">{{ $className }}</h4></td>
            <td>
                {{ $depth }}
            </td>
        </tr>
        @foreach($data['fields'] as $field => $info)
            <tr>
                <td>{{ $className }}</td>
                <td>{{ $info['name'] }}</td>
                <td>
                    <input type="checkbox" name="{{ $field }}" @if(old($field) || (isset($report) && in_array($field, $report->fields))) checked @endif>
                </td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
