<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="name-input">Name</label>
                    <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
                </div>
                <p></p>
                <div class="form-group col-12">
                    <label for="notes-input">Notes</label>
                    <textarea name="notes" class="form-control" id="notes-input">{{ $notes ?? "" }}</textarea>                    
                </div>
                <p></p>
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>