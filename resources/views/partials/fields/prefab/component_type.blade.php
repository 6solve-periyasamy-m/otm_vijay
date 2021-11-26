@include('partials.fields.dropdown', [
    'name' => 'Tour Component Type',
    'field' => 'tour_component_type',
    'values' => [
        'Included' => 'Included',
        'Upgrade' => 'Upgrade',
        'Add-on' => 'Add-on',
    ],
    'selected' => $value ?? 'Included'
])
