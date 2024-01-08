@php
    /**
     * @var \App\Models\Activity\Activity $activity
     * @var \App\Models\Activity\ActivityInventory|null $inventory
     */
    $inventory = $inventory ?? null;
    $title = __('activity.inventory.form.title.' . ($inventory === null ? 'create' : 'update'));
    $route = $inventory === null ?
        route('activity-inventories.store', ['activity' => $activity, ]) :
        route('activity-inventories.update', ['activity' => $activity, 'activityInventory' => $inventory,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @can('create', \App\Models\Activity\TicketType::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Ticket Type', 'field' => 'ticket_type_id', 'value' => $inventory?->ticket_type_id, 'route' => 'ticket-types',
                     'createRoute' => route('ticket-types.create'),])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Ticket Type', 'field' => 'ticket_type_id', 'value' => $inventory?->ticket_type_id, 'route' => 'ticket-types',])
    @endcan
    @include('partials.fields.datetime',
                ['name' => 'Starts At', 'field' => 'starts_at', 'value' => $inventory?->starts_at,
                 'onChange' => 'changeDate($(\'#starts_at-input\'), $(\'#ends_at-input\'))', 'width' => 6, ])
    @include('partials.fields.datetime',
                ['name' => 'Ends At', 'field' => 'ends_at', 'value' => $inventory?->ends_at,
                 'onChange' => 'removeAutoset($(\'#starts_at-input\'), $(\'#ends_at-input\'));', 'classes' => 'autoset', 'width' => 6,])
    @include('partials.fields.checkbox',
        ['name' => 'FIT Selectable', 'field' => 'fit_selectable', 'value' => $inventory?->fit_selectable,])
    @include('partials.fields.text',
        ['name' => 'Stock', 'field' => 'stock', 'value' => $inventory?->stock,])
    @include('partials.fields.text',
        ['name' => 'Purchase Price', 'field' => 'purchase_price', 'value' => $inventory?->purchase_price, 'width' => 6, ])
    @include('partials.fields.text',
        ['name' => 'Sales Price', 'field' => 'sales_price', 'value' => $inventory?->sales_price, 'width' => 6, ])
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'internal_notes', 'value' => $inventory?->internal_notes,])
    @include('partials.fields.textarea', ['name' => 'External Notes', 'field' => 'external_notes', 'value' => $inventory?->external_notes,])
    @include('partials.fields.submit')
@endsection