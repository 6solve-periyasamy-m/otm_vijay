@php
/**
 * @var \App\Models\System\Brand $branding
 */
    $branding = $branding ?? \App\Models\System\Brand::getSystemBrand();
    $gateways = \Gateway::getDefaultGateway() !== null;
@endphp
@extends('layout.customer', ['overflow' => false,])
@section('title', 'Customer Portal')
@php
    use Carbon\Carbon;
    use App\Models\Location\Country;
@endphp
@section('content')
 <div class="inner_content">
    <x-customer.overview-top-bar title="Overview" :search="false" />
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
            <h2>Upcoming Trips <span class="tours_count">{{ $upcomingOrders->count() }}</span></h2>
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
				                <h6  class="badge badge-{{ $vupcom->status->color() }} fw-bold overdue_btn">{{ $vupcom->status->description() }}</h6>
                                <h4>{{ $vupcom->tour?->event?->name}}</h4>                            
                                <p>{{ $vupcom->tour->name }}</p>
                                <p class="calendar_date"><img src="{{ asset('images/customer/images/calendar.svg') }}" />
                                {{ Carbon::parse($vupcom->tour->date_from)->format('d M Y') }}  - {{ Carbon::parse($vupcom->tour->date_to)->format('d M Y')}}</p>
                                <p class="view_details"><a href="itinerary/{{$vupcom->booking_reference }}/{{$orderCustomer->customer_id }}"> VIEW DETAILS <img src="{{ asset('images/customer/images/arrow_right.svg') }}" /></a></p>
                            </div> 
                    </div>
                    <div class="common_btn"><a href="{{ route('customer.itinerary.download', ['reference' => $vupcom->booking_reference, 'customer' =>$orderCustomer->customer_id]) }}" target="_blank">
                        <img src="{{ asset('images/customer/images/download_icon.svg') }}" />DOWNLOAD ITINERARY</a>
                    </div>
                </div>
                <hr>
            @endforeach
        </div>
        <div class="upcoming_payments">
            <h2>Upcoming Payments <span class="tours_count">{{ $customer->orderCustomers->count() }}</span></h2>
              @foreach($customer->orderCustomers as $orderCustomer)
                    <div class="event_list">
                        <div class="event_image_title">
                            @php
                                if (!empty($vupcom->tour->event->image_url)){
                                    $evenImg2 = $vupcom->tour->event->image_url;
                                } else{
                                    $evenImg2 = 'images/default_image.png';
                                }
                            @endphp
                            <div class="event_img"><img src="{{asset($evenImg2)}}" alt="{{ $orderCustomer->order->tour?->event?->name }}"/></div>
                            <div class="title_date">
                                <!-- <button class="overdue_btn"><h6 class="badge badge-{{ $orderCustomer->order->status->color() }} fw-bold">{{ $orderCustomer->order->status->description() }}</h6></button> -->
				                <h6 class=" badge badge-{{ $orderCustomer->order->status->color() }} fw-bold overdue_btn">{{ $orderCustomer->order->status->description() }}</h6>
                                <h4>{{ $orderCustomer->order->tour?->event?->name}}</h4>                            
                                <p>{{ $orderCustomer->order->tour?->name ?? "Tour Deleted" }}</p>
                                <p class="calendar_date"><img src="{{ asset('images/customer/images/calendar.svg') }}" />
                                {{ Carbon::parse($orderCustomer->order->tour->date_from)->format('d M Y') }}  - {{ Carbon::parse($orderCustomer->order->tour->date_to)->format('d M Y')}}</p>
                            </div> 
                        </div>
                        @if(str_contains($orderCustomer->order->status->description(), 'Outstanding'))
                            @php
                                $paymentDetails = collect([
                                    $orderCustomer->order->payment_details,
                                    $orderCustomer->order->quote?->payment_details,
                                    $orderCustomer->order->tour?->payment_details,
                                    setting('company.bank_transfer')
                                ])->first(fn($value) => !empty($value));
                            @endphp
                            <div class="common_btn">
                                <span class="dollar_amount">{{ fr_currency($orderCustomer->order->cache->total_owed, $orderCustomer->order->currency, false, 0) }}</span>
                                <a href="javascript:void(0);" class="pay-now-btn"
                                data-order-id="{{ $orderCustomer->order->id }}">
                                PAY NOW <img src="{{ asset('images/customer/images/arrow_right.svg') }}" />
                                </a>
                            </div>
                            <div id="payment-details-{{ $orderCustomer->order->id }}" class="payment-details" style="display:none;">
                                {!! $paymentDetails !!}
                            </div>
                            <!-- Popup -->
                            <div class="hotel-more-info-popup" data-order-id="{{ $orderCustomer->order->id }}">
                                <div class="hotel-more-info-contain">
                                    <div class="hotel-more-info-block">
                                        <div class="info-body">
                                            <div class="hotel-close-button">
                                                <img src="{{ asset('images/customer/images/Close-Button.svg') }}" alt="package-details">
                                            </div>
                                            <h4>Payment Type</h4>
                                            <!-- Placeholder for payment details -->
                                            <div class="payment-details-content"></div>
                                            @if($gateways)
                                            <form class="form-material" action="{{ route('customer.payment.make') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="booking_reference" id="form-booking-reference" value="{{ $orderCustomer->order->booking_reference }}">
                                                <input type="hidden" name="amount" id="amount" value="{{ $orderCustomer->order->cache->total_owed }}">
                                                <div class="order_amount">Total amount to pay : {{ fr_currency($orderCustomer->order->cache->total_owed, $orderCustomer->order->currency, false, 0) }} </div>
                                                <button type="submit" class="next-button">
                                                    <span>
                                                        <span>CHECKOUT</span>
                                                        <img src="{{ asset('images/customer/images/Right-arrow-mod.svg') }}" alt="right-arrow">
                                                    </span>
                                                </button>
                                            </form>
                                            @else
                                                <div class="col-12">
                                                    <div class="row">
                                                        <span class="heading">This operator has not enabled online payments</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                <hr>
            @endforeach
        </div>

        <div class="past_tours">
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
                            <div class="tour_event_img"><img src="{{asset($evenImg)}}" alt="{{ $vpast->tour?->event?->name }}"/></div>
                            <div class="event_title_date">
                                <h4>{{ $vpast->tour?->event?->name}}</h4>
                                <p>{{ $vpast->tour->name }}</p>
                                <p class="calendar_date"><img src="{{ asset('images/customer/images/calendar.svg') }}" />
                                {{ Carbon::parse($vpast->tour->date_from)->format('d M Y') }}  - {{ Carbon::parse($vpast->tour->date_to)->format('d M Y')}}
                                </p>
                                @if($vpast->tour->city != '' && optional(Country::find($vpast->tour->country_id))->name != '' )
                                    <p class="event_location"><img src="{{ asset('images/customer/images/location.svg') }}" />
                                        {{ $vpast->tour->city }},{{ optional(Country::find($vpast->tour->country_id))->name }}
                                    </p>
                                @endif
                            </div> 
                        </div>
                        @php
                            $hrefdata = url('/customer/finances/invoice/' .  $vpast->booking_reference);
                        @endphp
                        <div class="common_btn">
                            <a href="{{ route('customer.itinerary.download', ['reference' => $vpast->booking_reference, 'customer' => $orderCustomer->customer_id]) }}" target="_blank"><img src="{{ asset('images/customer/images/download_icon.svg') }}" /> ITINERARY</a>
                            <a href="{{ $hrefdata }}"  target="_blank" class="invoice_btn"><img src="{{ asset('images/customer/images/download_icon.svg') }}" /> INVOICE</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
 <script>
jQuery(document).on('click', '.common_btn .pay-now-btn', function () {
    var orderId = jQuery(this).data('order-id');
    var paymentDetails = jQuery('#payment-details-' + orderId).html();
    var popup = jQuery('.hotel-more-info-popup[data-order-id="' + orderId + '"]');
    popup.find('.payment-details-content').html(paymentDetails);
    popup.css('visibility', 'visible');
});

jQuery(document).on('click', '.hotel-more-info-popup .hotel-close-button', function () {
    jQuery(this).closest('.hotel-more-info-popup').css('visibility', 'hidden');
});
</script>
@endsection
