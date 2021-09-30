<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="order_id-input">Order Id</label>
    <input name="order_id" value="{{ $order_id ?? "" }}" class="form-control" id="order_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="customer_id-input">Customer Id</label>
    <input name="customer_id" value="{{ $customer_id ?? "" }}" class="form-control" id="customer_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="tour_cost-input">Tour Cost</label>
    <input name="tour_cost" value="{{ $tour_cost ?? "" }}" class="form-control" id="tour_cost-input">
  </div><p></p>
  <div id="form-group">
    <label for="single_occupancy_surcharge-input">Single Occupancy Surcharge</label>
    <input name="single_occupancy_surcharge" value="{{ $single_occupancy_surcharge ?? "" }}" class="form-control" id="single_occupancy_surcharge-input">
  </div><p></p>
  <div id="form-group">
    <label for="travel_insurer-input">Travel Insurer</label>
    <input name="travel_insurer" value="{{ $travel_insurer ?? "" }}" class="form-control" id="travel_insurer-input">
  </div><p></p>
  <div id="form-group">
    <label for="policy_number-input">Policy Number</label>
    <input name="policy_number" value="{{ $policy_number ?? "" }}" class="form-control" id="policy_number-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
