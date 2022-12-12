@include('partials.fields.selector.default',
        ['name' => 'Transport Inventory', 'field' => 'transport_inventory_id', 'value' => $transport_inventory_id ?? 0,
         'route' => 'inventory.transport', ])
@include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $tour_component_type, 'width' => 6])
@include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $tour_sales_price ?? null, 'width' => 6])
@include('partials.fields.submit')
