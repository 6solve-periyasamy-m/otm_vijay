@php /** @var \App\Models\Transport\TransportInventoryTour|null $transportInventoryTour */ @endphp
@include('partials.fields.selector.default',
        ['name' => 'Transport Inventory', 'field' => 'transport_inventory_id', 'value' => $transportInventoryTour?->transport_inventory_id ?? 0,
         'route' => 'inventory.transport', ])
@include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $transportInventoryTour?->tour_component_type ?? "Included"])
@include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $transportInventoryTour?->tour_sales_price ?? null, ])
@include('partials.fields.checkbox', ['name' => 'Stock Control Active', 'field' => 'stock_control_active', 'value' => $transportInventoryTour?->stock_control_active ?? false,])
@include('partials.fields.submit')
