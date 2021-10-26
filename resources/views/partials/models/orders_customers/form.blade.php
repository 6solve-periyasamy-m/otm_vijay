@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let customerSelect = $('#customer_id-input');
            customerSelect.select2({
                ajax: {
                    url: '{{ route('api.customers.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            $.ajax({url: '{{ route('api.customers.selected', ['id' => $customer_id ?? 0, ]) }}',})
                .then(function (data) {
                    customerSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    customerSelect.trigger({
                        type: 'select2:select',
                        params: {data: data,}
                    });
                });
        });
    </script>
@endsection
<form action="{{ $action }}" method="post">
    @csrf
    <div class="form-group">
        <label for="customer_id-input">Customer</label>
        <select style="width: 100%" name="customer_id"  class="form-control" id="customer_id-input"></select>
    </div>
    <p></p>
    <div class="form-group">
        <label for="tour_cost-input">Tour Cost</label>
        <input name="tour_cost" value="{{ $tour_cost ?? "" }}" class="form-control" id="tour_cost-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="single_occupancy_surcharge-input">Single Occupancy Surcharge</label>
        <input name="single_occupancy_surcharge" value="{{ $single_occupancy_surcharge ?? "" }}" class="form-control"
               id="single_occupancy_surcharge-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="travel_insurer-input">Travel Insurer</label>
        <input name="travel_insurer" value="{{ $travel_insurer ?? "" }}" class="form-control" id="travel_insurer-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="policy_number-input">Policy Number</label>
        <input name="policy_number" value="{{ $policy_number ?? "" }}" class="form-control" id="policy_number-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
