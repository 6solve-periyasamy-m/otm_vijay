@include('partials.fields.raw.dropdown', [
    'values' => [
        '0' => $lock ?? true ? 'Never' : 'Immediately',
        '1' => '1 day',
        '3' => '3 days',
        '5' => '5 days',
        '7' => '1 week',
        '14' => '2 weeks',
        '21' => '3 weeks',
        '30' => '1 month',
        '60' => '2 months',
        '90' => '3 months'
    ],
    'selected' => $value,
])
