<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="activity_type_id-input">Activity Type Id</label>
        <input name="activity_type_id" value="{{ $activity_type_id ?? "" }}" class="form-control"
               id="activity_type_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="location_id-input">Location Id</label>
        <input name="location_id" value="{{ $location_id ?? "" }}" class="form-control" id="location_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="title-input">Title</label>
        <input name="title" value="{{ $title ?? "" }}" class="form-control" id="title-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="description-input">Description</label>
        <input name="description" value="{{ $description ?? "" }}" class="form-control" id="description-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="notes-input">Notes</label>
        <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
