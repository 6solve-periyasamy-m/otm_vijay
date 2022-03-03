@include('partials.fields.text', ['name' => 'Name', 'field' => ($prefix ?? "") . 'name', 'value' => $address?->name ?? null,])
@can('create', \App\Models\LocationType::class)
    @include('partials.fields.selector.adder',
                ['name' => 'Location Type', 'field' => ($prefix ?? '') . 'location_type_id', 'value' => $address?->location_type_id ?? null,
                 'route' => 'location-types', 'createRoute' => route('location-types.create')])
@else
    @include('partials.fields.selector.default',
            ['name' => 'Location Type', 'field' => ($prefix ?? '') . 'location_type_id', 'value' => $address?->location_type_id ?? null,
             'route' => 'location-types', ])
@endcan
<hr class="splitter"/>
@include('partials.fields.text', ['name' => 'Address Line 1', 'field' => ($prefix ?? "") . 'address_line_1', 'value' => $address?->address_line_1 ?? null,])
@include('partials.fields.text', ['name' => 'Address Line 2', 'field' => ($prefix ?? "") . 'address_line_2', 'value' => $address?->address_line_2 ?? null,])
@include('partials.fields.text', ['name' => 'Town', 'field' => ($prefix ?? "") . 'town', 'value' => $address?->town ?? null,])
@include('partials.fields.text', ['name' => 'Region', 'field' => ($prefix ?? "") . 'region', 'value' => $address?->region ?? null,])
@include('partials.fields.selector.default',
            ['name' => 'Country', 'field' => ($prefix ?? '') . 'country_id', 'value' => $address?->country_id ?? null,
             'route' => 'countries', ])
@include('partials.fields.text', ['name' => 'Postcode', 'field' => ($prefix ?? "") . 'postcode', 'value' => $address?->postcode ?? null,])
@include('partials.fields.submit')
