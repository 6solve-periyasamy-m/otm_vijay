@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            // Transport Types
            let transportTypeSelect = $('#transport_type_id-input');
            transportTypeSelect.select2({
                ajax: {
                    url: '{{ route('api.transport-types.select') }}',
                }
            });
            $.ajax({ url: '{{ route('api.transport-types.selected', ['id' => $transport_type_id ?? 0, ]) }}', })
                .then(function (data) {
                    transportTypeSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    transportTypeSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            // Operators
            let operatorsSelect = $('#operator_id-input');
            operatorsSelect.select2({
                ajax: {
                    url: '{{ route('api.operators.select') }}',
                }
            });
            $.ajax({ url: '{{ route('api.operators.selected', ['id' => $operator_id ?? 0, ]) }}', })
                .then(function (data) {
                    operatorsSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    operatorsSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            // Departure Location
            let departureLocationSelect = $('#departure_location_id-input');
            departureLocationSelect.select2({
                ajax: {
                    url: '{{ route('api.locations.select') }}',
                }
            });
            $.ajax({ url: '{{ route('api.locations.selected', ['id' => $departure_location_id ?? 0, ]) }}', })
                .then(function (data) {
                    departureLocationSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    departureLocationSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            // Arrival Location
            let arrivalLocationSelect = $('#arrival_location_id-input');
            arrivalLocationSelect.select2({
                ajax: {
                    url: '{{ route('api.locations.select') }}',
                }
            });
            $.ajax({ url: '{{ route('api.locations.selected', ['id' => $arrival_location_id ?? 0, ]) }}', })
                .then(function (data) {
                    arrivalLocationSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    arrivalLocationSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
        });
    </script>
@endsection
<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="transport_type_id-input">Transport Type</label>
        <select style="width: 95%;" name="transport_type_id" class="form-control" id="transport_type_id-input"></select>
        <a href="{{ route('transport-types.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div id="form-group">
        <label for="operator_id-input">Operator</label><br/>
        <select style="width: 95%;" name="operator_id" class="form-control" id="operator_id-input"></select>
        <a href="{{ route('operators.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div id="form-group">
        <label for="departure_location_id-input">Departure Location</label>
        <select style="width: 100%;" name="departure_location_id" class="form-control" id="departure_location_id-input"></select>
    </div>
    <p></p>
    <div id="form-group">
        <label for="arrival_location_id-input">Arrival Location</label>
        <select style="width: 100%;" name="arrival_location_id" class="form-control" id="arrival_location_id-input"></select>
    </div>
    <p></p>
    <div id="form-group">
        <label for="name-input">Name</label>
        <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="description-input">Description</label>
        <input name="description" value="{{ $description ?? "" }}" class="form-control" id="description-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="currency-input">Currency</label>
        <input name="currency" value="{{ $currency ?? "" }}" class="form-control" id="currency-input">
    </div>
    <p></p>
    <div id="form-group">
        <input type="checkbox" name="is_domestic" class="form-check-input" id="is_domestic-input" @if(isset($is_domestic) && $is_domestic == 1) checked @endif>
        <label for="is_domestic-input">Is Domestic</label>
    </div>
    <p></p>
    <div id="form-group">
        <label for="notes-input">Notes</label>
        <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
