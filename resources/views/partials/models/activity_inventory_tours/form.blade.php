<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="tour_id-input">Tour Id</label>
        <input name="tour_id" value="{{ $tour_id ?? "" }}" class="form-control" id="tour_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="activity_inventory_id-input">Activity Inventory Id</label>
        <input name="activity_inventory_id" value="{{ $activity_inventory_id ?? "" }}" class="form-control"
               id="activity_inventory_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="tour_component_type-input">Tour Component Type</label>
        <input name="tour_component_type" value="{{ $tour_component_type ?? "" }}" class="form-control"
               id="tour_component_type-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="tour_sales_price-input">Tour Sales Price</label>
        <input name="tour_sales_price" value="{{ $tour_sales_price ?? "" }}" class="form-control"
               id="tour_sales_price-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
