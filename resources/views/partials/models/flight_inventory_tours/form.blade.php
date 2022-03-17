@include('partials.fields.selector.default',
            ['name' => 'Flight Inventory', 'field' => 'flight_inventory_id', 'value' => $flight_inventory_id ?? 0,
             'route' => 'inventory.flight'])
@include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $tour_component_type])
@include('partials.fields.text', ['name' => 'Flight Type', 'field' => 'flight_type', 'value' => $flight_type ?? null, ])
@include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $tour_sales_price ?? null, ])
@include('partials.fields.submit')
