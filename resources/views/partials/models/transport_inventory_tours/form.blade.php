@php /** @var \App\Models\Transport\TransportInventoryTour|null $transportInventoryTour */ @endphp
@include('partials.fields.selector.default',
        ['name' => 'Transport Inventory', 'field' => 'transport_inventory_id', 'value' => $transportInventoryTour?->transport_inventory_id ?? 0,
         'route' => 'inventory.transport', ])
@include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $transportInventoryTour?->tour_component_type ?? "Included", 'width' => 4])
@include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $transportInventoryTour?->tour_sales_price ?? null, 'width' => 4, ])
@include('partials.fields.checkbox', ['name' => 'Stock Control Active', 'field' => 'stock_control_active', 'value' => $transportInventoryTour?->stock_control_active ?? false, 'width' => 4, 'divClasses' => 'my-auto'])
@include('partials.fields.submit')
