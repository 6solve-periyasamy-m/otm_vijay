@php
    /**
     * @var \App\Models\Tour\Tour $tour
     * @var \App\Models\Flight\FlightInventoryTour|null $inventoryTour
     */
    $inventoryTour = $inventoryTour ?? null;
    $title = __('flight.inventory.tour.form.title.' . ($inventoryTour === null ? 'create' : 'update'));
    $route = $inventoryTour === null ?
        route('flight-inventory-tours.store', ['tour' => $tour, ]) :
        route('flight-inventory-tours.update', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.selector.default',
            ['name' => 'Flight Inventory', 'field' => 'flight_inventory_id', 'value' => $inventoryTour?->flight_inventory_id,
             'route' => 'inventory.flight', 'width' => 6,])
    @include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $inventoryTour?->tour_component_type, 'width' => 3,])
    @include('partials.fields.prefab.flight_type', ['value' => $inventoryTour?->flight_type, 'width' => 3,])
    @include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $inventoryTour?->tour_sales_price, 'width' => 3,])
    @include('partials.fields.checkbox', ['name' => 'Stock Control Active', 'field' => 'stock_control_active', 'value' => $inventoryTour?->stock_control_active, 'divClasses' => 'my-auto', 'width' => 3,])
    @include('partials.fields.checkbox', ['name' => 'Bookable', 'field' => 'is_bookable', 'value' => $inventoryTour?->is_bookable ?? false, 'divClasses' => 'my-auto', 'width' => 3])
    @include('partials.fields.text', ['name' => 'Document Order', 'field' => 'order', 'value' => $inventoryTour->document_order, 'width' => 3])
    @include('partials.fields.submit')
@endsection
