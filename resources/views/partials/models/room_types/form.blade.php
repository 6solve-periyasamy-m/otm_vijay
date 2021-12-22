@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,'width'=>10,])
@include('partials.fields.text', ['name' => 'Maximum Occupancy', 'field' => 'maximum_occupancy', 'value' => $maximum_occupancy ?? null,'width'=>2,])
@include('partials.fields.submit')
