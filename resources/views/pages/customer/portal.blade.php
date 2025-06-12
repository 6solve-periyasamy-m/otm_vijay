@extends('layout.customer', ['overflow' => false,])

@section('title', 'Customer Portal')

@section('content')
{{-- <div style="position: relative;">
    <div class="d-flex justify-content-center align-items-center h-75 mt-4">
        <div class="donut-menu">
            <div class="menu-item">
            </div>
            <div class="menu-item">
            </div>
            <div class="menu-item">
            </div>
            <div class="menu-item">
            </div>
            <div class="donut-center">
            </div>
        </div>
        <div class="click-menu">
            <div class="menu-item" onclick="window.location = '{{ route('customer.edit') }}'">
                <div class="menu-text">
                    <span class="icon-user menu-icon"></span>
                    <span>Your Details</span>
                </div>
            </div>
            <div class="menu-item" onclick="window.location = '{{ route('customer.finances') }}'">
                <div class="menu-text">
                    <span class="icon-credit-card menu-icon"></span>
                    <span>Your Finance</span>
                </div>
            </div>
            <div class="menu-item" onclick="window.location = '{{ route('customer.extras') }}'">
                <div class="menu-text">
                    <span class="icon-diamond menu-icon"></span>
                    <span>Your Extras</span>
                </div>
            </div>
            <div class="menu-item" onclick="window.location = '{{ route('customer.itinerary') }}'">
                <div class="menu-text">
                    <span class="icon-globe menu-icon"></span>
                    <span>Your Tours</span>
                </div>
            </div>
        </div>
        <div class="customer-portal-mobile">
            <div class="customer-portal-mobile-item" onclick="window.location = '{{ route('customer.edit') }}'">
                <div class="item-wrapper">
                    <span class="icon-user item-icon"></span>
                    <span class="item-text">Your Details</span>
                </div>
            </div>
            <div class="customer-portal-mobile-item" onclick="window.location = '{{ route('customer.finances') }}'">
                <div class="item-wrapper">
                    <span class="icon-credit-card item-icon"></span>
                    <span class="item-text">Your Finance</span>
                </div>
            </div>
            <div class="customer-portal-mobile-item" onclick="window.location = '{{ route('customer.extras') }}'">
                <div class="item-wrapper">
                    <span class="icon-diamond item-icon"></span>
                    <span class="item-text">Your Extras</span>
                </div>
            </div>
            <div class="customer-portal-mobile-item" onclick="window.location = '{{ route('customer.itinerary') }}'">
                <div class="item-wrapper">
                    <span class="icon-globe item-icon"></span>
                    <span class="item-text">Your Tours</span>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="inner_content">
    <div class="overview_top_bar">
        <p class="overview_title">Overview </p>
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
                            <p class="view_details"><a href="">VIEW DETAILS <img src="/images/customer/images/arrow_right.svg" /></a></p>
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
                            <p class="view_details"><a href="">VIEW DETAILS <img src="/images/customer/images/arrow_right.svg" /></a></p>
                        </div> 
                </div>
                <div class="common_btn"><a href=""><img src="/images/customer/images/download_icon.svg" />DOWNLOAD ITINERARY</a></div>
            </div>
        </div>
        <div class="upcoming_payments">
            <h2>Upcoming Payments <span class="tours_count">3</span></h2>
            <div class="event_list">
                <div class="event_image_title">
                        <div class="event_img"><img src="/images/customer/images/royal_ascot.png" alt="royal img"/></div>
                        <div class="title_date">
                            <button class="overdue_btn">OVERDUE</button>
                            <h4>Royal Ascot African Ladies 2024</h4>
                            <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                        </div> 
                </div>
                <div class="common_btn"><span class="dollar_amount">A$400.00</span><a href="">PAY NOW <img src="/images/customer/images/arrow_right.svg" /></a></div>
            </div>
            <hr>
            <div class="event_list">
                <div class="event_image_title">
                        <div class="event_img"><img src="/images/customer/images/royal_ascot_1.png" alt="royal img"/></div>
                        <div class="title_date">
                            <h4>Event 02</h4>
                            <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                        </div> 
                </div>
                <div class="common_btn"><span class="dollar_amount">A$400.00</span><a href="">PAY NOW <img src="/images/customer/images/arrow_right.svg" /></a></div>
            </div>
            <hr>
            <div class="event_list">
                <div class="event_image_title">
                        <div class="event_img"><img src="/images/customer/images/royal_ascot_2.png" alt="royal img"/></div>
                        <div class="title_date">
                            <h4>Event 03</h4>
                            <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                        </div> 
                </div>
                <div class="common_btn"><span class="dollar_amount">A$400.00</span><a href="">PAY NOW <img src="/images/customer/images/arrow_right.svg" /></a></div>
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
@endsection

@section('footer')
    {{-- <div class="customer-footer" style="position: fixed; bottom: 0;">
        <hr/>
        <div class="socials">
            @if(!empty(setting('social.facebook')))
                <div class="facebook">
                    <a href="{{ setting('social.facebook') }}" class="icon-social-facebook"></a>
                </div>
            @endif
            @if(!empty(setting('social.twitter')))
                <div class="twitter">
                    <a href="{{ setting('social.twitter') }}" class="icon-social-twitter"></a>
                </div>
            @endif
            @if(!empty(setting('social.instagram')))
                <div class="instagram">
                    <a href="{{ setting('social.instagram') }}" class="icon-social-instagram"></a>
                </div>
            @endif
        </div>
        <div class="d-flex justify-content-between align-items-center footer-wrapper"
             style="font-size: 13px; font-weight: 600;">
        <span class="p-3 pe-5 d-flex flex-column">
            <img class="stamp-logo sidebar-logo" src="{{ asset(setting('atol.stamp', '')) }}"/>
        </span>
            <span class="p-3 d-flex flex-column footer-client-details">
            <span>{{ setting('company.name', '') }}</span>
            <span>{{ Icon::email() }} {{ setting('company.contact.email', '') }}</span>
            <span>{{ Icon::phone() }} {{ setting('company.contact.phone', '') }}</span>
        </span>
        </div>
    </div> --}}
@endsection
