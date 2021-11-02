@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let travelClassSelect = $('#travel_class_id-input');
            travelClassSelect.select2({
                ajax: {
                    url: '{{ route('api.travel-classes.select') }}',
                    data: function (params) { return {filter: params.term,}; }
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
<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="travel_class_id-input">Travel Class</label>
                    <div class="d-flex">
                        <select name="travel_class_id" class="form-control" id="travel_class_id-input"></select>
                        <a href="{{ route('travel-classes.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="departure_date_time-input">Departure Date Time</label>
                    <input type="datetime-local" name="departure_date_time" value="{{ isset($departure_date_time) ? $departure_date_time->format('Y-m-d\TH:i') : "" }}" class="form-control"
                        id="departure_date_time-input">
                    <p></p>
                    <input type="checkbox" name="departure_confirmed" class="form-check-input" @if(isset($departure_confirmed) && $departure_confirmed == 1) checked @endif
                        id="departure_confirmed-input">
                    <label for="departure_confirmed-input">Departure Confirmed</label>
                </div>                                
                <div class="form-group col-12 col-xl-6">
                    <label for="arrival_date_time-input">Arrival Date Time</label>
                    <input type="datetime-local" name="arrival_date_time" value="{{ isset($arrival_date_time) ? $arrival_date_time->format('Y-m-d\TH:i') : "" }}" class="form-control"
                        id="arrival_date_time-input">
                    <p></p>
                    <input type="checkbox" name="arrival_confirmed" class="form-check-input" @if(isset($arrival_confirmed) && $arrival_confirmed == 1) checked @endif
                        id="arrival_confirmed-input">
                    <label for="arrival_confirmed-input">Arrival Confirmed</label>
                </div>
                <div class="form-group col-12">
                    <input type="checkbox" name="fit_selectable" class="form-check-input" id="fit_selectable-input" @if(isset($fit_selectable) && $fit_selectable == 1) checked @endif>
                    <label for="fit_selectable-input">Fit Selectable</label>
                </div>                
                <div class="form-group col-12">
                    <label for="stock-input">Stock</label>
                    <input name="stock" value="{{ $stock ?? "" }}" class="form-control" id="stock-input">
                </div>                
                <div class="form-group col-12">
                    <label for="purchase_price-input">Purchase Price</label>
                    <input name="purchase_price" value="{{ $purchase_price ?? "" }}" class="form-control" id="purchase_price-input">
                </div>                
                <div class="form-group col-12">
                    <label for="sales_price-input">Sales Price</label>
                    <input name="sales_price" value="{{ $sales_price ?? "" }}" class="form-control" id="sales_price-input">
                </div>                
                <div class="form-group col-12">
                    <label for="currency-input">Currency</label>
                    <input name="currency" value="{{ $currency ?? "" }}" class="form-control" id="currency-input">
                </div>                
                <div class="form-group col-12">
                    <label for="notes-input">Notes</label>
                    <textarea name="notes" class="form-control" id="notes-input">{{ $notes ?? "" }}</textarea>                    
                </div>                
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
