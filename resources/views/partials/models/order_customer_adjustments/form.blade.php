<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="order_customer_id-input">Order Customer Id</label>
    <input name="order_customer_id" value="{{ $order_customer_id ?? "" }}" class="form-control" id="order_customer_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="amount-input">Amount</label>
    <input name="amount" value="{{ $amount ?? "" }}" class="form-control" id="amount-input">
  </div><p></p>
  <div id="form-group">
    <label for="reason-input">Reason</label>
    <input name="reason" value="{{ $reason ?? "" }}" class="form-control" id="reason-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
