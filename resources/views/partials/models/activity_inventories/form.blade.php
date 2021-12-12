@can('create', \App\Models\TicketType::class)
@include('partials.fields.selector.adder',
            ['name' => 'Ticket Type', 'field' => 'ticket_type_id', 'value' => $ticket_type_id ?? 0, 'route' => 'ticket-types',
             'createRoute' => route('ticket-types.create'),])
@else
@include('partials.fields.selector.default',
        ['name' => 'Ticket Type', 'field' => 'ticket_type_id', 'value' => $ticket_type_id ?? 0, 'route' => 'ticket-types',])
@endcan
@include('partials.fields.datetime',
            ['name' => 'Starts At', 'field' => 'starts_at', 'value' => $starts_at ?? null,
             'onChange' => 'changeDate($(\'#starts_at-input\'), $(\'#ends_at-input\'))', 'width' => 6, ])
@include('partials.fields.datetime',
            ['name' => 'Ends At', 'field' => 'ends_at', 'value' => $ends_at ?? null,
             'onChange' => 'removeAutoset($(\'#starts_at-input\'), $(\'#ends_at-input\'));', 'classes' => 'autoset', 'width' => 6,])
@include('partials.fields.prefab.inventory_footer')
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
