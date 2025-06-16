@extends('layout.customer')

@section('title', 'Balance & Payment')

@php
    $gateways = \Gateway::getDefaultGateway() !== null;
@endphp

@section('content')
    <div class="row payment-balance">
        <div class="col-12">
            <form class="form-horizontal mx-2">
                <div class="form-group finances-select-wrapper">
                    <p class="mb-0  heading">Select Order</p>
                    <select class="form-select order-select" onchange="onOrderChange();" id="booking_reference">
                        @foreach($orders as $selector)
                            <option value='{{ $selector->booking_reference }}'>{{ $selector->tour->name }}
                                ({{ $selector->booking_reference }})
                                @if($selector->cancelled)
                                    (Cancelled)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <a href="#" target="_blank" class="invoice btn btn-primary">View Invoice</a>
                </div>
            </form>
        </div>
        @foreach($orders as $selector)
            @include('partials.customer.finances.block', ['order' => $selector,])
        @endforeach
        @if($gateways)
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <p class="heading">Make Payment</p>
                            <div class="col-md-12">
                                <form class="form-material" action="{{ route('customer.payment.make') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="booking_reference" id="form-booking-reference">
                                    <div class="row">
                                        <x-customer.input name="amount" :width="10">
                                            Enter Amount
                                        </x-customer.input>
                                        <div class="col-12 col-xl-2 d-flex justify-content-center align-items-center">
                                            <button type="submit" class="btn btn-primary">Make Payment</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <p class="heading">This operator has not enabled online payments</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="inner_content">
        <div class="overview_top_bar">
            <p class="overview_title">Finances </p>
            <!-- <div class="search_field"><p><input type="text" placeholder="SEARCH"></p></div> -->
        </div>
        <div class="tours_list">
            <div class="upcoming_payments_finances">
                <h2>Upcoming Payments <span class="tours_count">2</span></h2>
                <div class="finances_event">
                    <div class="event_payment_list">
                        <div class="event_detail_top">
                            <div class="event_image_title">
                                <div class="event_img"><img src="/images/customer/images/royal_ascot.png" alt="royal img"/></div>
                                <div class="title_date">
                                    <button class="overdue_btn">OVERDUE</button>
                                    <h4>Royal Ascot African Ladies 2024</h4>
                                    <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                </div> 
                            </div>
                        </div>
                        <div class="order_value_amount">
                            <div class="total_order">
                                <p>$15,000.00</p>
                                <p>Total Order Value</p>
                            </div>
                            <div class="total_paid">
                                <p>$5,000.00</p>
                                <p>Total Paid</p>
                            </div>
                            <div class="balance_outstanding">
                                <p>$10,000.00</p>
                                <p>Balance Outstanding</p>
                            </div>
                        </div>
                  
                    </div>
                    <div class="event_invoice_details">
                        <table class="invoice_tbl">
                            <tr>
                              <th>INVOICE NO.</th>
                              <th>DUE DATE</th>  
                              <th>AMOUNT</th>
                              <th>STATUS</th>
                              <th></th>
                            </tr>
                            <tr>
                              <td>INV-00123</td>
                              <td>02.02.2023</td>
                              <td>$5,000</td>
                              <td><button class="overdue">OVERDUE</button></td>
                              <td><p class="view_details"><a href="">VIEW INVOICE <img src="/images/customer/images/arrow_right.svg" /></a></p></td>
                            </tr>
                            <tr>
                                <td>INV-00123</td>
                                <td>02.02.2023</td>
                                <td>$5,000</td>
                                <td><button class="notpaid">NOT PAID</button></td>
                                <td><p class="view_details"><a href="">VIEW INVOICE <img src="/images/customer/images/arrow_right.svg" /></a></p></td>
                            </tr>
                            <tr>
                                <td>INV-00123</td>
                                <td>02.02.2023</td>
                                <td>$5,000</td>
                                <td><button class="notpaid">NOT PAID</button></td>
                                <td><p class="view_details"><a href="">VIEW INVOICE <img src="/images/customer/images/arrow_right.svg" /></a></p></td>
                            </tr>
                          </table>
                    </div>
                </div>
                <div class="finances_event">
                    <div class="event_payment_list">
                        <div class="event_detail_top">
                            <div class="event_image_title">
                                <div class="event_img"><img src="/images/customer/images/royal_ascot.png" alt="royal img"/></div>
                                <div class="title_date">
                                    <button class="overdue_btn">OVERDUE</button>
                                    <h4>Event 02</h4>
                                    <p class="calendar_date"><img src="/images/customer/images/calendar.svg" />02.02.2023 - 08.08.2024</p>
                                </div> 
                            </div>
                        </div>
                        <div class="order_value_amount">
                            <div class="total_order">
                                <p>$15,000.00</p>
                                <p>Total Order Value</p>
                            </div>
                            <div class="total_paid">
                                <p>$5,000.00</p>
                                <p>Total Paid</p>
                            </div>
                            <div class="balance_outstanding">
                                <p>$10,000.00</p>
                                <p>Balance Outstanding</p>
                            </div>
                        </div>
                  
                    </div>
                    <div class="event_invoice_details">
                        <table class="invoice_tbl">
                            <tr>
                              <th>INVOICE NO.</th>
                              <th>DUE DATE</th>  
                              <th>AMOUNT</th>
                              <th>STATUS</th>
                              <th></th>
                            </tr>
                            <tr>
                              <td>INV-00123</td>
                              <td>02.02.2023</td>
                              <td>$5,000</td>
                              <td><button class="paid">PAID</button></td>
                              <td><p class="view_details"><a href="">VIEW INVOICE <img src="/images/customer/images/arrow_right.svg" /></a></p></td>
                            </tr>
                            <tr>
                                <td>INV-00123</td>
                                <td>02.02.2023</td>
                                <td>$5,000</td>
                                <td><button class="notpaid">NOT PAID</button></td>
                                <td><p class="view_details"><a href="">VIEW INVOICE <img src="/images/customer/images/arrow_right.svg" /></a></p></td>
                            </tr>
                            <tr>
                                <td>INV-00123</td>
                                <td>02.02.2023</td>
                                <td>$5,000</td>
                                <td><button class="notpaid">NOT PAID</button></td>
                                <td><p class="view_details"><a href="">VIEW INVOICE <img src="/images/customer/images/arrow_right.svg" /></a></p></td>
                            </tr>
                          </table>

                    </div>
                </div>
                
                
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    <script>
        let route = "{{ route('customer.invoice', ['reference' => 'reference']) }}"

        function onOrderChange() {
            let newBooking = $('.order-select').val()
            $('.order').hide();
            $('.order-' + newBooking).show();
            $('#form-booking-reference').val(newBooking);
            $('.invoice').prop('href', route.replace('reference', newBooking));
        }

        onOrderChange();
    </script>
@endsection
