<form action="{{ $action }}" method="post">
    @csrf
    <div class="form-group">
        <label for="amount-input">Amount</label>
        <input name="amount" value="{{ $amount ?? "" }}" class="form-control" id="amount-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="reason-input">Reason</label>
        <input name="reason" value="{{ $reason ?? "" }}" class="form-control" id="reason-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="date-input">Date</label>
        <input type="datetime-local" name="date" value="{{ $date ?? "" }}" class="form-control" id="date-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
