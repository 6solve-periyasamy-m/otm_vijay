@include('partials.fields.selector.adder',
            ['name' => 'Event', 'field' => 'event_id', 'value' => $event_id ?? 0,
             'route' => 'events', 'createRoute' => route('events.create'),])
@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $description ?? null,])
@include('partials.fields.date',
            ['name' => 'Start Date', 'field' => 'date_from', 'value' => $date_from ?? null,
             'onChange' => 'changeDate($(\'#date_from-input\'), $(\'#date_to-input\'))', 'width' => 6, ])
@include('partials.fields.date',
            ['name' => 'End Date', 'field' => 'date_to', 'value' => $date_to ?? null,
             'onChange' => 'removeAutoset($(\'#date_from-input\'), $(\'#date_to-input\'));', 'classes' => 'autoset', 'width' => 6,])
@include('partials.fields.text', ['name' => 'Base Price Per Person', 'field' => 'base_price_per_person', 'value' => $base_price_per_person ?? null, 'width' => 6,])
@include('partials.fields.text', ['name' => 'Margin', 'field' => 'margin', 'value' => $margin ?? null, 'width' => 6,])
@include('partials.fields.text', ['name' => 'Deposit', 'field' => 'deposit', 'value' => $deposit ?? null, 'width' => 6,])
@include('partials.fields.text', ['name' => 'Single Occupancy Surcharge', 'field' => 'single_occupancy_surcharge', 'value' => $single_occupancy_surcharge ?? null, 'width' => 6,])
@include('partials.fields.checkbox', ['name' => 'Stock Control Active', 'field' => 'stock_control_active', 'value' => $stock_control_active ?? null,])
@include('partials.fields.text', ['name' => 'Stock', 'field' => 'stock', 'value' => $stock ?? null,])
@include('partials.fields.text', ['name' => 'Booking Form Url', 'field' => 'booking_form_url', 'value' => $booking_form_url ?? null,])
@include('partials.fields.text', ['name' => 'Tour Colour Id', 'field' => 'tour_colour_id', 'value' => $tour_colour_id ?? null,])
@include('partials.fields.checkbox', ['name' => 'Is Active', 'field' => 'is_active', 'value' => $is_active ?? null,])
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
