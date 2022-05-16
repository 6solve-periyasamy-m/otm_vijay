@extends('pages.customer.booking.layout')

@php
    /**
     * @var \App\Models\Tour $tour
     * @var \App\Models\Customer|null $customer
     */
@endphp

@section('title', 'Booking for ' . $tour->name)

@section('booking-body')
    <form class="form-horizontal form-material mx-2 row v-form-validation" method="post"
          action="{{ route('customer-booking.store-customers', ['bookingUrl' => $tour->booking_form_url, 'token' => $token,]) }}">
        <div class="card">
            <div class="card-body">
                @if(isset($customer) && !isset($token))
                    <script>alert('Since you are logged into the dashboard, we have filled your details for you :)');</script>
                @endif
                <h2 class="col-md-12 mb-0">Lead Booker Details</h2>
            </div>
        </div>
        <div class="card">
            <div class="card-body row">
                @csrf
                <div class="form-group col-md-1">
                    <label class="col-md-12 mb-0">Title</label>
                    <div class="col-md-12">
                        <input type="text" name="lead_title" id="lead_title-input" value="{{ $customer?->title ?? '' }}"
                               class="form-control ps-0 form-control-line" autocomplete="honorific-prefix" required>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label class="col-md-12 mb-0">First Name</label>
                    <div class="col-md-12">
                        <input type="text" name="lead_first_name" id="lead_first_name-input"
                               value="{{ $customer?->first_name ?? '' }}"
                               class="form-control ps-0 form-control-line" autocomplete="given-name" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="col-md-12 mb-0">Middle Names</label>
                    <div class="col-md-12">
                        <input type="text" name="lead_middle_names" id="lead_middle_names-input"
                               value="{{ $customer?->middle_names ?? '' }}"
                               class="form-control ps-0 form-control-line" autocomplete="additional-name">
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="col-md-12 mb-0">Last Name</label>
                    <div class="col-md-12">
                        <input type="text" name="lead_last_name" id="lead_last_name-input"
                               value="{{ $customer?->last_name ?? '' }}"
                               class="form-control ps-0 form-control-line" autocomplete="family-name" required>
                    </div>
                </div>
                <div class="form-group col-md-2">
                    <label class="col-md-12 mb-0">Date of Birth</label>
                    <div class="col-md-12">
                        <input type="date" name="lead_date_of_birth" id="lead_date_of_birth-input"
                               value="{{ $customer?->date_of_birth?->format('Y-m-d') ?? '' }}"
                               class="form-control ps-0 form-control-line" autocomplete="bday" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="col-md-12 mb-0">Email Address</label>
                    <div class="col-md-12">
                        <input type="text" name="lead_email_address" id="lead_email_address-input"
                               value="{{ $customer?->email_address ?? '' }}"
                               class="form-control ps-0 form-control-line v-email-validation-unique" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label class="col-md-12 mb-0">Confirm Your Email</label>
                    <div class="col-md-12">
                        <input type="text" name="lead_email_address_confirmation"
                               id="lead_email_address_confirmation-input" value="{{ $customer?->email_address ?? '' }}"
                               class="form-control ps-0 form-control-line" required>
                    </div>
                </div>
                <div class="form-group col-md-2">
                    <label class="col-md-12 mb-0">Mobile Number</label>
                    <div class="col-md-12">
                        <input type="text" name="lead_mobile_number" id="lead_mobile_number-input"
                               value="{{ $customer?->mobile_number ?? '' }}"
                               class="form-control ps-0 form-control-line" autocomplete="tel" required>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    Sharing is designated by the selection of a room, selecting the same room as another traveller indicates that the room will be shared by those travellers
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12 mb-0">Ideal Room Type</label>
                    <select name="lead_room_type" class="w-100">
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" @if(isset($leadTraveller) && $leadTraveller?->room_type?->id == $room->id) selected @endif>
                                {{ $room }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12 mb-0">Room Sharing Group</label>
                    <select name="lead_group" class="w-100">
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" @if(isset($leadTraveller) && $leadTraveller?->group?->id == $group->id) selected @endif>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6">
                    <hr class="splitter">
                    <div class="form-group col-md-12">
                        <h5 class="col-md-12 mb-0">Home Address</h5>
                    </div>
                    <hr class="splitter">
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Home Address Line 1</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_home_address_line_1" id="lead_home_address_line_1-input"
                                   value="{{ $customer?->homeAddress?->address_line_1 ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-line1" required>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Home Address Line 2</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_home_address_line_2" id="lead_home_address_line_2-input"
                                   value="{{ $customer?->homeAddress?->address_line_2 ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-line2">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Home Town</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_home_town" id="lead_home_town-input"
                                   value="{{ $customer?->homeAddress?->town ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-level2">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Home Region</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_region" id="lead_region-input"
                                   value="{{ $customer?->homeAddress?->region ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-level1">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Home Country</label>
                        <div class="col-md-12">
                            <select name="lead_home_country" class="w-100">
                                @foreach(\App\Models\Country::orderBy('name', 'asc')->get() as $country)
                                    <option value="{{ $country->id }}" @if(isset($leadTraveller) && $leadTraveller?->homeAddress?->country_id == $country->id) selected @endif>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Home Postcode</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_home_postcode" id="lead_home_postcode-input"
                                   value="{{ $customer?->homeAddress?->postcode ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="postcode" required>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <hr class="splitter">
                    <div class="form-group col-md-12">
                        <h5 class="col-md-12 mb-0">Billing Address</h5>
                    </div>
                    <hr class="splitter">
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Billing Address Line 1</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_billing_address_line_1" id="lead_billing_address_line_1-input"
                                   value="{{ $customer?->billingAddress?->address_line_1 ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-line1" required>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Billing Address Line 2</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_billing_address_line_2" id="lead_billing_address_line_2-input"
                                   value="{{ $customer?->billingAddress?->address_line_2 ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-line2">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Billing Town</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_billing_town" id="lead_billing_town-input"
                                   value="{{ $customer?->billingAddress?->town ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-level2">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Billing Region</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_billing_region" id="lead_region-input"
                                   value="{{ $customer?->billingAddress?->region ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-level1">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Billing Country</label>
                        <div class="col-md-12">
                            <select name="lead_billing_country" class="w-100">
                                @foreach(\App\Models\Country::orderBy('name', 'asc')->get() as $country)
                                    <option value="{{ $country->id }}" @if(isset($leadTraveller) && $leadTraveller?->billingAddress?->country_id == $country->id) selected @endif>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Billing Postcode</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_billing_postcode" id="lead_billing_postcode-input"
                                   value="{{ $customer?->billingAddress?->postcode ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="postcode" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Flight Selection --}}
        @if(!empty($flights['outbound']) || !empty($flights['inbound']))
        <div class="card">
            <div class="card-body">
                <h2 class="col-md-12 mb-0">Flight Selection</h2>
            </div>
        </div>
        <div class="card">
            <div class="card-body row">
                <div class="row col-6">
                    <hr class="splitter">
                    <div class="form-group col-md-12">
                        <h5 class="col-md-12 mb-0">Outbound Flight</h5>
                    </div>
                    <hr class="splitter">
                    <div class="col-12">
                        <select name="outbound" class="w-100">
                            @foreach($flights['outbound'] as $flight)
                                <option value="{{ $flight['id'] }}" @if($flight['selected']) selected @endif>
                                    {{ $flight['details'] }} - {{ StringFormatter::formatCurrency($flight['cost']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row col-6">
                    <hr class="splitter">
                    <div class="form-group col-md-12">
                        <h5 class="col-md-12 mb-0">Inbound Flight</h5>
                    </div>
                    <hr class="splitter">
                    <div class="col-12">
                        <select name="inbound" class="w-100" required>
                            @foreach($flights['inbound'] as $flight)
                                <option value="{{ $flight['id'] }}" @if($flight['selected']) selected @endif>
                                    {{ $flight['details'] }} - {{ StringFormatter::formatCurrency($flight['cost']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        @endif
        {{-- Additional Traveller Details --}}
        <div class="card">
            <div class="card-body">
                <h2 class="col-md-12 mb-0">Additional Traveller Details
                    <a class="btn btn-primary float-end" onclick="event.preventDefault();addCustomer();">
                        Add Customer
                    </a>
                </h2>
                <br />
                Please note that Traveller Email Addresses must be unique, if an Additional Traveller does not have an email address this must be left blank as they cannot share an Email Address with another Customer.
                <br />
                If you wish your each Traveller to manage their own booking and for their details not to be managed by the Lead Booker, then they will need a unique Email Address in order to create their own account on our Customer Portal.
            </div>
        </div>
        @php $additionals = 0 @endphp
        @foreach($additionalTravellers as $traveller)
            @if(!isset($traveller)) @continue @endif
            @include('partials.customer.booking.traveller', ['number' => $additionals, 'traveller' => $traveller,])
            @php $additionals++ @endphp
        @endforeach
        <div class="card customer-before">
            <div class="card-body">
                <input class="btn btn-success text-white float-end" type="submit" value="Continue">
            </div>
        </div>
    </form>
@endsection

@section('footer-script')
    <script type="text/javascript">
        const customerSection = `@include('partials.customer.booking.traveller', ['number' => '%NUMBER%', 'traveller' => null,])`;
        additional = {{ $additionals ?? 0 }};
        available = {{ $available - $additionals - 1}};

        $(document).ready(function () {
                $('.v-form-validation').submit(function (event) {
                    let emails = [];
                    let failed = false;
                    $('.v-email-validation-unique').each(function (index) {
                        let email = this.value.toLowerCase().trim();
                        if (emails.includes(email)) {
                            failed = true;
                            alert('You have used the email ' + this.value.trim() + ' for multiple customers. Please correct this.')
                            return false;
                        }
                        emails.push(email);
                    });
                    if (failed) {
                        event.preventDefault();
                    }
                });
            }
        );

        function addCustomer() {
            if (available <= 0) {
                alert('There is not enough stock for more customers');
                return;
            }
            $('.customer-before').before(customerSection.replaceAll('%NUMBER%', additional));
            additional++;
            available--;
        }

        function removeCustomer(btn) {
            let div = $(btn).parents('div.customer-section');
            let customerId = parseInt(div.attr('customer'));
            if (customerId === 0) {
                div.remove();
            }
            @if(isset($token))
                else {
                    $.post('{{ route('api.booking.remove-customer', ['token' => $token]) }}',
                        {
                            '_token': '{{ csrf_token() }}',
                            'customer': customerId,
                        })
                        .done(function (xhr, textStatus, errorThrown) {
                            if (xhr.success) {
                                div.remove();
                            } else {
                                console.log(xhr);
                                alert(xhr.message);
                            }
                        })
                        .fail(function (xhr, textStatus, errorThrown) {
                            alert(xhr.responseText);
                            location.reload();
                        });
                }
            @endif
            additional--;
            available++;
        }
    </script>
@endsection
