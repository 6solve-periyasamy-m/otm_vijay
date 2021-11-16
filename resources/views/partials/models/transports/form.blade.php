@include('partials.fields.selector.adder',
            ['name' => 'Transport Type', 'field' => 'transport_type_id', 'value' => $transport_type_id ?? 0,
             'route' => 'transport-types', 'createRoute' => route('transport-types.create'),])
@include('partials.fields.selector.adder',
            ['name' => 'Operator', 'field' => 'operator_id', 'value' => $operator_id ?? 0,
             'route' => 'operators', 'createRoute' => route('operators.create'),])
@include('partials.fields.selector.adder',
            ['name' => 'Departure Location', 'field' => 'departure_location_id', 'value' => $departure_location_id ?? 0,
             'route' => 'locations', 'createRoute' => route('locations.create'),])
@include('partials.fields.selector.adder',
            ['name' => 'Arrival Location', 'field' => 'arrival_location_id', 'value' => $arrival_location_id ?? 0,
             'route' => 'locations', 'createRoute' => route('locations.create'),])
@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $description ?? null,])
@include('partials.fields.text', ['name' => 'Currency', 'field' => 'currency', 'value' => $currency ?? null,])
@include('partials.fields.checkbox', ['name' => 'Is Domestic', 'field' => 'is_domestic', 'value' => $is_domestic ?? null,])
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
