@include('partials.fields.selector.default',
    ['name' => 'Accommodation Inventory', 'field' => 'accommodation_inventory_id', 'value' => $accommodation_inventory_id ?? 0,
     'route' => 'inventory.accommodation'])
@include('partials.fields.prefab.component_type')
@include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $tour_sales_price ?? null])
@include('partials.fields.submit')
