<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="quote_id-input">Quote Id</label>
    <input name="quote_id" value="{{ $quote_id ?? "" }}" class="form-control" id="quote_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="tour_id-input">Tour Id</label>
    <input name="tour_id" value="{{ $tour_id ?? "" }}" class="form-control" id="tour_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="lead_booker_id-input">Lead Booker Id</label>
    <input name="lead_booker_id" value="{{ $lead_booker_id ?? "" }}" class="form-control" id="lead_booker_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="token-input">Token</label>
    <input name="token" value="{{ $token ?? "" }}" class="form-control" id="token-input">
  </div><p></p>
  <div id="form-group">
    <label for="booking_reference-input">Booking Reference</label>
    <input name="booking_reference" value="{{ $booking_reference ?? "" }}" class="form-control" id="booking_reference-input">
  </div><p></p>
  <div id="form-group">
    <label for="ordered_on-input">Ordered On</label>
    <input name="ordered_on" value="{{ $ordered_on ?? "" }}" class="form-control" id="ordered_on-input">
  </div><p></p>
  <div id="form-group">
    <label for="internal_notes-input">Internal Notes</label>
    <input name="internal_notes" value="{{ $internal_notes ?? "" }}" class="form-control" id="internal_notes-input">
  </div><p></p>
  <div id="form-group">
    <label for="external_notes-input">External Notes</label>
    <input name="external_notes" value="{{ $external_notes ?? "" }}" class="form-control" id="external_notes-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
