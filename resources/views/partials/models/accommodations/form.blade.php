<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="name-input">Name</label>
                    <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
                </div>                
                <div class="form-group col-12">
                    <label for="description-input">Description</label>
                    <input name="description" value="{{ $description ?? "" }}" class="form-control" id="description-input">
                </div>                
                <div class="form-group col-12">
                    <label for="audit_date-input">Audit Date</label>
                    <input type="date" name="audit_date" value="{{ $audit_date ?? "" }}" class="form-control" id="audit_date-input">
                </div>                
                @include('partials.models.addresses.switcher', [
                    'location_type_id' => isset($address) ? $address->location_type_id : 0,
                    'address_line_1' => isset($address) ? $address->address_line_1 : "",
                    'address_line_2' => isset($address) ? $address->address_line_2 : "",
                    'town' => isset($address) ? $address->town : "",
                    'region' => isset($address) ? $address->region : "",
                    'country_id' => isset($address) ? $address->country_id : 0,
                    'postcode' => isset($address) ? $address->postcode : "",
                ])
                @include('partials.models.currencies.selector', ['id' => $currency_id ?? 0, ])
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
