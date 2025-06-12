@extends('layout.customer')

@section('title', 'View Itinerary')

@php
$orderNotesLock = $order->tour->repository->isOrderNotesLocked();
$accommodationLock = $order->tour->repository->isAccommodationLocked();
$activityLock = $order->tour->repository->isActivityLocked();
$flightLock = $order->tour->repository->isFlightLocked();
$transportLock = $order->tour->repository->isTransportLocked();

$currentURL = $_SERVER['REQUEST_URI'];
$basePattern = '/customer/itinerary/';
if (strpos($currentURL, $basePattern) !== false && strlen(str_replace($basePattern, '', $currentURL)) > 0) {
    $dynamic = true;
} else {
    $dynamic = false;
}
@endphp

@push('footer-stack')
    <script>
        const route = "{{ route('customer.itinerary') }}"

        function onOrderChange(selector) {
            window.location = route + '/' + $(selector).val();
        }
    </script>
@endpush

@section('content')
    {{-- <div class="row payment-balance">
        <div class="col-12">
            <form class="form-horizontal mx-2">
                <div class="form-group order-select-wrapper">
                    <p class="mb-0  heading">Select Order</p>
                    <select class="form-select order-select" onchange="onOrderChange(this);" id="booking_reference">
                        @foreach($orders as $selector)
                            <option value='{{ $selector->booking_reference }}' @if($selector->id == $order->id) selected
                                    @endif @if($selector->cancelled) disabled @endif>{{ $selector->tour->name }} ({{ $selector->booking_reference }} @if($selector->cancelled)
                                    (Cancelled)
                                @endif &#41;</option>
                        @endforeach
                    </select>
                    @if($order !== null && $order->booking_reference !== null)
                    <a href="{{ route('customer.invoice', ['reference' => $order->booking_reference]) }}"
                       target="_blank" class=" invoice btn btn-primary">Invoice</a>
                    @endif
                    @if ($order->has_atol)
                        <a href="{{ route('customer.atol', ['reference' => $order->booking_reference]) }}"
                           target="_blank" class=" atol btn btn-secondary">ATOL Certificate</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="container">
        <div class="row">
            @if(sizeof($editable ?? []) > 1)
                <div class="col-sm-12 col-md-3">
                    <div class="card other-profile col-md-12 col-xs-2" onclick="window.location = '{{ route('customer.itinerary', ['reference' => $order->booking_reference, 'customer' => $orderCustomer->customer,]) }}'">
                        <div class="card-body profile-card">
                            <center class="mt-4">
                                <h4 class="card-title mt-2 additional-customer-title">{{ $orderCustomer->customer->first_name }} {{ $orderCustomer->customer->last_name }}</h4>
                                <h6 class="card-subtitle additional-customer-subtitle">{{ $orderCustomer->customer?->email_address ?? "No Email Set" }}</h6>
                            </center>
                        </div>
                    </div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Customers
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
            @foreach($editable as $editableOrderCustomer)
                @if ($editableOrderCustomer->id === $orderCustomer->id) @continue @endif
                                            <div class="card other-profile col-md-12 col-xs-2"
                     onclick="window.location = '{{ route('customer.itinerary', ['reference' => $order->booking_reference, 'customer' => $editableOrderCustomer->customer,]) }}'">
                    <div class="card-body profile-card">
                        <center class="mt-4">
                            <h4 class="card-title mt-2additional-customer-title">{{ $editableOrderCustomer->customer->first_name }} {{ $editableOrderCustomer->customer->last_name }}</h4>
                            <h6 class="card-subtitleadditional-customer-subtitle">{{ $editableOrderCustomer->customer?->email_address ?? "No Email Set" }}</h6>
                        </center>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-sm-12 {{ sizeof($editable ?? []) > 1 ? 'col-md-9' : 'col-md-12' }}">
                <div class="card">
                    <div class="card-body">
                        <p class="heading d-inline">Your Itinerary for {{ $order->tour->name }} ({{ $order->booking_reference }})</p>

                        <a href="{{ route('customer.itinerary.download', ['reference' => $order->booking_reference, 'customer' => $orderCustomer->customer_id]) }}"
                           target="_blank" class="float-end invoice btn btn-primary">Download Itinerary</a>
                    </div>
                </div>
                @foreach($orderCustomer->repository->getComponentsForItinerary() as $day => $components)
                    <div class="mx-2">
                        <x-customer.accordion id="day-{{$day}}" nobg nocontainer>
                            <x-slot:header class="card-body">
                                <h2 class="mb-0" style="width: 100%; text-align: center;">{{ \Carbon\Carbon::createFromTimestamp($day)->format('l jS F Y') }}</h2>
                            </x-slot:header>
                            @foreach($components as $component)
                                @include('partials.customer.itinerary', ['orderComponent' => $component,])
                            @endforeach
                        </x-customer.accordion>
                    </div>
                @endforeach
                <form action="{{ route('customer.notes.update', ['reference' => $order->booking_reference, 'orderCustomer' => $orderCustomer,]) }}" method="post" class="form-horizontal form-material">
                    @csrf
                    @php $isLead = $order->repository->isLeadBooker(\App\Repository\Authentication\CustomerAuthenticationRepository::getCustomer()) @endphp
                    <div class="card">
                        <div class="card-body">
                            <h2 class="col-md-12 mb-0">Travel Insurance</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body row">
                            <x-customer.input name="travel_insurer" value="{{ $orderCustomer->travel_insurer }}" width="6">
                                Travel Insurer
                            </x-customer.input>
                            <x-customer.input name="policy_number" value="{{ $orderCustomer->policy_number }}" width="6">
                                Policy Number
                            </x-customer.input>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="col-md-12 mb-0">Notes About Your Tour</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body row">
                            @if($orderNotesLock || $accommodationLock || $activityLock || $flightLock || $transportLock)
                                <span class="fw-bold col-xl-12">
                                    Some or all of the below sections may be locked due to the tour starting soon. Changes made may not be reflected, therefore if any urgent changes are required, please contact us.
                                </span>
                            @endif
                            @if($isLead)
                            <x-customer.input.text-area disabled="{{$orderNotesLock}}" name="order_notes" value="{{ $order->external_notes }}" width="6">
                                Order Notes
                            </x-customer.input.text-area>
                            @endif
                            <x-customer.input.text-area disabled="{{$orderNotesLock}}" name="order_customer_notes" value="{{ $orderCustomer->external_notes }}" width="{{ $isLead ? 6 : 12 }}">
                                Customer Specific Order Notes
                            </x-customer.input.text-area>
                            <x-customer.input.text-area disabled="{{$accommodationLock}}" name="accommodation_notes" value="{{ $orderCustomer->accommodation_notes }}" width="3">
                                Accommodation Notes
                            </x-customer.input.text-area>
                            <x-customer.input.text-area disabled="{{$activityLock}}" name="activity_notes" value="{{ $orderCustomer->activity_notes }}" width="3">
                                Activity Notes
                            </x-customer.input.text-area>
                            <x-customer.input.text-area disabled="{{$flightLock}}" name="flight_notes" value="{{ $orderCustomer->flight_notes }}" width="3">
                                Flight Notes
                            </x-customer.input.text-area>
                            <x-customer.input.text-area disabled="{{$transportLock}}" name="transport_notes" value="{{ $orderCustomer->transport_notes }}" width="3">
                                Transport Notes
                            </x-customer.input.text-area>
                            @include('partials.fields.submit')
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div></div>
</div> --}}
@if ($dynamic)
<div class="inner_content">
    <div class="overview_top_bar">
        <p class="overview_title"><span><a href="{{ route('customer.itinerary') }}"><img src="/images/customer/images/arrow-left.svg" alt="arrow left"></a></span>Royal Ascot African Ladies 2024  </p>
        <div class="search_field"><p><input type="text" placeholder="SEARCH"></p></div>
    </div>
    <div class="tours_list_details">
        <div class="upcoming_tours_clock">
            <div class="upcoming_tour_title">
                <img src="/images/customer/images/clock.svg" alt="clock" /><span>UPCOMING TOUR</span>
            </div>
            <div class="event_list">
                <div class="event_image_title">
                        <div class="event_img"><img src="/images/customer/images/royal_ascot.png" alt="royal img"/></div>
                        <div class="title_date">
                            <h4>Royal Ascot African Ladies 2024</h4>
                            <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                            <p class="ticket_type"><span>Ticket Type</span><span>Lorem Ipsum</span></p>
                            <p class="booking_reference"><span>Booking Reference</span><span>OTM0O003E</span></p>
                        </div> 
                </div>
                <div class="common_btn"><a href=""><img src="/images/customer/images/download_icon.svg" />DOWNLOAD ITINERARY</a></div>
            </div>
        </div>
        <div class="customer_details">
            <h2>Customer Details</h2>
            <div class="lead_guest name_address_font">
                <h5>LEAD GUEST</h5>
                <div><span class="lead_guest_name">Name</span><span>Noemi Rath</span></div>
                <div><span class="lead_guest_email">Email address</span><span>noemirath@gmail.com</span></div>
            </div>
            <div class="other_guests name_address_font">
                <h5>OTHER GUESTS</h5>
                <div class="guests_row">
                    <div class="guest_colm1">
                        <p><span>Guest 1</span><span>Tom Rath</span></p>
                        <p><span class="guest_eaddrs">Email address</span><span>noemirath@gmail.com</span></p>
                    </div>
                    <div class="guest_colm_email">
                        <p><span>Guest 2</span><span>Matt Rath</span></p>
                        <p class="guest_email"><input type="email"  name="email" placeholder="ENTER EMAIL ADDRESS" required></p>
                    </div>
                    <div class="guest_colm_email">
                        <p><span>Guest 2</span><span>Ben Rath</span></p>
                        <p class="guest_email"><input type="email"  name="email" placeholder="ENTER EMAIL ADDRESS" required></p>
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        <hr>
        <div class="onsite_agent_details name_address_font">
            <h2>Onsite Agent Details</h2>
            <div class="lead_guest">
                <div><span class="agent_name">Name</span><span>John Smithvv</span></div>
                <div><span class="agend_phone_no">Phone Number</span><span>+44 75343 1536235</span></div>
                <div><span class="agend_email">Email address</span><span>niko04@bayer.net</span></div>
            </div>
        </div>
        <hr>
        <div class="travel_insurance name_address_font">
            <div class="travel_title_btn"><h2>Travel Insurance</h2> <div class="common_btn"><a href=""><img src="/images/customer/images/download_icon.svg" />DOWNLOAD INSURANCE</a></div></div>
                <div class="lead_guest">
                    <div><span class="traveler_name">Traveler Name</span><span>Noemi Rath</span></div>
                    <div><span class="policy_no">Policy Number</span><span>B2CSG5000004549 </span></div>
                </div>
        </div>
        <hr>
        <div class="optional_add_ons name_address_font">
            <h2>Optional add-ons & upgrades</h2>
            <div class="add_ons_row">
                <div class="ticket_upgrade_colm">
                    <h5>TICKET UPGRADE</h5>
                        <div class="img_title_date_btn_1">
                            <div class="add_ons_image_date">
                                <div class="add_ons_img"><img src="/images/customer/images/add_ons_img_1.png" /> </div>
                                    <div class="add_ons_title_date">
                                    <h4>4 Nights, 5 Star Accommodation</h4>
                                        <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                        <p class="event_location"><img src="/images/customer/images/location.svg" />SYDNEY, AUSTRALIA</p>
                                    </div>
                            </div>
                            <div class="dollar_amt_btn">
                                <div class="dollar_amt_btn_inr"><div>A$2,300</div><button class="ticket_added"><img src="/images/customer/images/check_btn.svg" /> ADDED </button></div>
                            </div>
                        </div>
                        <div class="img_title_date_btn_2">
                            <div class="add_ons_image_date">
                                <div class="add_ons_img"><img src="/images/customer/images/add_ons_img_2.png" /> </div>
                                    <div class="add_ons_title_date">
                                    <h4>4 Nights, 5 Star Accommodation</h4>
                                        <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                        <p class="event_location"><img src="/images/customer/images/location.svg" />SYDNEY, AUSTRALIA</p>
                                    </div>
                            </div>
                            <div class="dollar_amt_btn">
                                <div class="dollar_amt_btn_inr"><div>A$2,300</div><button class="ticket_add"><img src="/images/customer/images/add_btn.svg" /> ADD </button></div>
                            </div>
                        </div>
                        <div class="add_tour">
                            <h5>ADD A TOUR</h5>
                            <div class="img_title_date_btn_1">
                                <div class="add_ons_image_date">
                                    <div class="add_ons_img"><img src="/images/customer/images/add_ons_img_1.png" /> </div>
                                        <div class="add_ons_title_date">
                                        <h4>4 Nights, 5 Star Accommodation</h4>
                                            <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                            <p class="event_location"><img src="/images/customer/images/location.svg" />SYDNEY, AUSTRALIA</p>
                                        </div>
                                </div>
                                <div class="dollar_amt_btn">
                                    <div class="dollar_amt_btn_inr"><div>A$2,300</div><button class="ticket_added"><img src="/images/customer/images/check_btn.svg" /> ADDED </button></div>
                                </div>
                            </div>
                            <div class="img_title_date_btn_2">
                                <div class="add_ons_image_date">
                                    <div class="add_ons_img"><img src="/images/customer/images/add_ons_img_2.png" /> </div>
                                        <div class="add_ons_title_date">
                                        <h4>4 Nights, 5 Star Accommodation</h4>
                                            <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                            <p class="event_location"><img src="/images/customer/images/location.svg" />SYDNEY, AUSTRALIA</p>
                                        </div>
                                </div>
                                <div class="dollar_amt_btn">
                                    <div class="dollar_amt_btn_inr"><div>A$2,300</div><button class="ticket_add"><img src="/images/customer/images/add_btn.svg" /> ADD </button></div>
                                </div>
                            </div> 
                        </div>
                </div>
                <div class="stay_extra_colm">
                    <h5>STAY EXTRA NIGHTS</h5>
                        <div class="img_title_date_btn_1">
                            <div class="add_ons_image_date">
                                <div class="add_ons_img"><img src="/images/customer/images/add_ons_img_1.png" /> </div>
                                    <div class="add_ons_title_date">
                                    <h4>4 Nights, 5 Star Accommodation</h4>
                                        <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                        <p class="event_location"><img src="/images/customer/images/location.svg" />SYDNEY, AUSTRALIA</p>
                                    </div>
                            </div>
                            <div class="dollar_amt_btn">
                                <div class="dollar_amt_btn_inr"><div>A$2,300</div><button class="ticket_added"><img src="/images/customer/images/check_btn.svg" /> ADDED </button></div>
                            </div>
                        </div>
                        <div class="img_title_date_btn_2">
                            <div class="add_ons_image_date">
                                <div class="add_ons_img"><img src="/images/customer/images/add_ons_img_2.png" /> </div>
                                    <div class="add_ons_title_date">
                                    <h4>4 Nights, 5 Star Accommodation</h4>
                                        <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                        <p class="event_location"><img src="/images/customer/images/location.svg" />SYDNEY, AUSTRALIA</p>
                                    </div>
                            </div>
                            <div class="dollar_amt_btn">
                                <div class="dollar_amt_btn_inr"><div>A$2,300</div><button class="ticket_add"><img src="/images/customer/images/add_btn.svg" /> ADD </button></div>
                            </div>
                        </div> 

                </div>
            </div><!--Add Ons Row End-->
            <div class="upgrade_option_row">
                <div class="contact_us_text"><p>Contact Us for more upgrade options <img src="/images/customer/images/arrow_right.svg"></p></div>
                <div class=""><p class="upgrade_btn"><button>UPGRADE</button></p></div>
            </div>
        </div>
        <hr />

        <div class="trip_itinerary_row name_address_font">
            <div class="trip_text_btn">
                <div><h2>Trip itinerary and inclusions</h2></div>
            <div><div class="common_btn"><a href=""><img src="/images/customer/images/download_icon_white.svg" />DOWNLOAD ITINERARY</a></div></div>
            </div>
            <div class="trip_days">
                <div class="date_details">DAY 01 - Thursday 28th July 2024</div>
                <h5>Outbound Flight</h5>
                <div class="trip_details">
                    <div><span class="left_label_column">Airline</span><span>RyanAir</span></div>
                    <div><span class="left_label_column">Details</span><span>London Heathrow Airport to O.R. Tambo International Airport</span></div>
                    <div><span class="left_label_column">Travel Class</span><span>Economy</span></div>
                    <div><span class="left_label_column">Date & Time</span><span>28.07.2022 10:00 to 28.07.2022 17:00</span></div>
                </div>
            </div>
            <div class="trip_days">
                <div class="date_details">DAY 02 - Friday 29th July 2024</div>
                <h5>Accommodation</h5>
                <div class="trip_details">
                    <div><span class="left_label_column">Hotel</span><span>Signature Lux Hotel by ONOMO Foreshore</span></div>
                    <div><span class="left_label_column">No. of nights</span><span>1</span></div>
                    <div><span class="left_label_column">Address</span><span>31A Heerengracht Street, Roggebaai Square, Cape Town, South Africa, 8001</span></div>
                    <div><span class="left_label_column">Quantity</span><span>1</span></div>
                    <div><span class="left_label_column">Date & Time</span><span>29.07.2022 19:00 to 30.07.2022 09:00</span></div>
                </div>
            </div>
            <div class="trip_days">
                <div class="date_details">DAY 03 - Saturday 30th July 2024</div>
                <h5>Accommodation</h5>
                <div class="trip_details">
                    <div><span class="left_label_column">Hotel</span><span>Signature Lux Hotel by ONOMO Foreshore</span></div>
                    <div><span class="left_label_column">No. of nights</span><span>1</span></div>
                    <div><span class="left_label_column">Address</span><span>31A Heerengracht Street, Roggebaai Square, Cape Town, South Africa, 8001</span></div>
                    <div><span class="left_label_column">Quantity</span><span>1</span></div>
                    <div><span class="left_label_column">Date & Time</span><span>29.07.2022 19:00 to 30.07.2022 09:00</span></div>
                </div>
            </div>
        </div><!--Trip itinerary row-->
        <hr />
        <div class="event_information">
            <h3>Event Information</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elitorem ipsum dolor sit amet, consectetur adipiscing elit. Donec cursus  Lorem ipsum dolor sit amet, consectetur adipiscing elitorem ipsum dolor sit amet, consectetur adipiscing elit. Donec cursus.</p>
        </div>
        <hr />
        <div class="final_details">
            <h3>Final Details</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elitorem ipsum dolor sit amet, consectetur adipiscing elit. Donec cursus  Lorem ipsum dolor sit amet, consectetur adipiscing elitorem ipsum dolor sit amet, consectetur adipiscing elit. Donec cursus.</p>
        </div>
        <hr />
        <div class="notes_div">
            <h3>Notes</h3>
            <div class="notes_row">
                <div class="notes_colm">
                    <p class="notes_title">Quick Notes</p>
                </div>
                <div class="notes_colm">
                    <p class="notes_title">Specific Order Notes</p>
                </div>
            </div>
        </div>               
    </div>
</div>
@else
    <div class="inner_content">
        <div class="overview_top_bar">
            <p class="overview_title">Tours </p>
            <div class="search_field"><p><input type="text" placeholder="SEARCH"></p></div>
        </div>
        <div class="tours_list">
            <div class="upcoming_tours">
                <h2>Upcoming Tours <span class="tours_count">2</span></h2>
                <div class="event_list">
                    <div class="event_image_title">
                            <div class="event_img"><img src="/images/customer/images/royal_ascot.png" alt="royal img"/></div>
                            <div class="title_date">
                                <h4>Royal Ascot African Ladies 2024</h4>
                                <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                <p class="view_details"><a href="itinerary/OTM00600CT">VIEW DETAILS <img src="/images/customer/images/arrow_right.svg" /></a></p>
                            </div> 
                    </div>
                    <div class="common_btn"><a href=""><img src="/images/customer/images/download_icon.svg" />DOWNLOAD ITINERARY</a></div>
                </div>
                <hr>
                <div class="event_list">
                    <div class="event_image_title">
                            <div class="event_img"><img src="/images/customer/images/royal_ascot_1.png" alt="royal img"/></div>
                            <div class="title_date">
                                <h4>Royal Ascot African Ladies 2024</h4>
                                <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                <p class="view_details"><a href="itinerary/OTM00600CT">VIEW DETAILS <img src="/images/customer/images/arrow_right.svg" /></a></p>
                            </div> 
                    </div>
                    <div class="common_btn"><a href=""><img src="/images/customer/images/download_icon.svg" />DOWNLOAD ITINERARY</a></div>
                </div>
            </div>
            <div class="past_tours">
                <h2>Past Tours</h2>
                    <div class="past_tour_row">
                        <div class="past_tours_column">
                            <div class="past_image_title">
                                    <div class="tour_event_img"><img src="/images/customer/images/past_tours_1.png" alt="event_img_1"/></div>
                                    <div class="event_title_date">
                                        <h4>Event 01</h4>
                                        <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                        <p class="event_location"><img src="/images/customer/images/location.svg" />SYDNEY, AUSTRALIA</p>
                                    </div> 
                            </div>
                            <div class="common_btn"><a href=""> <img src="/images/customer/images/download_icon.svg" /> ITINERARY</a><a href="" class="invoice_btn"> <img src="/images/customer/images/download_icon.svg" /> INVOICE</a></div>
                        </div>
                        <div class="past_tours_column">
                            <div class="past_image_title">
                                    <div class="tour_event_img"><img src="/images/customer/images/past_tours_2.png" alt="event_img_1"/></div>
                                    <div class="event_title_date">
                                        <h4>Event 02</h4>
                                        <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                        <p class="event_location"><img src="/images/customer/images/location.svg" />SYDNEY, AUSTRALIA</p>
                                    </div> 
                            </div>
                            <div class="common_btn"><a href=""> <img src="/images/customer/images/download_icon.svg" /> ITINERARY</a><a href="" class="invoice_btn"> <img src="/images/customer/images/download_icon.svg" /> INVOICE</a></div>
                        </div>
                        <div class="past_tours_column">
                            <div class="past_image_title">
                                    <div class="tour_event_img"><img src="/images/customer/images/past_tours_3.png" alt="event_img_1"/></div>
                                    <div class="event_title_date">
                                        <h4>Event 03</h4>
                                        <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                        <p class="event_location"><img src="/images/customer/images/location.svg" />SYDNEY, AUSTRALIA</p>
                                    </div> 
                            </div>
                            <div class="common_btn"><a href=""> <img src="/images/customer/images/download_icon.svg" /> ITINERARY</a><a href="" class="invoice_btn"> <img src="/images/customer/images/download_icon.svg" /> INVOICE</a></div>
                        </div>


                    </div>
                
                                    
            </div>
        </div>
    </div>
@endif
@endsection
