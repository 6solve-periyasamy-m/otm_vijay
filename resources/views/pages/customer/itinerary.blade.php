@extends('layout.customer')

@section('title', 'View Itinerary')

@php
use Carbon\Carbon;
use App\Models\Location\Country;
if($order){
    $orderNotesLock     = $order->tour->repository->isOrderNotesLocked();
    $accommodationLock  = $order->tour->repository->isAccommodationLocked();
    $activityLock       = $order->tour->repository->isActivityLocked();
    $flightLock         = $order->tour->repository->isFlightLocked();
    $transportLock      = $order->tour->repository->isTransportLocked();
}

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
    {{-- -<div class="row payment-balance">
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
@php
    $eventName = $order->tour->event->name ?? $order->tour->name;
@endphp
<div class="inner_content">     
    <x-customer.overview-top-bar title="{{ $eventName }}" :search="false" :back="true" :backUrl="route('customer.itinerary')" />
    <div class="tours_list_details">
        <div class="upcoming_tours_clock">
            {{-- <div class="upcoming_tour_title">
                <img src="/images/customer/images/clock.svg" alt="clock" />
                <h6 class="badge badge-{{ $orderCustomer->order->status->color() }} fw-bold ">{{ $orderCustomer->order->status->description() }}</h6>
            </div> --}}
            <h6  class="btn btn-warning">{{ $orderCustomer->order->status->description() }}</h6>
            <div class="event_list">
                <div class="event_image_title">
                    @php 
                        if (!empty($order->tour->event->image_url)){
                                    $evenImg1 = $order->tour->event->image_url;
                        } else{
                            $evenImg1 = 'images/default_image.png';
                        }
                    @endphp
                        <div class="event_img"><img src="{{asset($evenImg1)}}" alt="{{ $order->tour->event->name }}"/></div>
                        <div class="title_date">                            
                            <h4>{{ $order->tour->event->name }}</h4>
                            <p>{{ $order->tour->name }} </p>
                            <p class="calendar_date"><img src="{{ asset('/images/customer/images/calendar.svg')}}" />
                              {{ Carbon::parse($order->tour->date_from)->format('d M Y') }}  - {{ Carbon::parse($order->tour->date_to)->format('d M Y')}}
                            </p>
                            <p class="ticket_type"><span>Ticket Type</span><span>{{$order->leadBooker->customer->first_name ?? '' . " " .$order->leadBooker->customer->last_name ?? ''}}</span></p>
                            <p class="booking_reference"><span>Booking Reference</span><span>{{$order->booking_reference}}</span></p>
                        </div> 
                </div>
                {{-- <div class="common_btn d-inline">
                    <p><a href="{{ route('customer.itinerary.download', ['reference' => $order->booking_reference, 'customer' => $orderCustomer->customer_id ?? '']) }}" target="_blank"><img src="{{ asset('images/customer/images/download_icon.svg') }}" />DOWNLOAD ITINERARY</a></p>
                    <p><a href="{{ route('customer.preview.download', ['reference' => $order->booking_reference, 'customer' => $orderCustomer->customer_id ?? '']) }}" target="_blank"><img src="{{ asset('images/customer/images/download_icon.svg') }}" />RESERVATION DOCUMENT</a></p>
                </div> --}}
                <div class="common_btn d-inline">
                    <p><a href="{{ route('customer.itinerary.download', ['reference' => $order->booking_reference, 'customer' => $orderCustomer->customer_id ?? '']) }}" target="_blank" class="download_itinerary_link"><img src="{{ asset('images/customer/images/download_icon.svg') }}" class="download_itinerary_org_icn"/>
                    <img src="{{ asset('images/customer/images/download_icon_white.svg') }}" class="download_itinerary_wht_icn"/>DOWNLOAD ITINERARY</a></p>
                    <p><a href="{{ route('customer.preview.download', ['reference' => $order->booking_reference, 'customer' => $orderCustomer->customer_id ?? '']) }}" target="_blank" class="reservation_doc_link"><img src="{{ asset('images/customer/images/download_icon.svg') }}" class="reservation_org_icn" />
                    <img src="{{ asset('images/customer/images/download_icon_white.svg') }}" class="reservation_wht_icn"/>RESERVATION DOCUMENT</a></p>
                </div>
            </div>
        </div>
        <div class="customer_details">
            <h2>Customer Details</h2>
            <div class="lead_guest name_address_font">
                <h5>LEAD GUEST</h5>
                <div><span class="lead_guest_name">Name</span><span>{{$order->leadBooker->customer->first_name ?? '' . " " .$order->leadBooker->customer->last_name ?? ''}}</span></div>
                <div><span class="lead_guest_email">Email address</span><span>{{ $order->leadBooker->customer->email_address }}</span></div>
            </div>
            @if(isset($order->orderCustomers))
            <div class="other_guests name_address_font">
                <h5>OTHER GUESTS</h5>
                <div class="guests_row">
                        @foreach($order->orderCustomers as $key => $ordersCustomer)
                            <div class="guest_colm1">
                                <p><span>Guest {{ $key+1 }}</span><span>{{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}</span></p>
                                @if($ordersCustomer->customer->email_address)
                                <p>
                                    <span class="guest_eaddrs">Email address</span>
                                    <span>{{ $ordersCustomer->customer->email_address ?? '-'}}</span>
                                </p>
                                @else
                                    <livewire:customer.order-customer-email :orderCustomer="$ordersCustomer" />
                                @endif
                            </div>
                        @endforeach
                        <!-- <div class="guest_colm_email">
                            <p><span>Guest 2</span><span>Matt Rath</span></p>
                            <p class="guest_email"><input type="email"  name="email" placeholder="ENTER EMAIL ADDRESS" required></p>
                        </div>
                        <div class="guest_colm_email">
                            <p><span>Guest 2</span><span>Ben Rath</span></p>
                            <p class="guest_email"><input type="email"  name="email" placeholder="ENTER EMAIL ADDRESS" required></p>
                        </div>
                            -->
                </div>             
            </div>
            @endif
        </div>
        <hr>
        @if($order->agent)
        <div class="onsite_agent_details name_address_font">
            <h2>Onsite Agent Details</h2>
            <div class="lead_guest">
                <div><span class="agent_name">Name : </span><span>{{(optional($order->agent)->first_name ?? '') . ' ' .(optional($order->agent)->last_name ?? '-')}}</span></div>
                    <!-- <div><span class="agend_phone_no">Phone Number</span><span>{{(optional($order->agent)->first_name ?? '')}}</span></div> -->
                <div><span class="agend_email">Email address : </span><span>{{(optional($order->agent)->email ?? '-')}}</span></div>
            </div>
        </div>
        <hr>
        @endif
        @if($orderCustomer->travel_insurer)
        <div class="travel_insurance name_address_font">
            <div class="travel_title_btn"><h2>Travel Insurance</h2></div>
            <div class="lead_guest">
                <div><span class="traveler_name">Traveler Name</span><span>{{ $orderCustomer->travel_insurer ?? '-' }}</span></div>
                <div><span class="policy_no">Policy Number</span><span>{{ $orderCustomer->policy_number ?? '-'}} </span></div>
            </div>
        </div>
        <hr />
        @endif
        {{--<div class="optional_add_ons name_address_font">
            <h2>Optional add-ons & upgrades</h2>
            @foreach($orderCustomer->orderActivities as $orderActivity)
                @if($orderActivity->tourComponent->tour_component_type == 'Add On')
                    <div class="add_ons_row">
                        <div class="ticket_upgrade_colm">
                            <h5>TICKET UPGRADE</h5>
                            <div class="img_title_date_btn_1">
                                <div class="add_ons_image_date">
                                    <div class="add_ons_img"><img src="/images/customer/images/add_ons_img_1.png" /> </div>
                                        <div class="add_ons_title_date">
                                            <h4>{{ $orderActivity->activity->name }}</h4>
                                            <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />{{ f_datetime($orderActivity->activity_inventory->starts_at) }} to {{ f_datetime($orderActivity->activity_inventory->ends_at) }}</p>
                                            <p class="event_location"><img src="/images/customer/images/location.svg" />{{ $orderActivity->activity->activityType->name }}</p>
                                        </div>
                                </div>
                                <div class="dollar_amt_btn">
                                    <div class="dollar_amt_btn_inr"><div>
                                        @if($orderActivity->tourComponent->tour_component_type == 'Included')
                                            {{ f_currency(0) }}
                                        @else
                                            {{ f_currency($orderActivity->cost) }}
                                        @endif
                                    </div><button class="ticket_added"><img src="/images/customer/images/check_btn.svg" /> ADDED </button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Add Ons Row End-->
                @endif
            @endforeach
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
            </div>
            <div class="upgrade_option_row">
                <div class="contact_us_text"><p>Contact Us for more upgrade options <img src="/images/customer/images/arrow_right.svg"></p></div>
                <div class=""><p class="upgrade_btn"><button>UPGRADE</button></p></div>
            </div>
        </div>
        --}}
        <div class="trip_itinerary_row name_address_font">
            <div class="trip_text_btn">
                <div><h2>Trip itinerary and inclusions</h2></div>
            <div><div class="common_btn"><a href="{{ route('customer.itinerary.download', ['reference' => $order->booking_reference, 'customer' => $orderCustomer->customer_id ?? '']) }}" target="_blank"><img src="{{ asset('images/customer/images/download_icon_white.svg') }}" />DOWNLOAD ITINERARY</a></div></div>
            </div>            
            @php
                $groupedByDate      = [];
                $accommodationdata  = [];
                $activitydata       = [];
                $flightdata         = [];
                $transportdata      = [];

                foreach ($orderCustomer->orderAccommodation as $accommodation) {
                    $date = Carbon::parse(optional($accommodation->tourComponent->inventory)->check_in)->toDateString();
                    $groupedByDate[$date]['accommodations'][] = $accommodation;
                    $accommodationdata[] = $accommodation;
                }

                foreach ($orderCustomer->orderActivities as $activity) {
                    $date = Carbon::parse(optional($activity->activity_inventory)->starts_at)->toDateString();
                    $groupedByDate[$date]['activities'][] = $activity;
                    $activitydata[] = $activity;
                }

                foreach ($orderCustomer->orderFlights as $flight) {
                    $date = Carbon::parse(optional($flight->flight_inventory)->departs_at)->toDateString();
                    $groupedByDate[$date]['flights'][] = $flight;
                    $flightdata[] = $flight;
                }

                foreach ($orderCustomer->orderTransports as $transport) {
                    $date = Carbon::parse(optional($transport->repository)->getStartTime())->toDateString();
                    $transportdata[] = $transport;
                }

                ksort($groupedByDate); // sort by date
            @endphp
           {{-- @foreach($groupedByDate as $date => $components)
                <div class="trip_days">
                    <div class="date_details">{{ Carbon::parse($date)->format('l jS F Y') }}</div>

                    @if (!empty($components['accommodations']))
                        @foreach ($components['accommodations'] as $orderAccommodation)
                            <h5>Accommodation</h5>
                            @php
                                $inventory = $orderAccommodation->tourComponent->inventory ?? null;
                                $checkIn = $inventory->check_in ?? null;
                                $checkOut = $inventory->check_out ?? null;
                                $days = ($checkIn && $checkOut) ? Carbon::parse($checkOut)->diffInDays(Carbon::parse($checkIn)) + 1 : 0;
                                $address = $inventory->accommodation->address ?? null;
                            @endphp
                            <div class="trip_details">
                                <div><span class="left_label_column">Hotel</span><span>{{ $inventory->accommodation->name ?? '-' }}</span></div>
                                <div><span class="left_label_column">No. of nights</span><span>{{ $days }}</span></div>
                                <div><span class="left_label_column">Address</span>
                                    <span>
                                        {{ implode(', ', array_filter([
                                            $address->address_line_1 ?? '',
                                            $address->address_line_2 ?? '',
                                            $address->address_line_3 ?? '',
                                            $address->town ?? '',
                                            $address->region ?? '',
                                            $address->postcode ?? '',
                                        ])) }}
                                    </span>
                                </div>
                                <div><span class="left_label_column">Quantity</span><span>{{ empty($orderAccommodation->group->getMembers($orderCustomer)) ? 'Not Shared' : $orderAccommodation->group->getMembers($orderCustomer) }}</span></div>
                                <div><span class="left_label_column">Date & Time</span>
                                    <span>{{ f_datetime($checkIn) ?? '-' }} to {{ f_datetime($checkOut) ?? '-' }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if (!empty($components['activities']))
                        @foreach ($components['activities'] as $orderActivity)
                            <h5>Activity</h5>
                            <div class="trip_details">
                                <div><span class="left_label_column">Name</span><span>{{ $orderActivity->activity->name ?? '-' }}</span></div>
                                <div><span class="left_label_column">Ticket Type</span><span>{{ $orderActivity->activity_inventory->ticketType->name ?? '-' }}</span></div>
                                <div><span class="left_label_column">Date & Time</span><span>{{ f_datetime($orderActivity->activity_inventory->starts_at) }} to {{ f_datetime($orderActivity->activity_inventory->ends_at) }}</span></div>
                            </div>
                        @endforeach
                    @endif

                    @if (!empty($components['flights']))
                        @foreach ($components['flights'] as $orderFlight)
                            <h5>Flight</h5>
                            <div class="trip_details">
                                <div><span class="left_label_column">Number</span><span>{{ $orderFlight->flight_number ?? '-' }}</span></div>
                                <div><span class="left_label_column">Flight Details</span><span>{{ $orderFlight->flight->departureAirport->name ?? '-' }} to {{ $orderFlight->flight->arrivalAirport->name ?? '-' }}</span></div>
                                <div><span class="left_label_column">Travel Class</span><span>{{ $orderFlight->flight_inventory->travelClass->name ?? '-' }}</span></div>
                                <div><span class="left_label_column">Component Type</span><span>{{ $orderFlight->flightInventoryTour->tour_component_type ?? '-' }}</span></div>
                                <div><span class="left_label_column">Date & Time</span><span>{{ f_datetime($orderFlight->flight_inventory->departs_at) }} to {{ f_datetime($orderFlight->flight_inventory->arrives_at) }}</span></div>
                            </div>
                        @endforeach
                    @endif
                    @if (!empty($components['transports']))
                        @foreach ($components['transports'] as $orderTransport)
                            <h5>Transport</h5>
                            <div class="trip_details">
                                <div><span class="left_label_column">Name</span><span>{{ $orderTransport->transport->name ?? '-' }}</span></div>
                                <div><span class="left_label_column">Transport Type</span><span>{{ $orderTransport->transport->transportType->name ?? '-' }}</span></div>
                                <div><span class="left_label_column">Transport Information</span><span>{{ $orderTransport->transport->departureAddress->name ?? '-' }} to {{ $orderTransport->transport->arrivalAddress->name ?? '-' }}</span></div>
                                <div><span class="left_label_column">Travel Class</span><span>{{ $orderTransport->transport_inventory->travelClass->name ?? '-' }}</span></div>
                                <div><span class="left_label_column">Date & Time</span><span>{{ f_datetime($orderTransport->repository->getStartTime()) }} to {{ f_datetime($orderTransport->repository->getEndTime()) }}</span></div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach --}}
        <div class="trip_days">
            
                @if(!empty($itinerary->items['Flights']))
                    @php $firstLoop = true; @endphp

                    @foreach($itinerary->items['Flights'] as $flight)
                        @if(isset($flight->details['Quantity']) && $flight->details['Quantity'] > 0)
                        <div class="single-module mb-n15 component-break">
                            @if($firstLoop)
                                <div class="heading-module">
                                    <h5>Flights
                                    </h5>
                                </div>
                                @php $firstLoop = false; @endphp
                            @endif
                            @if($flight->details['Flight Number'] !== $flight->details['Booking Reference'])
                                <div class="details-module">
                                    <table>
                                        <tbody>
                                            <tr><td colspan="2" class="pn10"></td></tr>
                                            <tr>
                                                <td class="left_label_column"><strong>Quantity:</strong></td>
                                                <td>{{ $flight->details['Quantity'] }}</td>
                                            </tr>
                                            <tr>
                                                <td class="left_label_column"><strong>Booking Reference:</strong></td>
                                                <td>{{ $flight->details['Booking Reference'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="non-booking-ref-block">&nbsp;</p>
                            @endif
                            <div class="component-body">
                                <table class="tbl-quote-section" style="width: 90%;">
                                    <tr>
                                        <th>Airline</th>
                                        <th>Flight No.</th>
                                        <th>Class</th>
                                        <th>Date</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Departure</th>
                                        <th>Arrival</th>
                                    </tr>
                                    <tr>
                                        <td class="word-wrap" style="width:80px;">{{ $flight->name }}</td>
                                        <td style="width:100px;">{{ $flight->details['Flight Number'] }}</td>
                                        <td style="width:60px;">{{ $flight->details['Class'] }}</td>
                                        <td style="width:70px;">{{ $flight->details['Departure Date'] }}</td>
                                        <td class="word-wrap" style="width:100px;">{{ $flight->details['Departure Airport'] }}</td>
                                        <td class="word-wrap" style="width:100px;">{{ $flight->details['Arrival Airport'] }}</td>
                                        <td style="width:55px;">{{ $flight->details['Departure Time'] }}</td>
                                        <td style="width:55px;">{{ $flight->details['Arrival Time'] }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif

                @if(!empty($itinerary->items['Transfers']))
                    @php $firstLoop = true; @endphp
                    @foreach($itinerary->items['Transfers'] as $transport)
                        @if(isset($transport->details['Quantity']) && $transport->details['Quantity'] > 0)
                        <div class="single-module mb-n15">
                            @if($firstLoop)
                                <div class="heading-module">
                                    <h5>
                                        Transport
                                    </h5>
                                </div>
                                @php $firstLoop = false; @endphp
                            @endif
                            <div class="details-module">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="item-header w-125"><strong> Service: </strong></td>
                                            <td class="item-detail">{{ $transport->name }}</td>
                                        </tr>
                                        @php
                                            $disable_items = [ 'Transport', 'Travel Class'];
                                        @endphp
                                        @foreach($transport->details as $key => $value)
                                        @php
                                            $class_desc_pos = $key == 'Description' ? 'text-wrap' : '';
                                        @endphp
                                            @if (!in_array($key, $disable_items))
                                                <tr>
                                                    <td class="item-header w-125">
                                                        <strong>{{ $key }}:</strong>
                                                    </td>
                                                    <td class="item-detail <?php echo $class_desc_pos;?>">
                                                    {{ $value }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif  

                @if(!empty($itinerary->items['Accommodation']))
                    @php $firstLoop = true; @endphp
                    
                    @foreach($itinerary->items['Accommodation'] as $accommodation)
                        <div class="single-module mb-n15">
                            @if($firstLoop)
                                <div class="heading-module">
                                    <h5>Accommodation
                                    </h5>
                                </div>
                                @php  $firstLoop = false; @endphp
                            @endif
                            <h4>{{ $accommodation->name }}
                            </h4>
                            <div class="details-module">
                                <table>
                                    <tbody>
                                        @php
                                        if (isset($accommodation->details['Description'], $accommodation->details['Quantity'])) {
                                            $temp_desc = $accommodation->details['Description'];
                                            unset($accommodation->details['Description']);
                                            $accommodation->details['Description'] = $temp_desc;
                                        }
                                        @endphp
                                        @foreach($accommodation->details as $key => $value)
                                            @php  $class_desc_pos = $key == 'Description' ? 'text-wrap' : '';  @endphp
                                            @continue(empty($value))
                                            <tr>
                                                <td class="left_label_column">
                                                    <strong>{{ $key }}:</strong>
                                                </td>
                                                <td class="item-detail <?php echo $class_desc_pos;?>">
                                                    @if(is_array($value))
                                                        @if(isset($value['attributes']['address_line_1']))
                                                            {{ $value['attributes']['address_line_1'] }}
                                                        @else
                                                            Address not available
                                                        @endif
                                                    @else
                                                        {!! $value !!}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if(!empty($itinerary->items['Event'])) 
                    @php  $firstLoop = true; @endphp
                    <div class="row">
                        @foreach($itinerary->items['Event'] as $item)
                            <div class="single-module mb-n15">
                                @if($firstLoop)
                                    <div class="heading-module">
                                        <h5>Event
                                        </h5>
                                    </div>
                                    @php
                                        $firstLoop = false;
                                    @endphp
                                @endif
                                <div class="details-module">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="left_label_column"><strong>Event:</strong></td>
                                                <td>{{ $order->tour?->event?->name }}</td>
                                            </tr>
                                            @if(array_key_exists('Ticket', $item->details) && !empty($item->details['Ticket']))
                                                <tr>
                                                    <td class="left_label_column"><strong>Ticket:</strong></td>
                                                    <td>{{ $item->details['Ticket'] }}</td>
                                                </tr>
                                            @endif
                                            @if(!empty($item->details['Dates']))
                                                <tr>
                                                    <td class="left_label_column"><strong>Dates:</strong></td>
                                                    <td> <?php
                                                            $dates = explode('to', $item->details['Dates']); 
                                                            echo trim($dates[0]); 
                                                            ?>
                                                    </td>
                                                </tr>
                                            @endif

                                            @if(!empty($item->details['Venue']))
                                                <tr>
                                                    <td class="left_label_column"><strong>Venue:</strong></td>
                                                    <td>{{ $item->details['Venue'] }}</td>
                                                </tr>
                                            @endif


                                            @if(!empty($item->details['Quantity']) && $item->details['Quantity'] > 0)
                                                <tr>
                                                    <td class="left_label_column"><strong>Quantity:</strong></td>
                                                    <td>{{ $item->details['Quantity'] }}</td>
                                                </tr>
                                            @endif

                                            @if(!empty($item->details['Description']))
                                                <tr>
                                                    <td class="left_label_column"><strong>Description:</strong></td>
                                                    <td class="text-wrap">{!! $item->details['Description'] !!}</td>
                                                </tr>
                                            @endif                 
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif        

                @if(!empty($itinerary->items['Inclusion']))
                    @php  $firstLoop = true; @endphp
                    @foreach($itinerary->items['Inclusion'] as $item)
                        <div class="single-module mb-n15">
                            @if($firstLoop)
                                <div class="heading-module">
                                    <h5>Additional Inclusions
                                    </h5 >
                                </div>
                                @php $firstLoop = false; @endphp
                            @endif
                            <div class="details-module">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="left_label_column"><strong>Inclusion:</strong></td>
                                            @if(array_key_exists('Ticket', $item->details))
                                                <td>{{ $item->details['Ticket'] }}</td>
                                            @else
                                                <td>{{ $item->name }}</td>
                                            @endif
                                        </tr>
                                        @php
                                            $disable_items = ['Ticket'];
                                        @endphp
                                        @foreach($item->details as $key => $value)
                                            @php $class_desc_pos = $key == 'Description' ? 'text-wrap' : ''; @endphp
                                            @continue(empty($value))
                                            @if (!in_array($key, $disable_items))
                                            <tr>
                                                <td class="left_label_column"><strong>{{ $key }}:</strong></td>
                                                <td class="<?php echo $class_desc_pos;?>">
                                                    @if($key === 'Dates')
                                                        {{ trim(explode('to', $value)[0]) }}
                                                    @else
                                                        {!! $value !!}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                @endif

            </div>
        </div><!--Trip itinerary row-->
        <hr />
        <div class="event_information">
            <h3>Event Information</h3>
           <p>{!! data_get($orderCustomer, 'order.tour.event.description', '')  !!}</p>

        </div>
        <hr />
        <div class="final_details">
            <h3>Final Details</h3><p>
            {!!  data_get($orderCustomer, 'order.tour.event.final_terms', '')  !!}</p>
        </div>
        <hr />
        <div class="notes_div">
            <h3>Notes</h3>            
            <form action="{{ route('customer.notes.update', ['reference' => $order->booking_reference, 'orderCustomer' => $orderCustomer,]) }}" method="post" class="form-horizontal form-material">
                 @csrf
                <div class="notes_row">
                    <!-- <div class="notes_colm">
                        <p class="notes_title">{!! $orderCustomer->internal_notes ?? '' !!}</p>
                    </div>
                    <div class="notes_colm">
                        <p class="notes_title">{!! $orderCustomer->external_notes ?? '' !!}</p>
                    </div> -->
                    <div class="notes_colm">
                        <x-customer.input.text-area  name="external_notes" value="{{ $order->external_notes }}">
                            External Notes
                        </x-customer.input.text-area>
                    </div>
                    {{-- <div  class="notes_colm">
                        <x-customer.input.text-area  name="internal_notes" value="{{ $order->internal_notes }}">
                            Internal Notes
                        </x-customer.input.text-area>
                    </div> --}}
                    <div class="">
                        @include('partials.fields.submit')
                    </div>
                </div>
            </form>
        </div>               
    </div>
</div>
@else
    <div class="inner_content">
         <x-customer.overview-top-bar title="Tours" :search="false"  />
        @php

            $upcomingOrders = $orders->filter(function ($order) {
                return optional($order->tour)->date_to && Carbon::parse($order->tour->date_to)->isFuture();
            });

            $pastOrders = $orders->filter(function ($order) {
                return optional($order->tour)->date_to && Carbon::parse($order->tour->date_to)->isPast();
            });
        @endphp
        <div class="tours_list">
            <div class="upcoming_tours">
                @if($upcomingOrders->count() > 0)
                    <h2>Upcoming Trips <span class="tours_count">{{ $upcomingOrders->count() }}</span></h2>
                @endif
                @foreach($upcomingOrders as $kupcom => $vupcom)
                    <div class="event_list">
                        <div class="event_image_title">
                            @php
                            if (!empty($vupcom->tour->event->image_url)){
                                $evenImg = $vupcom->tour->event->image_url;
                            } else{
                                $evenImg = 'images/default_image.png';
                            }
                            @endphp
                                <div class="event_img"><img src="{{asset($evenImg)}}" alt="{{ $vupcom->tour->event->name }}"/></div>
                                <div class="title_date">
                                    <h6 class="btn btn-warning">{{ $vupcom->status->description() }}</h6>
                                    <h4>{{ $vupcom->tour?->event?->name}}</h4>                            
                                    <p>{{ $vupcom->tour->name }}</p>
                                    <p class="calendar_date"><img src="{{ asset('images/customer/images/calendar.svg') }}" />
                                    {{ Carbon::parse($vupcom->tour->date_from)->format('d M Y') }}  - {{ Carbon::parse($vupcom->tour->date_to)->format('d M Y')}}</p>
                                    <p class="view_details"><a href="itinerary/{{$vupcom->booking_reference }}/{{$orderCustomer->customer_id }}"> VIEW DETAILS <img src="{{ asset('images/customer/images/arrow_right.svg') }}" /></a></p>
                                </div> 
                        </div>
                        <div class="common_btn d-inline">
                            <p><a href="{{ route('customer.itinerary.download', ['reference' => $vupcom->booking_reference, 'customer' =>$orderCustomer->customer_id]) }}" target="_blank">
                                <img src="{{ asset('images/customer/images/download_icon.svg') }}" />DOWNLOAD ITINERARY
                            </a></p>
                            <p><a href="{{ route('customer.preview.download', ['reference' => $vupcom->booking_reference, 'customer' =>$orderCustomer->customer_id]) }}" target="_blank">
                                <img src="{{ asset('images/customer/images/download_icon.svg') }}" />RESERVATION DOCUMENT
                            </a></p>
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
            <div class="past_tours">
                @if($pastOrders->count())
                    <h2>Past Tours</h2>
                    <div class="past_tour_row">
                        @foreach($pastOrders as $kpast => $vpast)
                            <div class="past_tours_column">
                                <div class="past_image_title">
                                    @php
                                    if (!empty($vpast->tour->event->image_url)){
                                        $evenImg = $vpast->tour->event->image_url;
                                    } else{
                                        $evenImg = 'images/default_image.png';
                                    }
                                    @endphp
                                    <div class="tour_event_img"><img src="{{asset($evenImg)}}" alt="{{ $vpast->tour->event->name ?? '' }}"/></div>
                                    <div class="event_title_date">
                                        <h4></h4>{{ $vpast->tour->name }}</h4>
                                        <p class="calendar_date"><img src="{{ asset('/images/customer/images/calendar.svg')}}" />
                                        {{ Carbon::parse($vpast->tour->date_from)->format('d M Y') }}  - {{ Carbon::parse($vpast->tour->date_to)->format('d M Y')}}</p>
                                        <p class="event_location"><img src="{{ asset('/images/customer/images/location.svg')}}" />{{ $vpast->tour->city }},{{ optional(Country::find($vpast->tour->country_id))->name }}
                                        </p>
                                    </div> 
                                </div>
                                @php
                                    $hrefdata = url('/customer/finances/invoice/' .  $vpast->booking_reference);
                                @endphp
                                <div class="common_btn"><a href="{{ route('customer.itinerary.download', ['reference' => $vpast->booking_reference, 'customer' => $orderCustomer->customer_id]) }}" target="_blank"> <img src="{{ asset('images/customer/images/download_icon.svg')}}" /> ITINERARY</a><a  href="{{ $hrefdata }}"  target="_blank" class="invoice_btn"> <img src="{{ asset('images/customer/images/download_icon.svg')}}" /> INVOICE</a></div>
                            </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center justify-content-center align-items-center" style="height: 100vh;">
                        <p>No tours found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
@endsection
