<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="amount-input">Amount</label>
                    <input name="amount" value="{{ $amount ?? "" }}" class="form-control" id="amount-input">
                </div>
                <div class="form-group col-12">
                    <label for="reason-input">Reason</label>
                    <input name="reason" value="{{ $reason ?? "" }}" class="form-control" id="reason-input">
                </div>
                <div class="form-group col-12">
                    <label for="date-input">Date</label>
                    <input type="datetime-local" name="date" value="{{ $date ?? "" }}" class="form-control" id="date-input">
                </div>
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
