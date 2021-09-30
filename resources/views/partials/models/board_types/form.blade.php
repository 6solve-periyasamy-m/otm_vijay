<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="board_type_name-input">Board Type Name</label>
    <input name="board_type_name" value="{{ $board_type_name ?? "" }}" class="form-control" id="board_type_name-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
