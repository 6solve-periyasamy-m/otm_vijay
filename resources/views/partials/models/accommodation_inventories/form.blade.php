<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="accommodation_id-input">Accommodation Id</label>
        <input name="accommodation_id" value="{{ $accommodation_id ?? "" }}" class="form-control"
               id="accommodation_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="room_type_id-input">Room Type Id</label>
        <input name="room_type_id" value="{{ $room_type_id ?? "" }}" class="form-control" id="room_type_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="board_type_id-input">Board Type Id</label>
        <input name="board_type_id" value="{{ $board_type_id ?? "" }}" class="form-control" id="board_type_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="check_in_date_time-input">Check In Date Time</label>
        <input name="check_in_date_time" value="{{ $check_in_date_time ?? "" }}" class="form-control"
               id="check_in_date_time-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="checkin_confirmed-input">Checkin Confirmed</label>
        <input name="checkin_confirmed" value="{{ $checkin_confirmed ?? "" }}" class="form-control"
               id="checkin_confirmed-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="check_out_date_time-input">Check Out Date Time</label>
        <input name="check_out_date_time" value="{{ $check_out_date_time ?? "" }}" class="form-control"
               id="check_out_date_time-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="checkout_confirmed-input">Checkout Confirmed</label>
        <input name="checkout_confirmed" value="{{ $checkout_confirmed ?? "" }}" class="form-control"
               id="checkout_confirmed-input">
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
        <label for="notes-input">Notes</label>
        <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
