<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="tour_id-input">Tour Id</label>
    <input name="tour_id" value="{{ $tour_id ?? "" }}" class="form-control" id="tour_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="flight_inventory_id-input">Flight Inventory Id</label>
    <input name="flight_inventory_id" value="{{ $flight_inventory_id ?? "" }}" class="form-control" id="flight_inventory_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="tour_component_type-input">Tour Component Type</label>
    <input name="tour_component_type" value="{{ $tour_component_type ?? "" }}" class="form-control" id="tour_component_type-input">
  </div><p></p>
  <div id="form-group">
    <label for="flight_type-input">Flight Type</label>
    <input name="flight_type" value="{{ $flight_type ?? "" }}" class="form-control" id="flight_type-input">
  </div><p></p>
  <div id="form-group">
    <label for="tour_sales_price-input">Tour Sales Price</label>
    <input name="tour_sales_price" value="{{ $tour_sales_price ?? "" }}" class="form-control" id="tour_sales_price-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
