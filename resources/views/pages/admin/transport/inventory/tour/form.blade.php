@php
    /**
     * @var \App\Models\Tour\Tour $tour
     * @var \App\Models\Transport\TransportInventoryTour|null $inventoryTour
     */
    $inventoryTour = $inventoryTour ?? null;
    $title = __('transport.inventory.tour.form.title.' . ($inventoryTour === null ? 'create' : 'update'));
    $route = $inventoryTour === null ?
        route('transport-inventory-tours.store', ['tour' => $tour, ]) :
        route('transport-inventory-tours.update', ['tour' => $tour, 'inventoryTour' => $inventoryTour,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.selector.default',
            ['name' => 'Transport Inventory', 'field' => 'transport_inventory_id', 'value' => $inventoryTour?->transport_inventory_id ?? 0,
             'route' => 'inventory.transport', ])
    @include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $inventoryTour?->tour_component_type ?? "Included", 'width' => 4])
    @include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $inventoryTour?->tour_sales_price ?? null, 'width' => 4, ])
    @include('partials.fields.checkbox', ['name' => 'Stock Control Active', 'field' => 'stock_control_active', 'value' => $inventoryTour?->stock_control_active ?? false, 'width' => 4, 'divClasses' => 'my-auto'])
    @include('partials.fields.submit')
@endsection
