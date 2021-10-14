<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="tour_id-input">Tour Id</label>
        <input name="tour_id" value="{{ $tour_id ?? "" }}" class="form-control" id="tour_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="transport_inventory_id-input">Transport Inventory Id</label>
        <input name="transport_inventory_id" value="{{ $transport_inventory_id ?? "" }}" class="form-control"
               id="transport_inventory_id-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="tour_component_type-input">Tour Component Type</label>
        <select class="form-select accommodation-component-type-select" name="tour_component_type" id="tour_component_type-input">
            <option value="Included" selected>Included</option>
            <option value="Upgrade">Upgrade</option>
            <option value="Add-on">Add-on</option>
        </select>
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
