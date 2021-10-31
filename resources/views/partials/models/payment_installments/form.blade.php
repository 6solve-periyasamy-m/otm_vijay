<div class="card">
  <div class="card-body">    
    <form action="{{ $action }}" method="post">
      @csrf
      <div class="row">
        <div id="form-group col-12">
          <label for="due_on-input">Due On</label>
          <input type="date" name="due_on" value="{{ $due_on ?? "" }}" class="form-control" id="due_on-input">
        </div><p></p>
        <div id="form-group col-12">
          <label for="amount-input">Amount</label>
          <input name="amount" value="{{ $amount ?? "" }}" class="form-control" id="amount-input">
        </div><p></p>
        <div id="form-group col-12">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>
  </div>  
</div>
