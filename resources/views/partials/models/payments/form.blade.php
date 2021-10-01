<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="order_id-input">Order Id</label>
        <input name="order_id" value="{{ $order_id ?? "" }}" class="form-control" id="order_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="payment_method_id-input">Payment Method Id</label>
        <input name="payment_method_id" value="{{ $payment_method_id ?? "" }}" class="form-control"
               id="payment_method_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="amount-input">Amount</label>
        <input name="amount" value="{{ $amount ?? "" }}" class="form-control" id="amount-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="reason-input">Reason</label>
        <input name="reason" value="{{ $reason ?? "" }}" class="form-control" id="reason-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
