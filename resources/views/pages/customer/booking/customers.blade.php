@extends('pages.customer.booking.layout')

@php
    /**
     * @var \App\Models\Tour\Tour $tour
     * @var \App\Models\Booking\BookingTraveller|null $customer
     */
    $shouldRooming = $tour->templates->count();
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
                Having trouble with this form? You can find our contact details by clicking the <span class="icon-menu"></span> icon in the top left corner
            </div>
        </div>
        <div class="card">
            <div class="card-body row">
                @csrf
                <x-customer.input name="lead_title" value="{{ $customer?->title ?? '' }}" width="1" autocomplete="honorific-prefix" required>
                    Title
                </x-customer.input>

                <x-customer.input name="lead_first_name" value="{{ $customer?->first_name ?? '' }}" width="3" autocomplete="given-name" required>
                    First Name
                </x-customer.input>

                <x-customer.input name="lead_middle_names" value="{{ $customer?->middle_names ?? '' }}" width="4" autocomplete="additional-name">
                    Middle Names
                </x-customer.input>

                <x-customer.input name="lead_last_name" value="{{ $customer?->last_name ?? '' }}" width="4" autocomplete="family-name" required>
                    Last Name
                </x-customer.input>

                <x-customer.input type="date" name="lead_date_of_birth" value="{{ $customer?->date_of_birth?->format('Y-m-d') ?? '' }}" width="3" autocomplete="bday" required>
                    Date of Birth
                </x-customer.input>

                <x-customer.input name="lead_email_address" value="{{ $customer?->email_address }}" width="3" autocomplete="email" required>
                    Email Address
                </x-customer.input>

                <x-customer.input name="lead_email_address_confirmation" value="{{ $customer?->email_address }}" width="3" autocomplete="email" required>
                    Confirm your Email
                </x-customer.input>

                <x-customer.input name="lead_mobile_number" value="{{ $customer?->mobile_number ?? '' }}" width="3" autocomplete="tel" required>
                    Mobile Number
                </x-customer.input>
                @if($shouldRooming)
                <div class="form-group col-md-12">
                    Selecting the same room as another traveller indicates that the room will be shared by those individuals.
                    <br />
                    For example, two people sharing a twin/double room should select <span class="fw-bold">Ideal Room Type</span> followed by <span class="fw-bold">Room 1</span>
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
                    <label class="col-md-12 mb-0">Room</label>
                    <select name="lead_group" class="w-100">
                        @for($group = 1; $group < 31; $group++)
                            <option value="{{ $group }}">Room {{ $group }}</option>
                        @endfor
                    </select>
                </div>
                @endif
                <hr class="splitter">
                <div class="col-md-12">
                    <div class="form-group">
                        <h4 class="mb-0">Home Address</h4>
                    </div>
                    <hr class="splitter">

                    <x-customer.input name="lead_home_address_line_1" value="{{ $customer->homeAddress->address_line_1 ?? '' }}" autocomplete="address-line1" required>
                        Address Line 1
                    </x-customer.input>

                    <x-customer.input name="lead_home_address_line_2" value="{{ $customer->homeAddress->address_line_2 ?? '' }}" autocomplete="address-line2">
                        Address Line 2
                    </x-customer.input>
                    <x-customer.input name="lead_home_town" value="{{ $customer->homeAddress->town ?? '' }}" autocomplete="address-level2">
                        Town
                    </x-customer.input>
                    <x-customer.input name="lead_home_town" value="{{ $customer->homeAddress->region ?? '' }}" autocomplete="address-level1">
                        Region
                    </x-customer.input>

                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Home Country</label>
                        <div class="col-md-12">
                            <select name="lead_home_country" class="w-100" required>
                                <option value="" @if(!isset($customer?->homeAddress?->country_id)) selected @endif disabled>Please Select</option>
                                @foreach(\App\Models\Location\Country::orderBy('name', 'asc')->get() as $country)
                                    <option value="{{ $country->id }}" @if(isset($customer) && $customer?->homeAddress?->country_id == $country->id) selected @endif>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <x-customer.input name="lead_home_postcode" value="{{ $customer->homeAddress->postcode ?? '' }}" autocomplete="postcode">
                        Postcode
                    </x-customer.input>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <h4 class="mb-0">Billing Address</h4>
                    </div>
                    <hr class="splitter">

                    <x-customer.input name="lead_billing_address_line_1" value="{{ $customer->billingAddress->address_line_1 ?? '' }}" width="6" autocomplete="address-line1" required>
                        Address Line 1
                    </x-customer.input>

                    <x-customer.input name="lead_billing_address_line_2" value="{{ $customer->billingAddress->address_line_2 ?? '' }}" width="6" autocomplete="address-line2">
                        Address Line 2
                    </x-customer.input>

                    <x-customer.input name="lead_billing_town" value="{{ $customer->billingAddress->town ?? '' }}" width="6" autocomplete="address-level2">
                        Town
                    </x-customer.input>
                    <x-customer.input name="lead_billing_town" value="{{ $customer->billingAddress->region ?? '' }}" width="6" autocomplete="address-level1">
                        Region
                    </x-customer.input>

                    <div class="form-group col-md-6">
                        <label class="col-md-12 mb-0">Billing Country</label>
                        <div class="col-md-12">
                            <select name="lead_billing_country" class="w-100" required>
                                <option value="" @if(!isset($customer?->billingAddress?->country_id)) selected @endif disabled>Please Select</option>
                                @foreach(\App\Models\Location\Country::orderBy('name', 'asc')->get() as $country)
                                    <option value="{{ $country->id }}" @if(isset($customer) && $customer?->billingAddress?->country_id === $country->id) selected @endif>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <x-customer.input name="lead_billing_postcode" value="{{ $customer->billingAddress->postcode ?? '' }}" autocomplete="postcode">
                        Postcode
                    </x-customer.input>
                </div>
                <hr class="splitter">
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
                                    {{ $flight['details'] }} - {{ f_currency($flight['cost']) }}
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
                        <select name="inbound" class="w-100">
                            @foreach($flights['inbound'] as $flight)
                                <option value="{{ $flight['id'] }}" @if($flight['selected']) selected @endif>
                                    {{ $flight['details'] }} - {{ f_currency($flight['cost']) }}
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
                Please note that Traveller Email Addresses must be unique, please leave this blank if the email address is unknown. This will also mean that you will manage the travellers on this booking including payments and extras.
                <br />
                If you wish each Traveller to have the ability to manage their own booking and for their details not to be managed by the Lead Booker, then please enter a unique Email Address in order to create their own account on our Customer Portal. This can also be added post booking.
            </div>
        </div>
        @php $additionals = 0 @endphp
        @foreach($additionalTravellers ?? [] as $traveller)
            @if(!isset($traveller)) @continue @endif
            @include('partials.customer.booking.traveller', ['number' => $additionals, 'traveller' => $traveller, 'shouldRooming' => $shouldRooming])
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
        const customerSection = `@include('partials.customer.booking.traveller', ['number' => '%NUMBER%', 'traveller' => null, 'shouldRooming' => $shouldRooming])`;
        additional = {{ $additionals ?? 0 }};
        available = {{ $available - $additionals - 1}};

        $(document).ready(function () {
                $('.v-form-validation').submit(function (event) {
                    let emails = [];
                    let failed = false;
                    $('.v-email-validation-unique').each(function (index) {
                        let email = this.value.toLowerCase().trim();
                        if (!email || email.length === 0) return true;
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
            @if ($tour->stock_control_active)
                if (available <= 0) {
                    alert('There is not enough stock for more customers');
                    return;
                }
            @endif
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
