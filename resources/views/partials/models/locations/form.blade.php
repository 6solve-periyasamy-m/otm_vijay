@include('partials.fields.selector.adder',
            ['name' => 'Location Type', 'field' => 'location_type_id', 'value' => $location_type_id,
             'route' => 'location-types', 'createRoute' => route('location-types.create')])
@include('partials.fields.selector.adder',
            ['name' => 'Region', 'field' => 'region_id', 'value' => $region_id,
             'route' => 'regions', 'createRoute' => route('regions.create')])
@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.text', ['name' => 'Address', 'field' => 'address', 'value' => $address ?? null,])
@include('partials.fields.submit')
