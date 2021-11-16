@include('partials.fields.selector.adder',
            ['name' => 'Activity Type', 'field' => 'activity_type_id', 'value' => $activity_type_id ?? 0,
             'route' => 'activity-types', 'createRoute' => route('activity-types.create'),])
@include('partials.fields.selector.adder',
            ['name' => 'Location', 'field' => 'location_id', 'value' => $location_id ?? 0,
             'route' => 'locations', 'createRoute' => route('locations.create'),])
@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $description ?? null,])
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
