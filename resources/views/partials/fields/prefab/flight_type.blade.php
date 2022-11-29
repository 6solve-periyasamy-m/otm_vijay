@include('partials.fields.dropdown', [
    'name' => 'Flight Type',
    'field' => 'flight_type',
    'values' => [
        'Inbound' => 'Inbound',
        'Outbound' => 'Outbound',
    ],
    'selected' => $value ?? 'Inbound',
])
