@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null, 'width' => 6])
@include('partials.fields.dropdown', [
    'name' => 'Tour Component Type',
    'field' => 'tour_component_type',
    'values' => [
        'Included' => 'Included',
        'Add-on' => 'Add-on',
    ],
    'selected' => $value ?? 'Add-on',
    'width' => 4,
])
@include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 2,])
@include('partials.fields.text',
    ['name' => 'Stock', 'field' => 'stock', 'value' => $stock ?? null, 'width' => 4,])
@include('partials.fields.text',
    ['name' => 'Purchase Price', 'field' => 'purchase_price', 'value' => $purchase_price ?? null, 'width' => 4, ])
@include('partials.fields.text',
    ['name' => 'Sales Price', 'field' => 'sales_price', 'value' => $sales_price ?? null, 'width' => 4, ])
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
