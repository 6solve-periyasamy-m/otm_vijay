<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="address_line_1-input">Address Line 1</label>
        <input name="address_line_1" value="{{ $address_line_1 ?? "" }}" class="form-control" id="address_line_1-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="address_line_2-input">Address Line 2</label>
        <input name="address_line_2" value="{{ $address_line_2 ?? "" }}" class="form-control" id="address_line_2-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="town-input">Town</label>
        <input name="town" value="{{ $town ?? "" }}" class="form-control" id="town-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="region-input">Region</label>
        <input name="region" value="{{ $region ?? "" }}" class="form-control" id="region-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="country-input">Country</label>
        <input name="country" value="{{ $country ?? "" }}" class="form-control" id="country-input">
    </div>
    <p></p>
    <div id="form-group">
        <label for="postcode-input">Postcode</label>
        <input name="postcode" value="{{ $postcode ?? "" }}" class="form-control" id="postcode-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
