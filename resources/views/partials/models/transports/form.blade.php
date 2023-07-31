@can('create', \App\Models\Transport\TransportType::class)
@include('partials.fields.selector.adder',
            ['name' => 'Transport Type', 'field' => 'transport_type_id', 'value' => $transport_type_id ?? 0,
             'route' => 'transport-types', 'createRoute' => route('transport-types.create'), 'width' => 6,])
@else
@include('partials.fields.selector.default',
        ['name' => 'Transport Type', 'field' => 'transport_type_id', 'value' => $transport_type_id ?? 0,
         'route' => 'transport-types', 'width' => 6,])
@endcan
@can('create', \App\Models\Transport\Operator::class)
@include('partials.fields.selector.adder',
            ['name' => 'Operator', 'field' => 'operator_id', 'value' => $operator_id ?? 0,
             'route' => 'operators', 'createRoute' => route('operators.create'), 'width' => 6,])
@else
@include('partials.fields.selector.default',
        ['name' => 'Operator', 'field' => 'operator_id', 'value' => $operator_id ?? 0,
         'route' => 'operators', 'width' => 6,])
@endcan
<hr class="splitter">
@include('partials.fields.checkbox', ['name' => 'Show Customer Addresses', 'field' => 'customers', 'width' => 6,])
@include('partials.fields.checkbox', ['name' => 'Is Domestic', 'field' => 'is_domestic', 'value' => $is_domestic ?? null, 'width' => 6,])
@include('partials.fields.prefab.addresses.transport', ['namePrefix' => 'Departure', 'prefix' => 'departure_', 'value' => $departure_address_id ?? 0, 'customerCheckbox' => true, 'width' => 6,])
@include('partials.fields.prefab.addresses.transport', ['namePrefix' => 'Arrival', 'prefix' => 'arrival_', 'value' => $arrival_address_id ?? 0, 'customerCheckbox' => true, 'width' => 6,])
<hr class="splitter">
@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,'width'=>10,])
@include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 2, 'value' => $image_url ?? null,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $description ?? null,])
@include('partials.fields.selector.default',
    ['name' => 'Currency', 'field' => 'currency_id', 'value' => $currency ?? null, 'route' => 'currencies',])
@include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'notes', 'value' => $notes ?? null])
@include('partials.fields.submit')
