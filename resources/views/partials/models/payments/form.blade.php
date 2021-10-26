<form action="{{ $action }}" method="post">
    @csrf
    <div class="form-group">
        <label for="payment_method_id-input">Payment Method</label>
        <input name="payment_method_id" value="{{ $payment_method_id ?? "" }}" class="form-control"
               id="payment_method_id-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="amount-input">Amount</label>
        <input name="amount" value="{{ $amount ?? "" }}" class="form-control" id="amount-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="payment_type-input">Payment Type</label>
        <input name="payment_type" value="{{ $payment_type ?? "" }}" class="form-control" id="payment_type-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
