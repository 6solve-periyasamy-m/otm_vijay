@include('partials.fields.selector.default',
            ['name' => 'Accommodation Inventory', 'field' => 'accommodation_inventory_id', 'value' => $accommodation_inventory_id ?? 0,
             'route' => 'inventory.accommodation'])
@include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $tour_component_type, 'width' => 4,])
@include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $tour_sales_price ?? null, 'width' => 4,])
@include('partials.fields.checkbox', ['name' => 'Should this be used as a template?', 'field' => 'is_template', 'value' => $accommodationInventoryTour->is_template, 'width' => 4, 'divClasses' => 'my-auto'])
@include('partials.fields.submit')
