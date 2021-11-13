@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $description ?? null,])
@include('partials.fields.date',
            ['name' => 'Start Date', 'field' => 'starts_at', 'value' => $starts_at ?? null,
             'onChange' => 'changeDate($(\'#starts_at-input\'), $(\'#ends_at-input\'))', 'width' => 6,])
@include('partials.fields.date',
            ['name' => 'End Date', 'field' => 'ends_at', 'value' => $ends_at ?? null,
             'onChange' => 'removeAutoset($(\'#starts_at-input\'), $(\'#ends_at-input\'));', 'classes' => 'autoset', 'width' => 6,])
@include('partials.fields.text', ['name' => 'Booking URL', 'field' => 'booking_url', 'value' => $booking_url ?? null,])
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
