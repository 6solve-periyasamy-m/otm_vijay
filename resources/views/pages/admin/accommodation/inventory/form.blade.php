@php
    /**
     * @var \App\Models\Accommodation\Accommodation $accommodation
     * @var \App\Models\Accommodation\AccommodationInventory|null $inventory
     */
    $inventory = $inventory ?? null;
    $title = __('accommodation.inventory.form.title.' . ($inventory === null ? 'create' : 'update'));
    $route = $inventory === null ?
        route('accommodation-inventories.store', ['accommodation' => $accommodation, ]) :
        route('accommodation-inventories.update', ['accommodation' => $accommodation, 'inventory' => $inventory,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @can('create', \App\Models\Accommodation\RoomType::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Room Type', 'field' => 'room_type_id', 'value' => $inventory?->room_type_id,
                     'route' => 'room-types', 'createRoute' => route('room-types.create'), 'width' => 6,])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Room Type', 'field' => 'room_type_id', 'value' => $inventory?->room_type_id,
                 'route' => 'room-types', 'width' => 6,])
    @endcan
    @can('create', \App\Models\Accommodation\BoardType::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Board Type', 'field' => 'board_type_id', 'value' => $inventory?->board_type_id,
                     'route' => 'board-types', 'createRoute' => route('board-types.create'), 'width' => 6,])
    @else
        @include('partials.fields.selector.default',
                    ['name' => 'Board Type', 'field' => 'board_type_id', 'value' => $inventory?->board_type_id,
                     'route' => 'board-types', 'width' => 6,])
    @endcan
    <div class="form-group col-xl-6">
        @include('partials.fields.raw.datetime',
                    ['name' => 'Check In', 'field' => 'check_in', 'value' => $inventory?->check_in ?? $accommodation?->check_in,
                     'onChange' => 'changeDate($(\'#check_in-input\'), $(\'#check_out-input\'))', ])
        <p></p>
        @include('partials.fields.raw.checkbox',
                    ['name' => 'Check In Time Confirmed', 'field' => 'check_in_time_confirmed', 'value' => $inventory?->check_in_time_confirmed, ])
    </div>
    <div class="form-group col-xl-6">
        @include('partials.fields.raw.datetime',
                    ['name' => 'Check Out', 'field' => 'check_out', 'value' => $inventory?->check_out ?? $accommodation?->check_out(isset($accommodation?->check_out)) ? $accommodation?->check_out : $inventory?->check_out,
                     'onChange' => 'removeAutoset($(\'#check_in-input\'), $(\'#check_out-input\'))', 'classes' => 'autoset', ])
        <p></p>
        @include('partials.fields.raw.checkbox',
                    ['name' => 'Check Out Time Confirmed', 'field' => 'check_out_time_confirmed', 'value' => $inventory?->check_out_time_confirmed, ])
    </div>
    @include('partials.fields.checkbox',
    ['name' => 'FIT Selectable', 'field' => 'fit_selectable', 'value' => $inventory?->fit_selectable, ])
    @include('partials.fields.text',
        ['name' => 'Stock', 'field' => 'stock', 'value' => $inventory?->stock, ])
    @include('partials.fields.text',
        ['name' => 'Purchase Price', 'field' => 'purchase_price', 'value' => $inventory?->purchase_price, 'width' => 6, ])
    @include('partials.fields.text',
        ['name' => 'Sales Price', 'field' => 'sales_price', 'value' => $inventory?->sales_price, 'width' => 6, ])
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'internal_notes', 'value' => $inventory?->internal_notes])
    @include('partials.fields.textarea', ['name' => 'External Notes', 'field' => 'external_notes', 'value' => $inventory?->external_notes])
    @include('partials.fields.submit')
@endsection
