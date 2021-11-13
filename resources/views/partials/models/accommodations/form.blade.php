@include('partials.fields.selector.adder',
['name' => 'Region', 'field' => 'region_id', 'value' => $region_id ?? 0, 'route' => 'regions', 'createRoute' => route('regions.create'),])
@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $name ?? null,])
@include('partials.fields.date', ['name' => 'Audit Date', 'field' => 'audit_date', 'value' => $audit_date ?? null,])
@include('partials.fields.text', ['name' => 'Address', 'field' => 'address', 'value' => $address ?? null,])
@include('partials.fields.text', ['name' => 'Currency', 'field' => 'currency', 'value' => $currency ?? null,])
@include('partials.fields.submit')
