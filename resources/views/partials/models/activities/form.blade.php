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
<form action="{{ $action }}" method="post">
    @csrf
    <div class="form-group">
        <label for="activity_type_id-input">Activity Type</label>
        <select style="width: 95%" name="activity_type_id" class="form-control" id="activity_type_id-input"></select>
        <a href="{{ route('activity-types.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div class="form-group">
        <label for="location_id-input">Location</label>
        <select style="width: 100%;" name="location_id" class="form-control" id="location_id-input"></select>
    </div>
    <p></p>
    <div class="form-group">
        <label for="title-input">Title</label>
        <input name="title" value="{{ $title ?? "" }}" class="form-control" id="title-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="description-input">Description</label>
        <input name="description" value="{{ $description ?? "" }}" class="form-control" id="description-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="notes-input">Notes</label>
        <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
