@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let ticketType = $('#ticket_type_id-input');
            ticketType.select2({
                ajax: {
                    url: '{{ route('api.ticket-types.select') }}',
                    data: function (params) {
                        return {filter: params.term,};
                    }
                }
            });
            $.ajax({url: '{{ route('api.ticket-types.selected', ['id' => $ticket_type_id ?? 0, ]) }}',})
                .then(function (data) {
                    ticketType.append(new Option(data.text, data.id, true, true)).trigger('change');

                    ticketType.trigger({
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
        <label for="ticket_type_id-input">Ticket Type</label>
        <select style="width: 95%;" name="ticket_type_id" class="form-control" id="ticket_type_id-input"></select>
        <a href="{{ route('ticket-types.create') }}" target="_blank" class="btn btn-success d-inline">+</a>
    </div>
    <p></p>
    <div id="form-group">
        <label for="activity_start_date_time-input">Activity Start Date Time</label>
        <input type="datetime-local" name="activity_start_date_time" value="{{ $activity_start_date_time ?? "" }}" class="form-control"
               id="activity_start_date_time-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="activity_end_date_time-input">Activity End Date Time</label>
        <input type="datetime-local" name="activity_end_date_time" value="{{ $activity_end_date_time ?? "" }}"
               class="form-control" id="activity_end_date_time-input">
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
