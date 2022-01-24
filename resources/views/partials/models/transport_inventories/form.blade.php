@can('create', \App\Models\TravelClass::class)
    @include('partials.fields.selector.adder',
                ['name' => 'Travel Class', 'field' => 'travel_class_id', 'value' => $travel_class_id ?? 0,
                 'route' => 'travel-classes', 'createRoute' => route('travel-classes.create'), ])
@else
    @include('partials.fields.selector.default',
            ['name' => 'Travel Class', 'field' => 'travel_class_id', 'value' => $travel_class_id ?? 0,
             'route' => 'travel-classes',])
@endcan
<div class="form-group col-xl-6">
    @include('partials.fields.raw.datetime',
                ['name' => 'Departs At', 'field' => 'departs_at', 'value' => $departs_at ?? null,
                 'onChange' => 'changeDate($(\'#departs_at-input\'), $(\'#arrives_at-input\'))', ])
    <p></p>
    @include('partials.fields.raw.checkbox',
                ['name' => 'Departure Confirmed', 'field' => 'departure_time_confirmed', 'value' => $departure_time_confirmed ?? null, ])
</div>
<div class="form-group col-xl-6">
    @include('partials.fields.raw.datetime',
                ['name' => 'Arrives At', 'field' => 'arrives_at', 'value' => $arrives_at ?? null,
                 'onChange' => 'removeAutoset($(\'#departs_at-input\'), $(\'#arrives_at-input\'))', 'classes' => 'autoset', ])
    <p></p>
    @include('partials.fields.raw.checkbox',
                ['name' => 'Arrival Confirmed', 'field' => 'arrival_time_confirmed', 'value' => $arrival_time_confirmed ?? null, ])
</div>
@include('partials.fields.prefab.inventory_footer')
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
