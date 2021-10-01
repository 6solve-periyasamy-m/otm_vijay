<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="event_id-input">Event Id</label>
    <input name="event_id" value="{{ $event_id ?? "" }}" class="form-control" id="event_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="title-input">Title</label>
    <input name="title" value="{{ $title ?? "" }}" class="form-control" id="title-input">
  </div><p></p>
  <div id="form-group">
    <label for="description-input">Description</label>
    <input name="description" value="{{ $description ?? "" }}" class="form-control" id="description-input">
  </div><p></p>
  <div id="form-group">
    <label for="date_from-input">Date From</label>
    <input name="date_from" value="{{ $date_from ?? "" }}" class="form-control" id="date_from-input">
  </div><p></p>
  <div id="form-group">
    <label for="date_to-input">Date To</label>
    <input name="date_to" value="{{ $date_to ?? "" }}" class="form-control" id="date_to-input">
  </div><p></p>
  <div id="form-group">
    <label for="base_price_per_person-input">Base Price Per Person</label>
    <input name="base_price_per_person" value="{{ $base_price_per_person ?? "" }}" class="form-control" id="base_price_per_person-input">
  </div><p></p>
  <div id="form-group">
    <label for="margin-input">Margin</label>
    <input name="margin" value="{{ $margin ?? "" }}" class="form-control" id="margin-input">
  </div><p></p>
  <div id="form-group">
    <label for="single_occupancy_surcharge-input">Single Occupancy Surcharge</label>
    <input name="single_occupancy_surcharge" value="{{ $single_occupancy_surcharge ?? "" }}" class="form-control" id="single_occupancy_surcharge-input">
  </div><p></p>
  <div id="form-group">
    <label for="stock_control_active-input">Stock Control Active</label>
    <input name="stock_control_active" value="{{ $stock_control_active ?? "" }}" class="form-control" id="stock_control_active-input">
  </div><p></p>
  <div id="form-group">
    <label for="stock-input">Stock</label>
    <input name="stock" value="{{ $stock ?? "" }}" class="form-control" id="stock-input">
  </div><p></p>
  <div id="form-group">
    <label for="booking_form_url-input">Booking Form Url</label>
    <input name="booking_form_url" value="{{ $booking_form_url ?? "" }}" class="form-control" id="booking_form_url-input">
  </div><p></p>
  <div id="form-group">
    <label for="tour_colour_id-input">Tour Colour Id</label>
    <input name="tour_colour_id" value="{{ $tour_colour_id ?? "" }}" class="form-control" id="tour_colour_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="is_active-input">Is Active</label>
    <input name="is_active" value="{{ $is_active ?? "" }}" class="form-control" id="is_active-input">
  </div><p></p>
  <div id="form-group">
    <label for="notes-input">Notes</label>
    <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
