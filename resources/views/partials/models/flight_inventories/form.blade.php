@include('partials.fields.selector.adder',
            ['name' => 'Travel Class', 'field' => 'travel_class_id', 'value' => $travel_class_id ?? 0,
             'route' => 'travel-classes', 'createRoute' => route('travel-classes.create'), ])
@include('partials.fields.text', ['name' => 'Flight Number', 'field' => 'flight_number', 'value' => $flight_number ?? null,])
@include('partials.fields.date', ['name' => 'Check In', 'field' => 'check_in', 'value' => $check_in ?? null,])
@include('partials.fields.datetime',
            ['name' => 'Departs At', 'field' => 'departs_at', 'value' => $departs_at ?? null,
             'onChange' => 'changeDate($(\'#departs_at-input\'), $(\'#arrives_at-input\'))', 'width' => 6,])
@include('partials.fields.datetime',
            ['name' => 'Arrives At', 'field' => 'arrives_at', 'value' => $arrives_at ?? null,
             'onChange' => 'removeAutoset($(\'#departs_at-input\'), $(\'#arrives_at-input\'));', 'classes' => 'autoset', 'width' => 6,])
@include('partials.fields.prefab.inventory_footer')
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
