@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let travelClassSelect = $('#travel_class_id-input');
            travelClassSelect.select2({
                ajax: {
                    url: '{{ route('api.travel-classes.select') }}',
                }
            });
            $.ajax({ url: '{{ route('api.travel-classes.selected', ['id' => $travel_class_id ?? 0, ]) }}', })
                .then(function (data) {
                    console.log(data);
                    travelClassSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    travelClassSelect.trigger({
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
        <label for="travel_class_id-input">Travel Class</label>
        <select name="travel_class_id" class="form-control" id="travel_class_id-input"></select>
        <a href="{{ route('travel-classes.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div id="form-group">
        <label for="departure_date_time-input">Departure Date Time</label>
        <input name="departure_date_time" value="{{ $departure_date_time ?? "" }}" class="form-control"
               id="departure_date_time-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="departure_confirmed-input">Departure Confirmed</label>
        <input name="departure_confirmed" value="{{ $departure_confirmed ?? "" }}" class="form-control"
               id="departure_confirmed-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="arrival_date_time-input">Arrival Date Time</label>
        <input name="arrival_date_time" value="{{ $arrival_date_time ?? "" }}" class="form-control"
               id="arrival_date_time-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="arrival_confirmed-input">Arrival Confirmed</label>
        <input name="arrival_confirmed" value="{{ $arrival_confirmed ?? "" }}" class="form-control"
               id="arrival_confirmed-input">
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
