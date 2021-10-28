<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="due_on-input">Due On</label>
    <input type="date" name="due_on" value="{{ $due_on ?? "" }}" class="form-control" id="due_on-input">
  </div><p></p>
  <div id="form-group">
    <label for="amount-input">Amount</label>
    <input name="amount" value="{{ $amount ?? "" }}" class="form-control" id="amount-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
