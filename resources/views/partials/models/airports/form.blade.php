<form action="{{ $action }}" method="post">
    @csrf
    <div class="form-group">
        <label for="name-input">Name</label>
        <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="iata_code-input">Iata Code</label>
        <input name="iata_code" value="{{ $iata_code ?? "" }}" class="form-control" id="iata_code-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
