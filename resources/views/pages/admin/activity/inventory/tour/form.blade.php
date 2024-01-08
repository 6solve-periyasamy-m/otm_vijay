@php
    /**
     * @var \App\Models\Tour\Tour $tour
     * @var \App\Models\Activity\ActivityInventoryTour|null $inventoryTour
     */
    $inventoryTour = $inventoryTour ?? null;
    $title = __('activity.inventory.tour.form.title.' . ($inventoryTour === null ? 'create' : 'update'));
    $route = $inventoryTour === null ?
        route('activity-inventory-tours.store', ['tour' => $tour, ]) :
        route('activity-inventory-tours.update', ['tour' => $tour, 'activityInventoryTour' => $inventoryTour,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.selector.default',
                ['name' => 'Activity Inventory', 'field' => 'activity_inventory_id', 'value' => $inventoryTour?->activity_inventory_id ?? 0,
                 'route' => 'inventory.activity'])
    @include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $inventoryTour?->tour_component_type ?? 'Included', 'width' => 4])
    @include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $inventoryTour?->tour_sales_price ?? null, 'width' => 4])
    @include('partials.fields.checkbox', ['name' => 'Stock Control Active', 'field' => 'stock_control_active', 'divClasses' => 'my-auto', 'value' => $inventoryTour?->stock_control_active ?? false, 'width' => 4])
    @include('partials.fields.submit')

@endsection