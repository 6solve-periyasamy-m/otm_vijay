@extends('layout.form', ['action' => (isset($inventoryTour) ? route('merchandise.inventory.tour.update', ['tour' => $tour, 'inventoryTour' => $inventoryTour]) : route('merchandise.inventory.store', ['merchandise' => $merchandise,])), ])

@section('title', (isset($inventoryTour) ? 'Update' : 'Create New') . ' Merchandise Inventory Tour')

@php
    /**
     * @var \App\Models\Tour\Tour $tour
     * @var \App\Models\Merchandise\MerchandiseInventoryTour|null $inventoryTour
     */
    $inventoryTour = $inventoryTour ?? null;
@endphp

@section('form-body')
    @include('partials.fields.dropdown', [
                'name' => 'Tour Component Type',
                'field' => 'tour_component_type',
                'values' => [
                    'Included' => 'Included',
                    'Add-on' => 'Add-on',
                ],
                'selected' => $inventoryTour?->tour_component_type ?? 'Included',
                'width' => 6,
            ])
    @include('partials.fields.text',
        ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $inventoryTour?->tour_sales_price ?? null, 'width' => 6,])
    @include('partials.fields.checkbox',
        ['name' => 'Bookable?', 'field' => 'is_bookable', 'value' => $inventoryTour?->is_bookable ?? false, ])
    @include('partials.fields.submit')
@endsection
