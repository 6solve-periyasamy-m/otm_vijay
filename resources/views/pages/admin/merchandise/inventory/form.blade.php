@extends('layout.form', ['action' => (isset($inventory) ? route('merchandise.inventory.update', ['merchandise' => $merchandise, 'inventory' => $inventory]) : route('merchandise.inventory.store', ['merchandise' => $merchandise,])), 'multipart' => true,])

@section('title', (isset($inventory) ? 'Update' : 'Create New') . ' Merchandise Inventory')

@php
/**
 * @var \App\Models\Merchandise\Merchandise $merchandise
 * @var \App\Models\Merchandise\MerchandiseInventory|null $inventory
 */
$inventory = $inventory ?? null;
@endphp

@section('form-body')
    @include('partials.fields.selector.adder', ['name' => 'Variant', 'field' => 'variant', 'route' => 'variants', 'createRoute' => route('merchandise.variant.create'), 'value' => $inventory?->variant_id ?? null, 'width' => 5])
    @include('partials.fields.selector.adder', ['name' => 'Size', 'field' => 'size', 'route' => 'sizes', 'createRoute' => route('merchandise.size.create'), 'value' => $inventory?->merchandise_size_id ?? null, 'width' => 5])
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 2])
    @include('partials.fields.checkbox',
        ['name' => 'FIT Selectable', 'field' => 'fit_selectable', 'value' => $inventory?->fit_selectable ?? null, ])
    @include('partials.fields.text',
        ['name' => 'Stock', 'field' => 'stock', 'value' => $inventory?->stock ?? null, ])
    @include('partials.fields.text',
        ['name' => 'Purchase Price', 'field' => 'purchase_price', 'value' => $inventory?->purchase_price ?? null, 'width' => 6, ])
    @include('partials.fields.text',
        ['name' => 'Sales Price', 'field' => 'sales_price', 'value' => $inventory?->sales_price ?? null, 'width' => 6, ])
    @include('partials.fields.textarea', ['name' => 'Notes', 'field' => 'notes', 'value' => $inventory?->notes,])
    @include('partials.fields.submit')
@endsection
