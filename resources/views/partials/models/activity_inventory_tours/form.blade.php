@php /** @var \App\Models\Activity\ActivityInventoryTour|null $activityInventoryTour */ @endphp
@include('partials.fields.selector.default',
            ['name' => 'Activity Inventory', 'field' => 'activity_inventory_id', 'value' => $activityInventoryTour?->activity_inventory_id ?? 0,
             'route' => 'inventory.activity'])
@include('partials.fields.prefab.component_type', ['classes' => 'accommodation-component-type-select', 'value' => $activityInventoryTour?->tour_component_type ?? 'Included'])
@include('partials.fields.text', ['name' => 'Tour Sales Price', 'field' => 'tour_sales_price', 'value' => $activityInventoryTour?->tour_sales_price ?? null,])
@include('partials.fields.checkbox', ['name' => 'Stock Control Active', 'field' => 'stock_control_active', 'value' => $activityInventoryTour?->stock_control_active ?? false,])
@include('partials.fields.submit')

