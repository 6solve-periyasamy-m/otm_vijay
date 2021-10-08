@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let airlineSelect = $('#airline_id-input');
            airlineSelect.select2({
                ajax: {
                    url: '{{ route('api.travel-classes.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            $.ajax({url: '{{ route('api.travel-classes.selected', ['id' => $travel_class_id ?? 0, ]) }}',})
                .then(function (data) {
                    airlineSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    airlineSelect.trigger({
                        type: 'select2:select',
                        params: {data: data,}
                    });
                });
        });
    </script>
@endsection
<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="travel_class_id-input">Travel Class</label>
        <select style="width: 95%;" name="travel_class_id" class="form-control" id="travel_class_id-input"></select>
        <a href="{{ route('travel-classes.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div id="form-group">
        <label for="flight_number-input">Flight Number</label>
        <input name="flight_number" value="{{ $flight_number ?? "" }}" class="form-control" id="flight_number-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="check_in_date_time-input">Check In Date Time</label>
        <input name="check_in_date_time" value="{{ $check_in_date_time ?? "" }}" class="form-control"
               id="check_in_date_time-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="departure_date_time-input">Departure Date Time</label>
        <input name="departure_date_time" value="{{ $departure_date_time ?? "" }}" class="form-control"
               id="departure_date_time-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="arrival_date_time-input">Arrival Date Time</label>
        <input name="arrival_date_time" value="{{ $arrival_date_time ?? "" }}" class="form-control"
               id="arrival_date_time-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="fit_selectable-input">Fit Selectable</label>
        <input name="fit_selectable" value="{{ $fit_selectable ?? "" }}" class="form-control" id="fit_selectable-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="stock-input">Stock</label>
        <input name="stock" value="{{ $stock ?? "" }}" class="form-control" id="stock-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="purchase_price-input">Purchase Price</label>
        <input name="purchase_price" value="{{ $purchase_price ?? "" }}" class="form-control" id="purchase_price-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="sales_price-input">Sales Price</label>
        <input name="sales_price" value="{{ $sales_price ?? "" }}" class="form-control" id="sales_price-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="currency-input">Currency</label>
        <input name="currency" value="{{ $currency ?? "" }}" class="form-control" id="currency-input">
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
