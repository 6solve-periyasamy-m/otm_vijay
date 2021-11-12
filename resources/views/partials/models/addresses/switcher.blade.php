@push('head-stack')
<script type="text/javascript">
    $(document).ready(function () {
        let {{ $prefix ?? "" }}locationTypeSelect = $('#{{ $prefix ?? "" }}location_type_id-input');
        {{ $prefix ?? "" }}locationTypeSelect.select2({
            ajax: {
                url: '{{ route('api.location-types.select') }}',
                data: function (params) { return {filter: params.term,}; }
            }
        });
        $.ajax({ url: '{{ route('api.location-types.selected', ['id' => $location_type_id ?? 0, ]) }}', })
            .then(function (data) {
                {{ $prefix ?? "" }}locationTypeSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                {{ $prefix ?? "" }}locationTypeSelect.trigger({
                    type: 'select2:select',
                    params: { data: data, }
                });
            });
        let {{ $prefix ?? "" }}countryTypeSelect = $('#{{ $prefix ?? "" }}country_id-input');
        {{ $prefix ?? "" }}countryTypeSelect.select2({
            ajax: {
                url: '{{ route('api.countries.select') }}',
                data: function (params) { return {filter: params.term,}; }
            }
        });
        $.ajax({ url: '{{ route('api.countries.selected', ['id' => $country_id ?? 0, ]) }}', })
            .then(function (data) {
                {{ $prefix ?? "" }}countryTypeSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                {{ $prefix ?? "" }}countryTypeSelect.trigger({
                    type: 'select2:select',
                    params: { data: data, }
                });
            });
    });
</script>
@endpush
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
    @include('partials.models.addresses.selector', ['id' => isset($address) ? $address->id : 0, ])
</div>
<div class="switcher-new">
    <div class="form-group col-12">
        <label for="location_type_id-input">Location Type</label>
        <div class="d-flex">
            <select name="location_type_id" class="form-control" id="location_type_id-input"></select>
            <a href="{{ route('location-types.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
        </div>
    </div>
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    <div class="form-group col-12">
        <label for="{{ $prefix ?? "" }}address_line_1-input">Address Line 1</label>
        <input name="{{ $prefix ?? "" }}address_line_1" value="{{ $address_line_1 ?? "" }}" class="form-control" id="{{ $prefix ?? "" }}address_line_1-input">
    </div>
    <div class="form-group col-12">
        <label for="{{ $prefix ?? "" }}address_line_2-input">Address Line 2</label>
        <input name="{{ $prefix ?? "" }}address_line_2" value="{{ $address_line_2 ?? "" }}" class="form-control" id="{{ $prefix ?? "" }}address_line_2-input">
    </div>
    <div class="form-group col-12">
        <label for="{{ $prefix ?? "" }}town-input">Town</label>
        <input name="{{ $prefix ?? "" }}town" value="{{ $town ?? "" }}" class="form-control" id="{{ $prefix ?? "" }}town-input">
    </div>
    <div class="form-group col-12">
        <label for="{{ $prefix ?? "" }}region-input">Region</label>
        <input name="{{ $prefix ?? "" }}region" value="{{ $region ?? "" }}" class="form-control" id="{{ $prefix ?? "" }}region-input">
    </div>
    <div class="form-group col-12">
        <label for="{{ $prefix ?? "" }}country-input">Country</label>
        <div class="d-flex">
            <select name="{{ $prefix ?? "" }}country_id" class="form-control" id="{{ $prefix ?? "" }}country_id-input"></select>
        </div>
    </div>
    <div class="form-group col-12">
        <label for="{{ $prefix ?? "" }}postcode-input">Postcode</label>
        <input name="{{ $prefix ?? "" }}postcode" value="{{ $postcode ?? "" }}" class="form-control" id="{{ $prefix ?? "" }}postcode-input">
    </div>
</div>
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
