@php /** @var \App\Models\Accommodation\AccommodationInventoryTour|null $accommodationInventoryTour */ @endphp
@include('partials.fields.selector.default',
            ['name' => 'Accommodation Inventory', 'field' => 'accommodation_inventory_id', 'value' => $accommodationInventoryTour?->id ?? 0,
             'route' => 'inventory.accommodation'])
@include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $accommodationInventoryTour?->tour_component_type])
@include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $accommodationInventoryTour?->tour_sales_price])
@include('partials.fields.checkbox', ['name' => 'Should this be used as a template?', 'field' => 'is_template', 'value' => $accommodationInventoryTour?->is_template ?? false, 'width' => 6, 'divClasses' => 'my-auto'])
@include('partials.fields.checkbox', ['name' => 'Stock Control Active', 'field' => 'stock_control_active', 'value' => $accommodationInventoryTour?->stock_control_active ?? false, 'width' => 6])
@include('partials.fields.submit')
