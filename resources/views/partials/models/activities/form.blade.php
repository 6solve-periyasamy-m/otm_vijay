@can('create', \App\Models\ActivityType::class)
    @include('partials.fields.selector.adder',
                ['name' => 'Activity Type', 'field' => 'activity_type_id', 'value' => $activity_type_id ?? 0,
                 'route' => 'activity-types', 'createRoute' => route('activity-types.create'),])
@else
    @include('partials.fields.selector.default',
            ['name' => 'Activity Type', 'field' => 'activity_type_id', 'value' => $activity_type_id ?? 0,
             'route' => 'activity-types',])
@endcan
@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,'width' => 10,])
@include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 2,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $description ?? null,])
@include('partials.fields.prefab.addresses.switcher', [
    'location_type_id' => isset($address) ? $address->location_type_id : 0,
    'address_line_1' => isset($address) ? $address->address_line_1 : "",
    'address_line_2' => isset($address) ? $address->address_line_2 : "",
    'town' => isset($address) ? $address->town : "",
    'region' => isset($address) ? $address->region : "",
    'country_id' => isset($address) ? $address->country_id : 0,
    'postcode' => isset($address) ? $address->postcode : "",
])
@include('partials.fields.selector.default',
    ['name' => 'Currency', 'field' => 'currency_id', 'value' => $currency ?? null, 'route' => 'currencies',])
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
