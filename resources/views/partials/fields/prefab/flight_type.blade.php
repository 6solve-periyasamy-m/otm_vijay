@include('partials.fields.dropdown', [
    'name' => 'Flight Type',
    'field' => 'flight_type',
    'values' => [
        'Inbound' => 'Inbound',
        'Outbound' => 'Outbound',
        'Mid-Package' => 'Mid-Package'
    ],
    'selected' => $value ?? 'Mid-Package',
])
