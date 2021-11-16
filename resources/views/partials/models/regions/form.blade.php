@include('partials.fields.selector.adder',
            ['name' => 'Country', 'field' => 'region', 'value' => $country_id ?? 0,
             'route' => 'countries', 'createRoute' => route('countries.create'),])
@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.submit')
