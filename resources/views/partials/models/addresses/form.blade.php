@php
/**
 * @var \App\Models\Location\Address|null $address
 * @var string|null $prefix
 */
$prefix = $prefix ?? "";
@endphp
@can('create', \App\Models\Location\LocationType::class)
    @include('partials.fields.selector.adder',
                ['name' => 'Location Type', 'field' => ($prefix ?? '') . 'location_type_id', 'value' => $address?->location_type_id,
                 'route' => 'location-types', 'createRoute' => route('location-types.create'), ])
@else
    @include('partials.fields.selector.default',
            ['name' => 'Location Type', 'field' => ($prefix ?? '') . 'location_type_id', 'value' => $address?->location_type_id,
             'route' => 'location-types', 'onchange' => 'getLocationType($(this).val())'])
@endcan
<hr class="splitter"/>
@include('partials.fields.text', ['name' => 'Address Name', 'field' => ($prefix) . 'address_name', 'value' => $address?->name,])
@include('partials.fields.text', ['name' => 'Address Line 1', 'field' => ($prefix) . 'address_line_1', 'value' => $address?->address_line_1, 'width' => 6])
@include('partials.fields.text', ['name' => 'Address Line 2', 'field' => ($prefix) . 'address_line_2', 'value' => $address?->address_line_2, 'width' => 6])
@include('partials.fields.text', ['name' => 'Town', 'field' => ($prefix) . 'town', 'value' => $address?->town, 'width' => 6])
@include('partials.fields.text', ['name' => 'Region', 'field' => ($prefix) . 'region', 'value' => $address?->region, 'width' => 6])
<x-livewire.input.select.country label="Country" name="{{ ($prefix) . 'country_id' }}" value="{{$address?->country_id}}" width="6" />
@include('partials.fields.text', ['name' => 'Postcode', 'field' => ($prefix) . 'postcode', 'value' => $address?->postcode, 'width' => 6])
@include('partials.fields.submit')
