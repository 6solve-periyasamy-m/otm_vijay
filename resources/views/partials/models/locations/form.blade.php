<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="region_id-input">Region Id</label>
        <input name="region_id" value="{{ $region_id ?? "" }}" class="form-control" id="region_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="location_type_id-input">Location Type Id</label>
        <input name="location_type_id" value="{{ $location_type_id ?? "" }}" class="form-control"
               id="location_type_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="name-input">Name</label>
        <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="address-input">Address</label>
        <input name="address" value="{{ $address ?? "" }}" class="form-control" id="address-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
