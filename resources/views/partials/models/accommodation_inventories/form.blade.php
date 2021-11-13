@include('partials.fields.selector.adder',
    ['name' => 'Room Type', 'field' => 'room_type_id', 'route' => 'room-types', 'id' => $room_type_id ?? 0, 'createRoute' => route('room-types.create'),])
@include('partials.fields.selector.adder',
    ['name' => 'Board Type', 'field' => 'board_type_id', 'route' => 'board-types', 'id' => $board_type_id ?? 0, 'createRoute' => route('board-types.create'),])
<div class="form-group col-xl-6">
    @include('partials.fields.raw.datetime',
        ['name' => 'Check In', 'field' => 'check_in', 'value' => $check_in ?? null,
        'onChange' => 'changeDate($(\'#check_in-input\'), $(\'#check_out-input\'))', ])
    <p></p>
    @include('partials.fields.raw.checkbox',
        ['name' => 'Check In Time Confirmed', 'field' => 'check_in_time_confirmed', 'value' => $check_in_time_confirmed ?? null, ])
</div>
<div class="form-group col-xl-6">
    @include('partials.fields.raw.datetime',
        ['name' => 'Check Out', 'field' => 'check_out', 'value' => $check_out ?? null,
        'onChange' => 'removeAutoset($(\'#check_in-input\'), $(\'#check_out-input\'))', 'classes' => 'autoset', ])
    <p></p>
    @include('partials.fields.raw.checkbox',
        ['name' => 'Check In Time Confirmed', 'field' => 'check_out_time_confirmed', 'value' => $check_out_time_confirmed ?? null, ])
</div>
@include('partials.fields.prefab.inventory_footer')
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
