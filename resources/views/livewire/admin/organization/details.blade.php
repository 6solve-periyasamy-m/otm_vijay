<div class="otm-callout">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-12 row">
            <div class="row">
                <div class="col-12">
                    <p>Organization Name</p>
                    <h6 class="fw-bold">{{ $organization->name }}</h6>
                </div>
                <div class="col-6">
                    <p>Email Address</p>
                    <h6 class="fw-bold">
                        @if(isset($organization->contact_email))
                            <a href="mailto:{{ $organization->contact_email }}">{{ $organization->contact_email }}</a>
                        @else
                            Email Address Not Set
                        @endif
                    </h6>
                </div>
                <div class="col-6">
                    <p>Phone Number</p>
                    <h6 class="fw-bold">
                        <a href="tel:{{ $organization->contact_number }}">{{ $organization->contact_number }}</a>
                    </h6>
                </div>
                <div class="col-6">
                    <p>Internal Notes</p>
                    <h6 class="fw-bold">{{ $organization->internal_notes }}</h6>
                </div>
                <div class="col-6">
                    <p>External Notes</p>
                    <h6 class="fw-bold">{{ $organization->external_notes }}</h6>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <h6 class="fw-bold">Delivery Address</h6>
            {{ $organization->deliveryAddress->address_line_1 }}<br />
            {{ $organization->deliveryAddress->address_line_2 }}<br />
            {{ $organization->deliveryAddress->town }}<br />
            {{ $organization->deliveryAddress->region }}<br />
            {{ $organization->deliveryAddress->country?->name }}<br />
            {{ $organization->deliveryAddress->postcode }}<br />
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <h6 class="fw-bold">Billing Address</h6>
            {{ $organization->billingAddress->address_line_1 }}<br />
            {{ $organization->billingAddress->address_line_2 }}<br />
            {{ $organization->billingAddress->town }}<br />
            {{ $organization->billingAddress->region }}<br />
            {{ $organization->billingAddress->country?->name }}<br />
            {{ $organization->billingAddress->postcode }}<br />
        </div>
        <div class="col-12">
            <button onclick="openModal('admin.organization.form', {'organization': {{$organization->id}},})" class="btn btn-success">
                {{ Icon::edit() }}
                Edit Organization
            </button>
        </div>
    </div>
</div>
