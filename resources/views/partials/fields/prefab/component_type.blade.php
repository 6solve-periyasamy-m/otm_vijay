@if($value == 'Upgrade')
    <input type="hidden" name="tour_component_type-input" value="Upgrade">
@else
@include('partials.fields.dropdown', [
    'name' => 'Tour Component Type',
    'field' => 'tour_component_type',
    'values' => [
        'Included' => 'Included',
        'Add-on' => 'Add-on',
    ],
    'selected' => $value ?? 'Included'
])
@endif
