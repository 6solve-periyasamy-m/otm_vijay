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
                                <h4>{{ $vupcom->tour->name }}</h4>                                
                                <p class="calendar_date"><img src="{{ asset('images/customer/images/calendar.svg') }}" />
                                {{ Carbon::parse($vupcom->tour->date_from)->format('d/M/Y') }}  - {{ Carbon::parse($vupcom->tour->date_to)->format('d/M/Y')}}</p>
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
                                <h4>{{ $orderCustomer->order->tour?->name ?? "Tour Deleted" }}</h4>
                                <p class="calendar_date"><img src="{{ asset('images/customer/images/calendar.svg') }}" />
                                {{ Carbon::parse($orderCustomer->order->tour->date_from)->format('d/M/Y') }}  - {{ Carbon::parse($orderCustomer->order->tour->date_to)->format('d/M/Y')}}</p>
                            </div> 
                    </div>
                    @if(str_contains($orderCustomer->order->status->description(), 'Outstanding'))
                        <div class="common_btn"><span class="dollar_amount">{{ f_currency($orderCustomer->tour_cost) }}</span><a href="">PAY NOW <img src="{{ asset('images/customer/images/arrow_right.svg') }}" /></a></div>
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
                                <h4>{{ $vpast->tour->name }}</h4>
                                <p class="calendar_date"><img src="{{ asset('images/customer/images/calendar.svg') }}" />
                                {{ Carbon::parse($vpast->tour->date_from)->format('d/M/Y') }}  - {{ Carbon::parse($vpast->tour->date_to)->format('d/M/Y')}}
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
@endsection
