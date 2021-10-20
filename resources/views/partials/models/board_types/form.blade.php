<form action="{{ $action }}" method="post">
    @csrf
    <div class="form-group">
        <label for="board_type_name-input">Board Type Name</label>
        <input name="board_type_name" value="{{ $board_type_name ?? "" }}" class="form-control"
               id="board_type_name-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
