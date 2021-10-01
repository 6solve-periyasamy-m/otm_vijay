<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="event_title-input">Event Title</label>
    <input name="event_title" value="{{ $event_title ?? "" }}" class="form-control" id="event_title-input">
  </div><p></p>
  <div id="form-group">
    <label for="event_description-input">Event Description</label>
    <input name="event_description" value="{{ $event_description ?? "" }}" class="form-control" id="event_description-input">
  </div><p></p>
  <div id="form-group">
    <label for="event_start_date-input">Event Start Date</label>
    <input name="event_start_date" value="{{ $event_start_date ?? "" }}" class="form-control" id="event_start_date-input">
  </div><p></p>
  <div id="form-group">
    <label for="event_end_date-input">Event End Date</label>
    <input name="event_end_date" value="{{ $event_end_date ?? "" }}" class="form-control" id="event_end_date-input">
  </div><p></p>
  <div id="form-group">
    <label for="booking_url-input">Booking Url</label>
    <input name="booking_url" value="{{ $booking_url ?? "" }}" class="form-control" id="booking_url-input">
  </div><p></p>
  <div id="form-group">
    <label for="notes-input">Notes</label>
    <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
