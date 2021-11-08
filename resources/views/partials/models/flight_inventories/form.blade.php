@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let airlineSelect = $('#travel_class_id-input');
            airlineSelect.select2({
                ajax: {
                    url: '{{ route('api.travel-classes.select') }}',
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; },
                    type: 'post'
                }
            });
            $.ajax({url: '{{ route('api.travel-classes.selected', ['id' => $travel_class_id ?? 0, ]) }}', type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }})
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
                <div class="form-group col-12">
                    <label for="flight_number-input">Flight Number</label>
                    <input name="flight_number" value="{{ $flight_number ?? "" }}" class="form-control" id="flight_number-input">
                </div>                
                <div class="form-group col-12">
                    <label for="check_in-input">Check In Date Time</label>
                    <input type="datetime-local" name="check_in" value="{{ isset($check_in) ? $check_in->format('Y-m-d\TH:i') : "" }}" class="form-control"
                        id="check_in-input">
                </div>                
                <div class="form-group col-12">
                    <label for="departs_at-input">Departs At</label>
                    <input type="datetime-local" name="departs_at" value="{{ isset($departs_at) ? $departs_at->format('Y-m-d\TH:i') : "" }}" class="form-control"
                        id="departs_at-input">
                </div>
                <div class="form-group col-12">
                    <label for="arrives_at-input">Arrives At</label>
                    <input type="datetime-local" name="arrives_at" value="{{ isset($arrives_at) ? $arrives_at->format('Y-m-d\TH:i') : "" }}" class="form-control"
                        id="arrives_at-input">
                </div>
                <div class="form-group col-12">
                    <input type="checkbox" name="fit_selectable" class="form-check-input" id="fit_selectable-input" @if(isset($fit_selectable) && $fit_selectable == 1) checked @endif >
                    <label for="fit_selectable-input" class="form-check-label">Fit Selectable</label>
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
                    <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
                </div>
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
