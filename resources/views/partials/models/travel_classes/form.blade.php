<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group">
                    <label for="title-input">Name</label>
                    <input name="title" value="{{ $name ?? "" }}" class="form-control" id="title-input">
                </div>
                <p></p>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
