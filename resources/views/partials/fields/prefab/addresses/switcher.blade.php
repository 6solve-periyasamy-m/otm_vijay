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
        $(document).ready(function () { {{ $prefix ?? "" }}getLocationType(); });
        
        function getLocationType(thisVal) {
            if(thisVal == 1){
                $('.check_in_out_add').show();
            } else {
                $('.check_in_out_add').hide();
               
            }
        }

    </script>
@endpush
<hr class="splitter"/>
<div class="form-group col-xl-6">
    <input type="checkbox" name="{{ $prefix ?? "" }}use_existing" class="form-check-input"
           @if(isset($address_id)) checked @endif
    id="{{ $prefix ?? "" }}use_existing-input" onchange="{{ $prefix ?? "" }}switchView();">
    <label for="{{ $prefix ?? "" }}use_existing-input" class="form-check-label">Use Pre-Existing Address</label>
</div>
<hr class="splitter"/>
<div class="{{ $prefix ?? "" }}switcher-existing">
    @include('partials.fields.prefab.addresses.selector', ['value' => isset($address) ? $address->id : 0, ])
</div>
<div class="switcher-new row">
    @can('create', \App\Models\Location\LocationType::class)
    @include('partials.fields.selector.adder',
                ['name' => 'Location Type', 'field' => ($prefix ?? '') . 'location_type_id', 'value' => $location_type_id ?? null,
                 'route' => 'location-types', 'createRoute' => route('location-types.create'), 'onchange' => 'getLocationType($(this).val())'])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Location Type', 'field' => ($prefix ?? '') . 'location_type_id', 'value' => $location_type_id ?? null,
                 'route' => 'location-types', 'onchange' => 'getLocationType($(this).val())'])
    @endcan

  
    <hr class="splitter"/>
    @include('partials.fields.text', ['name' => 'Address Name', 'field' => ($prefix ?? "") . 'address_name', 'value' => $name ?? null,])
    @include('partials.fields.text', ['name' => 'Address Line 1', 'field' => ($prefix ?? "") . 'address_line_1', 'value' => $address_line_1 ?? null, 'width' => 6])
    @include('partials.fields.text', ['name' => 'Address Line 2', 'field' => ($prefix ?? "") . 'address_line_2', 'value' => $address_line_2 ?? null, 'width' => 6])
    @include('partials.fields.text', ['name' => 'Town', 'field' => ($prefix ?? "") . 'town', 'value' => $town ?? null, 'width' => 6])
    @include('partials.fields.text', ['name' => 'Region', 'field' => ($prefix ?? "") . 'region', 'value' => $region ?? null, 'width' => 6])
    @include('partials.fields.selector.default',
                ['name' => 'Country', 'field' => ($prefix ?? '') . 'country_id', 'value' => $country_id ?? null,
                 'route' => 'countries', 'width' => 6])
    @include('partials.fields.text', ['name' => 'Postcode', 'field' => ($prefix ?? "") . 'postcode', 'value' => $postcode ?? null, 'width' => 6])
    
    <div class="form-group col-xl-6 check_in_out_add" style="display:none">
        @include('partials.fields.raw.datetime',
                    ['name' => 'Check In', 'field' => 'check_in', 'value' => $check_in ?? null,
                     'onChange' => 'changeDate($(\'#check_in-input\'), $(\'#check_out-input\'))', ])
       </div>

    <div class="form-group col-xl-6 check_in_out_add" style="display:none">
        @include('partials.fields.raw.datetime',
                    ['name' => 'Check Out', 'field' => 'check_out', 'value' => $check_out ?? null,
                     'onChange' => 'removeAutoset($(\'#check_in-input\'), $(\'#check_out-input\'))', 'classes' => 'autoset', ])
        </div>

</div>
<hr class="splitter"/>
