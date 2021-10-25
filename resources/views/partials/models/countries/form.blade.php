<form action="{{ $action }}" method="post">
    @csrf
    <div class="form-group">
        <label for="name-input">Name</label>
        <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="code-input">Code</label>
        <input name="code" value="{{ $code ?? "" }}" class="form-control" id="code-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="currency-input">Currency</label>
        <input name="currency" value="{{ $currency ?? "" }}" class="form-control" id="currency-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
