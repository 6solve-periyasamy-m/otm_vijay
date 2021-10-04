@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let roomTypeSelect = $('#room_type_id-input');
            roomTypeSelect.select2({
                ajax: {
                    url: '{{ route('api.room-types.select') }}',
                }
            });
            $.ajax({ url: '{{ route('api.room-types.selected', ['id' => $room_type_id ?? 0, ]) }}', })
                .then(function (data) {
                    console.log(data);
                    roomTypeSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    roomTypeSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            let boardTypeSelect = $('#board_type_id-input');
            boardTypeSelect.select2({
                ajax: {
                    url: '{{ route('api.board-types.select') }}',
                }
            });
            $.ajax({ url: '{{ route('api.board-types.selected', ['id' => $room_type_id ?? 0, ]) }}', })
                .then(function (data) {
                    console.log(data);
                    boardTypeSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    boardTypeSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
        });
    </script>
@endsection
<form action="{{ $action }}" method="post">
    @csrf
    <p></p>
    <div id="form-group">
        <label for="room_type_id-input">Room Type Id</label>
        <select style="width: 100%" name="room_type_id" class="form-control" id="room_type_id-input"> </select>
    </div>
    <p></p>
    <div id="form-group">
        <label for="board_type_id-input">Board Type Id</label>
        <select style="width: 100%" name="board_type_id" class="form-control" id="board_type_id-input"></select>
    </div>
    <p></p>
    <div id="form-group">
        <label for="check_in_date_time-input">Check In Date Time</label>
        <input type="datetime-local" name="check_in_date_time" value="{{ isset($check_in_date_time) ? $check_in_date_time->format('Y-m-d\TH:i') : "" }}" class="form-control"
               id="check_in_date_time-input">
    </div>
    <p></p>
    <div id="form-group">
        <input type="checkbox" name="checkin_confirmed" class="form-check-input" @if(isset($checkin_confirmed) && $checkin_confirmed == 1) checked @endif
               id="checkin_confirmed-input">
        <label for="checkin_confirmed-input" class="form-check-label">Checkin Confirmed</label>
    </div>
    <p></p>
    <div id="form-group">
        <label for="check_out_date_time-input">Check Out Date Time</label>
        <input type="datetime-local" name="check_out_date_time" value="{{ isset($check_out_date_time) ? $check_out_date_time->format('Y-m-d\TH:i') : "" }}" class="form-control"
               id="check_out_date_time-input">
    </div>
    <p></p>
    <div id="form-group">
        <input type="checkbox" name="checkout_confirmed" class="form-check-input" @if(isset($checkout_confirmed) && $checkout_confirmed == 1) checked @endif
               id="checkout_confirmed-input">
        <label for="checkout_confirmed-input" class="form-check-label">Checkout Confirmed</label>
    </div>
    <p></p>
    <div id="form-group">
        <input type="checkbox" name="fit_selectable" class="form-check-input" id="fit_selectable-input" @if(isset($fit_selectable) && $fit_selectable == 1) checked @endif >
        <label for="fit_selectable-input" class="form-check-label">Fit Selectable</label>
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
        <label for="notes-input">Notes</label>
        <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
