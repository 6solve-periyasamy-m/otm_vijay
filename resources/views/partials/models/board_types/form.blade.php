<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="board_type_name-input">Board Type Name</label>
                    <input name="board_type_name" value="{{ $board_type_name ?? "" }}" class="form-control"
                        id="board_type_name-input">
                </div>
                <p></p>
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
