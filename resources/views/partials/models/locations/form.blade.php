@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let regionSelect = $('#region_id-input');
            regionSelect.select2({
                ajax: {
                    url: '{{ route('api.regions.select') }}',
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; },
                    type: 'post'
                }
            });
            $.ajax({ url: '{{ route('api.regions.selected', ['id' => $region_id ?? 0, ]) }}', type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
                .then(function (data) {
                    regionSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    regionSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            let locationTypeSelect = $('#location_type_id-input');
            locationTypeSelect.select2({
                ajax: {
                    url: '{{ route('api.location-types.select') }}',
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; },
                    type: 'post'
                }
            });
            $.ajax({ url: '{{ route('api.location-types.selected', ['id' => $location_type_id ?? 0, ]) }}', type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
                .then(function (data) {
                    locationTypeSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    locationTypeSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
        });
    </script>
@endsection
<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="region_id-input">Region</label>
                    <div class="d-flex">
                        <select name="region_id" class="form-control" id="region_id-input"></select>
                        <a href="{{ route('regions.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a> 
                    </div>
                </div>                
                <div class="form-group col-12">
                    <label for="location_type_id-input">Location Type</label>
                    <div class="d-flex">
                        <select name="location_type_id" class="form-control" id="location_type_id-input"></select>
                        <a href="{{ route('location-types.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-12">
                    <label for="name-input">Name</label>
                    <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
                </div>                
                <div class="form-group col-12">
                    <label for="address-input">Address</label>
                    <input name="address" value="{{ $address ?? "" }}" class="form-control" id="address-input">
                </div>                
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
