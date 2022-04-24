<div class="row customer-section">
    <hr class="splitter">
    <h2 class="col-md-12 mb-0">Additional Traveller Details
        @if(empty($customer))
            <div class="text-end d-inline-flex">
                <a class="btn btn-danger text-white" href="" onclick="event.preventDefault();$(this).parents('div.customer-section').remove();">
                    <i class="icon-trash"></i>
                    Remove Customer
                </a>
            </div>
        @endif
    </h2>
    <hr class="splitter">
    <input type="hidden" name="additional[{{ $number }}][id]" value="{{ $customer?->id ?? 0 }}">
    <div class="form-group col-md-1">
        <label class="col-md-12 mb-0">Title</label>
        <div class="col-md-12">
            <input type="text" name="additional[{{ $number }}][title]" id="additional[{{ $number }}][title]-input" value="{{ $customer?->title ?? '' }}"
                   class="form-control ps-0 form-control-line" autocomplete="honorific-prefix" required>
        </div>
    </div>
    <div class="form-group col-md-3">
        <label class="col-md-12 mb-0">First Name</label>
        <div class="col-md-12">
            <input type="text" name="additional[{{ $number }}][first_name]" id="additional[{{ $number }}][first_name]-input" value="{{ $customer?->first_name ?? '' }}"
                   class="form-control ps-0 form-control-line" autocomplete="given-name" required>
        </div>
    </div>
    <div class="form-group col-md-4">
        <label class="col-md-12 mb-0">Middle Names</label>
        <div class="col-md-12">
            <input type="text" name="additional[{{ $number }}][middle_names]" id="additional[{{ $number }}][middle_names]-input" value="{{ $customer?->middle_names ?? '' }}"
                   class="form-control ps-0 form-control-line" autocomplete="additional-name">
        </div>
    </div>
    <div class="form-group col-md-4">
        <label class="col-md-12 mb-0">Last Name</label>
        <div class="col-md-12">
            <input type="text" name="additional[{{ $number }}][last_name]" id="additional[{{ $number }}][last_name]-input" value="{{ $customer?->last_name ?? '' }}"
                   class="form-control ps-0 form-control-line" autocomplete="family-name" required>
        </div>
    </div>
    <div class="form-group col-md-2">
        <label class="col-md-12 mb-0">Date of Birth</label>
        <div class="col-md-12">
            <input type="date" name="additional[{{ $number }}][date_of_birth]" id="additional[{{ $number }}][date_of_birth]-input" value="{{ $customer?->date_of_birth?->format('Y-m-d') ?? '' }}"
                   class="form-control ps-0 form-control-line" autocomplete="bday" required>
        </div>
    </div>
    <div class="form-group col-md-8">
        <label class="col-md-12 mb-0">Email Address</label>
        <div class="col-md-12">
            <input type="text" name="additional[{{ $number }}][email_address]" id="additional[{{ $number }}][email_address]-input" value="{{ $customer?->email_address ?? '' }}"
                   class="form-control ps-0 form-control-line">
        </div>
    </div>
    <div class="form-group col-md-2">
        <label class="col-md-12 mb-0">Mobile Number</label>
        <div class="col-md-12">
            <input type="text" name="additional[{{ $number }}][mobile_number]" id="additional[{{ $number }}][mobile_number]-input" value="{{ $customer?->mobile_number ?? '' }}"
                   class="form-control ps-0 form-control-line" autocomplete="tel">
        </div>
    </div>
</div>
