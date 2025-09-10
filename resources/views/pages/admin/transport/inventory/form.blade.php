@php
    /**
     * @var \App\Models\Transport\Transport $transport
     * @var \App\Models\Transport\TransportInventory|null $inventory
     */
    $inventory = $inventory ?? null;
    $title = __('transport.inventory.form.title.' . ($inventory === null ? 'create' : 'update'));
    $route = $inventory === null ?
        route('transport-inventories.store', ['transport' => $transport, ]) :
        route('transport-inventories.update', ['transport' => $transport, 'inventory' => $inventory,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Transport Number', 'field' => 'transport_number', 'value' => $inventory?->transport_number, 'width' => 6,])
    @can('create', \App\Models\TravelClass::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Travel Class', 'field' => 'travel_class_id', 'value' => $inventory?->travel_class_id,
                     'route' => 'travel-classes', 'createRoute' => route('travel-classes.create'), 'width' => 3,])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Travel Class', 'field' => 'travel_class_id', 'value' => $inventory?->travel_class_id,
                 'route' => 'travel-classes', 'width' => 3,])
    @endcan
    @include('partials.fields.selector.default',
                    ['name' => 'Occupancy', 'field' => 'transport_occupancy_id', 'value' => $inventory?->transport_occupancy_id,
                     'route' => 'transport-occupancy', 'width' => 3,])
    <div class="form-group col-xl-6">
        @include('partials.fields.raw.datetime',
                    ['name' => 'Departs At', 'field' => 'departs_at', 'value' => $inventory?->departs_at,
                     'onChange' => 'changeDate($(\'#departs_at-input\'), $(\'#arrives_at-input\'))', ])
        <p></p>
        @include('partials.fields.raw.checkbox',
                    ['name' => 'Departure Confirmed', 'field' => 'departure_time_confirmed', 'value' => $inventory?->departure_time_confirmed, ])
    </div>
    <div class="form-group col-xl-6">
        @include('partials.fields.raw.datetime',
                    ['name' => 'Arrives At', 'field' => 'arrives_at', 'value' => $inventory?->arrives_at,
                     'onChange' => 'removeAutoset($(\'#departs_at-input\'), $(\'#arrives_at-input\'))', 'classes' => 'autoset', ])
        <p></p>
        @include('partials.fields.raw.checkbox',
                    ['name' => 'Arrival Confirmed', 'field' => 'arrival_time_confirmed', 'value' => $inventory?->arrival_time_confirmed, ])
    </div>
    @include('partials.fields.checkbox',
        ['name' => 'FIT Selectable', 'field' => 'fit_selectable', 'value' => $inventory?->fit_selectable,])
    @include('partials.fields.text',
        ['name' => 'Stock', 'field' => 'stock', 'value' => $inventory?->stock,])
    @include('partials.fields.text',
        ['name' => 'Purchase Price', 'field' => 'purchase_price', 'value' => $inventory?->purchase_price, 'width' => 3, ])
    <x-livewire.input.select.currency name="currency_id" width="3" label="Currency Override" value="{{ $inventory->currency_id ?? null }}" clearable />
    @include('partials.fields.text',
        ['name' => 'Sales Price', 'field' => 'sales_price', 'value' => $inventory?->sales_price, 'width' => 6, ])
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'internal_notes', 'value' => $inventory?->internal_notes,])
    @include('partials.fields.textarea', ['name' => 'External Notes', 'field' => 'external_notes', 'value' => $inventory?->external_notes,])
    @include('partials.fields.submit')
@endsection
