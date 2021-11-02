@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let activitySelect = $('#activity_type_id-input');
            activitySelect.select2({
                ajax: {
                    url: '{{ route('api.activity-types.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            $.ajax({ url: '{{ route('api.activity-types.selected', ['id' => $activity_type_id ?? 0, ]) }}', })
                .then(function (data) {
                    activitySelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    activitySelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            let locationSelect = $('#location_id-input');
            locationSelect.select2({
                ajax: {
                    url: '{{ route('api.locations.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            $.ajax({ url: '{{ route('api.locations.selected', ['id' => $activity_type_id ?? 0, ]) }}', })
                .then(function (data) {
                    locationSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    locationSelect.trigger({
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
                    <label for="activity_type_id-input">Activity Type</label>
                    <div class="d-flex">
                        <select name="activity_type_id" class="form-control" id="activity_type_id-input"></select>
                        <a href="{{ route('activity-types.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-12">
                    <label for="location_id-input">Location</label>
                    <div class="d-flex">
                        <select name="location_id" class="form-control" id="location_id-input"></select>
                        <a href="{{ route('locations.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>
                <div class="form-group col-12">
                    <label for="name-input">Name</label>
                    <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
                </div>                
                <div class="form-group col-12">
                    <label for="description-input">Description</label>
                    <input name="description" value="{{ $description ?? "" }}" class="form-control" id="description-input">
                </div>                
                <div class="form-group col-12">
                    <label for="notes-input">Notes</label>
                    <textarea name="notes" class="form-control"  id="notes-input" rows="2">{{ $notes ?? "" }}</textarea>                    
                </div>                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
