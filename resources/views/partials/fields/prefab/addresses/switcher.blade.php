@push('footer-stack')
    <script type="text/javascript">
        function {{ $prefix ?? "" }}switchView() {
            let createNew = $('#{{ $prefix ?? "" }}use_existing-input').is(':checked');
            if (createNew) {
                $('.{{ $prefix ?? "" }}switcher-new').hide();
                $('.{{ $prefix ?? "" }}switcher-existing').show();
            } else {
                $('.{{ $prefix ?? "" }}switcher-new').show();
                $('.{{ $prefix ?? "" }}switcher-existing').hide();
            }
        }
        $(document).ready(function () { {{ $prefix ?? "" }}switchView(); });
    </script>
@endpush
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
<div class="form-group col-xl-6">
    <input type="checkbox" name="{{ $prefix ?? "" }}use_existing" class="form-check-input"
           @if(isset($address_id)) checked @endif
    id="{{ $prefix ?? "" }}use_existing-input" onchange="{{ $prefix ?? "" }}switchView();">
    <label for="{{ $prefix ?? "" }}use_existing-input" class="form-check-label">Use Pre-Existing Address</label>
</div>
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
<div class="{{ $prefix ?? "" }}switcher-existing">
    @include('partials.fields.prefab.addresses.selector', ['value' => isset($address) ? $address->id : 0, ])
</div>
<div class="switcher-new">
    @can('create', \App\Models\LocationType::class)
    @include('partials.fields.selector.adder',
                ['name' => 'Location Type', 'field' => ($prefix ?? '') . 'location_type_id', 'value' => $location_type_id ?? null,
                 'route' => 'location-types', 'createRoute' => route('location-types.create')])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Location Type', 'field' => ($prefix ?? '') . 'location_type_id', 'value' => $location_type_id ?? null,
                 'route' => 'location-types', ])
    @endcan
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    @include('partials.fields.text', ['name' => 'Address Line 1', 'field' => ($prefix ?? "") . 'address_line_1', 'value' => $address_line_1 ?? null,])
    @include('partials.fields.text', ['name' => 'Address Line 2', 'field' => ($prefix ?? "") . 'address_line_2', 'value' => $address_line_2 ?? null,])
    @include('partials.fields.text', ['name' => 'Town', 'field' => ($prefix ?? "") . 'town', 'value' => $town ?? null,])
    @include('partials.fields.text', ['name' => 'Region', 'field' => ($prefix ?? "") . 'region', 'value' => $region ?? null,])
    @include('partials.fields.selector.default',
                ['name' => 'Country', 'field' => ($prefix ?? '') . 'country_id', 'value' => $country_id ?? null,
                 'route' => 'countries', ])
    @include('partials.fields.text', ['name' => 'Postcode', 'field' => ($prefix ?? "") . 'postcode', 'value' => $postcode ?? null,])
</div>
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
