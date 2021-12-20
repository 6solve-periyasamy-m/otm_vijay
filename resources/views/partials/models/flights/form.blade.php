@can('create', \App\Models\Airline::class)
@include('partials.fields.selector.adder',
            ['name' => 'Airline', 'field' => 'airline_id', 'value' => $airline_id ?? 0,
             'route' => 'airlines', 'createRoute' => route('airlines.create'),])
@else
@include('partials.fields.selector.default',
        ['name' => 'Airline', 'field' => 'airline_id', 'value' => $airline_id ?? 0,
         'route' => 'airlines'])
@endcan
@can('create', \App\Models\Airport::class)
@include('partials.fields.selector.adder',
            ['name' => 'Departure Airport', 'field' => 'departure_airport_id', 'value' => $departure_airport_id ?? 0,
             'route' => 'airports', 'createRoute' => route('airports.create'),])
@else
@include('partials.fields.selector.default',
            ['name' => 'Departure Airport', 'field' => 'departure_airport_id', 'value' => $departure_airport_id ?? 0,
             'route' => 'airports',])
@endcan
@can('create', \App\Models\Airport::class)
@include('partials.fields.selector.adder',
            ['name' => 'Arrival Airport', 'field' => 'arrival_airport_id', 'value' => $arrival_airport_id ?? 0,
             'route' => 'airports', 'createRoute' => route('airports.create'),])
@else
@include('partials.fields.selector.default',
            ['name' => 'Arrival Airport', 'field' => 'arrival_airport_id', 'value' => $arrival_airport_id ?? 0,
             'route' => 'airports',])
@endcan
@include('partials.fields.file', ['name' => 'Image', 'field' => 'image',])
@include('partials.fields.checkbox', ['name' => 'Is Domestic', 'field' => 'is_domestic', 'value' => $is_domestic ?? null,])
@include('partials.fields.date', ['name' => 'Available After', 'field' => 'available_after', 'value' => $available_after ?? null,])
@include('partials.fields.selector.default',
    ['name' => 'Currency', 'field' => 'currency_id', 'value' => $currency ?? null, 'route' => 'currencies',])
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
