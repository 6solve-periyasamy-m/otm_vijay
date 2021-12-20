@can('create', \App\Models\TransportType::class)
@include('partials.fields.selector.adder',
            ['name' => 'Transport Type', 'field' => 'transport_type_id', 'value' => $transport_type_id ?? 0,
             'route' => 'transport-types', 'createRoute' => route('transport-types.create'),])
@else
@include('partials.fields.selector.default',
        ['name' => 'Transport Type', 'field' => 'transport_type_id', 'value' => $transport_type_id ?? 0,
         'route' => 'transport-types',])
@endcan
@can('create', \App\Models\Operator::class)
@include('partials.fields.selector.adder',
            ['name' => 'Operator', 'field' => 'operator_id', 'value' => $operator_id ?? 0,
             'route' => 'operators', 'createRoute' => route('operators.create'),])
@else
@include('partials.fields.selector.default',
        ['name' => 'Operator', 'field' => 'operator_id', 'value' => $operator_id ?? 0,
         'route' => 'operators',])
@endcan
@include('partials.fields.prefab.addresses.selector', ['namePrefix' => 'Departure', 'prefix' => 'departure_', 'value' => $departure_address_id ?? 0,])
@include('partials.fields.prefab.addresses.selector', ['namePrefix' => 'Arrival', 'prefix' => 'arrival_', 'value' => $arrival_address_id ?? 0,])
@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,'width'=>10,])
@include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 2,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $description ?? null,])
@include('partials.fields.checkbox', ['name' => 'Is Domestic', 'field' => 'is_domestic', 'value' => $is_domestic ?? null,])
@include('partials.fields.selector.default',
    ['name' => 'Currency', 'field' => 'currency_id', 'value' => $currency ?? null, 'route' => 'currencies',])
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
