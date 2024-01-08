@php
    /**
     * @var \App\Models\Flight\Flight $flight
     * @var \App\Models\Flight\FlightInventory|null $inventory
     */
    $inventory = $inventory ?? null;
    $title = __('flight.inventory.form.title.' . ($inventory === null ? 'create' : 'update'));
    $route = $inventory === null ?
        route('flight-inventories.store', ['flight' => $flight, ]) :
        route('flight-inventories.update', ['flight' => $flight, 'flightInventory' => $inventory,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @can('create', \App\Models\TravelClass::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Travel Class', 'field' => 'travel_class_id', 'value' => $inventory?->travel_class_id,
                     'route' => 'travel-classes', 'createRoute' => route('travel-classes.create'), ])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Travel Class', 'field' => 'travel_class_id', 'value' => $inventory?->travel_class_id,
                 'route' => 'travel-classes',])
    @endcan
    @include('partials.fields.text', ['name' => 'Flight Number', 'field' => 'flight_number', 'value' => $inventory?->flight_number,])
    @include('partials.fields.datetime', ['name' => 'Check In', 'field' => 'check_in', 'value' => $inventory?->check_in,])
    @include('partials.fields.datetime',
                ['name' => 'Departs At', 'field' => 'departs_at', 'value' => $inventory?->departs_at,
                 'onChange' => 'changeDate($(\'#departs_at-input\'), $(\'#arrives_at-input\'))', 'width' => 6,])
    @include('partials.fields.datetime',
                ['name' => 'Arrives At', 'field' => 'arrives_at', 'value' => $inventory?->arrives_at,
                 'onChange' => 'removeAutoset($(\'#departs_at-input\'), $(\'#arrives_at-input\'));', 'classes' => 'autoset', 'width' => 6,])
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