<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="name-input">Name</label>
        <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
