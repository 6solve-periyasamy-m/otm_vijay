@php /** @var \App\Models\Flight\FlightInventoryTour|null $flightInventoryTour */ @endphp
@include('partials.fields.selector.default',
            ['name' => 'Flight Inventory', 'field' => 'flight_inventory_id', 'value' => $flightInventoryTour?->flight_inventory_id ?? 0,
             'route' => 'inventory.flight'])
@include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $flightInventoryTour?->tour_component_type, 'width' => 4,])
@include('partials.fields.prefab.flight_type', ['value' => $flightInventoryTour?->flight_type ?? null, 'width' => 4,])
@include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $flightInventoryTour?->tour_sales_price ?? null, 'width' => 4,])
@include('partials.fields.checkbox', ['name' => 'Stock Control Active', 'field' => 'stock_control_active', 'value' => $flightInventoryTour?->stock_control_active ?? false,])
@include('partials.fields.submit')
