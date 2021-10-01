<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="region_id-input">Region Id</label>
    <input name="region_id" value="{{ $region_id ?? "" }}" class="form-control" id="region_id-input">
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
    <label for="audit_date-input">Audit Date</label>
    <input name="audit_date" value="{{ $audit_date ?? "" }}" class="form-control" id="audit_date-input">
  </div><p></p>
  <div id="form-group">
    <label for="address-input">Address</label>
    <input name="address" value="{{ $address ?? "" }}" class="form-control" id="address-input">
  </div><p></p>
  <div id="form-group">
    <label for="currency-input">Currency</label>
    <input name="currency" value="{{ $currency ?? "" }}" class="form-control" id="currency-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
