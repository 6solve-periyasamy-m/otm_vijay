@extends('pages.customer.booking.layout')

@php
    /**
     * @var \App\Models\Tour $tour
     * @var \App\Models\Customer|null $customer
     */
@endphp

@section('title', 'Booking for ' . $tour->name)

@section('booking-body')
    <form class="form-horizontal form-material mx-2 row" method="post"
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
                               class="form-control ps-0 form-control-line" required>
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
                <div class="form-group col-md-6">
                    <label class="col-md-12 mb-0">Room Type</label>
                    <select name="lead_room_type" class="w-100">
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">
                                {{ $room }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12 mb-0">Group</label>
                    <select name="lead_group" class="w-100">
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">
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
                        <label class="col-md-12 mb-0">Address Line 1</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_home_address_line_1" id="lead_home_address_line_1-input"
                                   value="{{ $customer->homeAddress->address_line_1 ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-line1" required>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Address Line 2</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_home_address_line_2" id="lead_home_address_line_2-input"
                                   value="{{ $customer->homeAddress->address_line_2 ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-line2">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Town</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_home_town" id="lead_home_town-input"
                                   value="{{ $customer->homeAddress->town ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-level2">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Region</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_region" id="lead_region-input"
                                   value="{{ $customer->homeAddress->region ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-level1">
                        </div>
                    </div>
                    @include('partials.fields.selector.default',
                                    ['name' => 'Country', 'field' => 'lead_home_country',
                                     'value' => $customer->homeAddress->country_id ?? null, 'route' => 'countries',])
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Postcode</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_home_postcode" id="lead_home_postcode-input"
                                   value="{{ $customer->homeAddress->postcode ?? '' }}"
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
                        <label class="col-md-12 mb-0">Address Line 1</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_billing_address_line_1" id="lead_billing_address_line_1-input"
                                   value="{{ $customer->billingAddress->address_line_1 ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-line1" required>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Address Line 2</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_billing_address_line_2" id="lead_billing_address_line_2-input"
                                   value="{{ $customer->billingAddress->address_line_2 ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-line2">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Town</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_billing_town" id="lead_billing_town-input"
                                   value="{{ $customer->billingAddress->town ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-level2">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Region</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_region" id="lead_region-input"
                                   value="{{ $customer->billingAddress->region ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="address-level1">
                        </div>
                    </div>
                    @include('partials.fields.selector.default',
                                ['name' => 'Country', 'field' => 'lead_billing_country',
                                 'value' => $customer->billingAddress->country_id ?? null, 'route' => 'countries',])
                    <div class="form-group col-md-12">
                        <label class="col-md-12 mb-0">Postcode</label>
                        <div class="col-md-12">
                            <input type="text" name="lead_billing_postcode" id="lead_billing_postcode-input"
                                   value="{{ $customer->billingAddress->postcode ?? '' }}"
                                   class="form-control ps-0 form-control-line" autocomplete="postcode" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Flight Selection --}}
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
        {{-- Additional Traveller Details --}}
        <div class="card">
            <div class="card-body">
                <h2 class="col-md-12 mb-0">Additional Traveller Details
                    <a class="btn btn-primary float-end" onclick="event.preventDefault();addCustomer();">
                        Add Customer
                    </a>
                </h2>
            </div>
        </div>
        @php $additionals = 0 @endphp
        @foreach($additionalTravellers as $traveller)
            @if(!isset($traveller)) @continue @endif
            @include('partials.customer.booking.traveller', ['number' => $additionals, 'customer' => $traveller,])
            @php $additionals++ @endphp
        @endforeach
        <div class="card customer-before">
            <div class="card-body">
                <input class="btn btn-success text-white float-end" type="submit" value="Confirm Lead Traveller">
            </div>
        </div>
    </form>
@endsection

@section('footer-script')
    <script type="text/javascript">
        const customerSection = `@include('partials.customer.booking.traveller', ['number' => '%NUMBER%', 'customer' => null,])`;
        additional = {{ $additionals ?? 0 }};

        function addCustomer() {
            $('.customer-before').before(customerSection.replaceAll('%NUMBER%', additional));
            additional++;
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
        }
    </script>
@endsection
