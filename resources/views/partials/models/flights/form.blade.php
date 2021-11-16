@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let airlineSelect = $('#airline_id-input');
            airlineSelect.select2({
                ajax: {
                    url: '{{ route('api.airlines.select') }}',
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; },
                    type: 'post'
                }
            });
            $.ajax({ url: '{{ route('api.airlines.selected', ['id' => $airline_id ?? 0, ]) }}', type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
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
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; },
                    type: 'post'
                }
            });
            $.ajax({ url: '{{ route('api.airports.selected', ['id' => $departure_airport_id ?? 0, ]) }}', type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
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
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; },
                    type: 'post'
                }
            });
            $.ajax({ url: '{{ route('api.airports.selected', ['id' => $arrival_airport_id ?? 0, ]) }}', type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
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
<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="airline_id-input">Airline</label>
                    <div class="d-flex">
                        <select name="airline_id" class="form-control" id="airline_id-input"></select>
                        <a href="{{ route('airlines.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-12">
                    <label for="departure_airport_id-input">Departure Airport</label>
                    <div class="d-flex">
                        <select name="departure_airport_id" class="form-control" id="departure_airport_id-input"></select>
                        <a href="{{ route('airports.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-12">
                    <label for="arrival_airport_id-input">Arrival Airport</label>
                    <div class="d-flex">
                        <select name="arrival_airport_id" class="form-control" id="arrival_airport_id-input"></select>
                        <a href="{{ route('airports.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-12">
                    <input type="checkbox" name="is_domestic" class="form-check-input" @if(isset($is_domestic) && $is_domestic == 1) checked @endif
                    id="is_domestic-input">
                    <label for="is_domestic-input" class="form-check-label">Is Domestic</label>
                </div>                
                <div class="form-group col-12">
                    <label for="available_after-input">Available After</label>
                    <input type="date" name="available_after" value="{{ $available_after ?? "" }}" class="form-control"
                        id="available_after-input">
                </div>
                <div class="form-group col-12">
                    <label for="notes-input">Notes</label>                    
                    <textarea name="notes" class="form-control" id="notes-input" rows="2">{{ $notes ?? "" }}</textarea>
                </div>                
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
