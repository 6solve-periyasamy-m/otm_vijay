@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let airlineSelect = $('#airline_id-input');
            airlineSelect.select2({
                ajax: {
                    url: '{{ route('api.airlines.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            $.ajax({ url: '{{ route('api.airlines.selected', ['id' => $airline_id ?? 0, ]) }}', })
                .then(function (data) {
                    airlineSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    airlineSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            let departureSelect = $('#departure_airport_id-input');
            departureSelect.select2({
                ajax: {
                    url: '{{ route('api.airports.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            $.ajax({ url: '{{ route('api.airports.selected', ['id' => $departure_airport_id ?? 0, ]) }}', })
                .then(function (data) {
                    departureSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    departureSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            let arrivalSelect = $('#arrival_airport_id-input');
            arrivalSelect.select2({
                ajax: {
                    url: '{{ route('api.airports.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            $.ajax({ url: '{{ route('api.airports.selected', ['id' => $arrival_airport_id ?? 0, ]) }}', })
                .then(function (data) {
                    arrivalSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    arrivalSelect.trigger({
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
        <label for="airline_id-input">Airline</label>
        <select name="airline_id" class="form-control" id="airline_id-input"></select>
        <a href="{{ route('airlines.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div id="form-group">
        <label for="departure_airport_id-input">Departure Airport</label>
        <select name="departure_airport_id" class="form-control" id="departure_airport_id-input"></select>
        <a href="{{ route('airports.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div id="form-group">
        <label for="arrival_airport_id-input">Arrival Airport</label>
        <select name="arrival_airport_id" class="form-control" id="arrival_airport_id-input"></select>
        <a href="{{ route('airports.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div id="form-group">
        <label for="is_domestic-input">Is Domestic</label>
        <input name="is_domestic" value="{{ $is_domestic ?? "" }}" class="form-control" id="is_domestic-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="notes-input">Notes</label>
        <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="available_after-input">Available After</label>
        <input name="available_after" value="{{ $available_after ?? "" }}" class="form-control"
               id="available_after-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
