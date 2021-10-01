<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="transport_type_id-input">Transport Type Id</label>
        <input name="transport_type_id" value="{{ $transport_type_id ?? "" }}" class="form-control"
               id="transport_type_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="operator_id-input">Operator Id</label>
        <input name="operator_id" value="{{ $operator_id ?? "" }}" class="form-control" id="operator_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="departure_location_id-input">Departure Location Id</label>
        <input name="departure_location_id" value="{{ $departure_location_id ?? "" }}" class="form-control"
               id="departure_location_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="arrival_location_id-input">Arrival Location Id</label>
        <input name="arrival_location_id" value="{{ $arrival_location_id ?? "" }}" class="form-control"
               id="arrival_location_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="name-input">Name</label>
        <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="description-input">Description</label>
        <input name="description" value="{{ $description ?? "" }}" class="form-control" id="description-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="currency-input">Currency</label>
        <input name="currency" value="{{ $currency ?? "" }}" class="form-control" id="currency-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="is_domestic-input">Is Domestic</label>
        <input name="is_domestic" value="{{ $is_domestic ?? "" }}" class="form-control" id="is_domestic-input">
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
