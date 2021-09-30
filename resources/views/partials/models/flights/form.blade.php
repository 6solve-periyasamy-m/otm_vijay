<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="airline_id-input">Airline Id</label>
    <input name="airline_id" value="{{ $airline_id ?? "" }}" class="form-control" id="airline_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="departure_airport_id-input">Departure Airport Id</label>
    <input name="departure_airport_id" value="{{ $departure_airport_id ?? "" }}" class="form-control" id="departure_airport_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="arrival_airport_id-input">Arrival Airport Id</label>
    <input name="arrival_airport_id" value="{{ $arrival_airport_id ?? "" }}" class="form-control" id="arrival_airport_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="is_domestic-input">Is Domestic</label>
    <input name="is_domestic" type="checkbox" value="{{ $is_domestic ?? "" }}" class="form-control" id="is_domestic-input">
  </div><p></p>
  <div id="form-group">
    <label for="notes-input">Notes</label>
    <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
  </div><p></p>
  <div id="form-group">
    <label for="available_after-input">Available After</label>
    <input name="available_after" type="date" value="{{ $available_after ?? "" }}" class="form-control" id="available_after-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
