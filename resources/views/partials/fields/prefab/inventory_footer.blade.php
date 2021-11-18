@include('partials.fields.checkbox',
    ['name' => 'Fit Selectable', 'field' => 'fit_selectable', 'value' => $fit_selectable ?? null, ])
@include('partials.fields.text',
    ['name' => 'Stock', 'field' => 'stock', 'value' => $stock ?? null, ])
@include('partials.fields.text',
    ['name' => 'Purchase Price', 'field' => 'purchase_price', 'value' => $purchase_price ?? null, 'width' => 6, ])
@include('partials.fields.text',
    ['name' => 'Sales Price', 'field' => 'sales_price', 'value' => $sales_price ?? null, 'width' => 6, ])
