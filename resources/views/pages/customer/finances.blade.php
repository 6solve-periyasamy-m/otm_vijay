@extends('layout.customer')

@section('title', 'Balance & Payment')

@php
    $gateways = \Gateway::getDefaultGateway() !== null;
    use App\Models\Order\Invoice\Invoice;
    use Carbon\Carbon;
@endphp

@section('content')
   {{-- <div class="row payment-balance">
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
    </div> --}}
    <div class="inner_content">
        <x-customer.overview-top-bar title="Finances" :search="false" />
        <div class="tours_list">
            <div class="upcoming_payments_finances">
                @php
                    $activeOrderCount = $orders->filter(function ($order) {
                            return $order->cache && ($order->cache->status->value != 0);
                        })->count();
                @endphp
                <h2>Upcoming Payments <span class="tours_count">{{ $activeOrderCount }}</span></h2>
                 @foreach($orders as $k => $order)
                    <div class="finances_event">
                        <div class="event_payment_list">
                            <div class="event_detail_top">
                                <div class="event_image_title">
                                    @php 
                                   
                                        $pastdue = 0;
                                        if (!empty($order->tour->event->image_url)){
                                            $evenImg = $order->tour->event->image_url;
                                        } else{
                                            $evenImg = 'images/default_image.png';
                                        }
                                    @endphp
                                    <div class="event_img"><img src="{{ asset($evenImg) }}" alt="{{ $order->tour?->event?->name }}"/></div>
                                    <div class="title_date">
                                        @foreach($order->payments as $payment)
                                            @if (Carbon::parse($payment->paid_on)->isPast())
                                                @php $pastdue = 1 @endphp
                                            @endif
                                        @endforeach
                                            <h6 class="badge badge-{{ $order->status->color() }} fw-bold overdue_btn">{{ $order->status->description() }}</h6>
                                            <h6>Lead Guest : {{$order->leadBooker->customer->first_name ?? '' . ' ' .$order->leadBooker->customer->last_name ?? ''}}</h6>
                                            <!-- <h6>Booking Reference : {{ $order->booking_reference }}</h6> -->
                                            <h4>{{ $order->tour->name }}</h4>
                                            <p class="calendar_date"><img src="{{ asset('images/customer/images/calendar.svg') }}" />{{ Carbon::parse($order->tour->date_from)->format('d.m.Y') }}  - {{ Carbon::parse($order->tour->date_to)->format('d.m.Y')}}</p>
                                    </div> 
                                </div>
                            </div>
                            <div class="order_value_amount_div">
                                <h6>Booking Reference : KP12B7E4K</h6>
                                <div class="order_value_amount">
                                    <div class="total_order">
                                        <p>{{ f_currency($order->total) }}</p>
                                        <p>Total Order Value</p>
                                    </div>
                                    <div class="total_paid">
                                        @php
                                            $TotalPaid = $order->payments->sum('amount') ?? 0;
                                        @endphp
                                        <p>{{ f_currency($TotalPaid) }}</p>
                                        <p>Total Paid</p>
                                    </div>
                                    <div class="balance_outstanding">
                                        <p>{{ f_currency($order->remaining) }}</p>
                                        <p>Balance Outstanding</p>
                                    </div>
                                </div>
                            </div>                                            
                        </div>
                        <div class="event_invoice_details">
                            <table class="invoice_tbl">
                                <tr>
                                    <th>DUE DATE</th> 
                                    <th>STATUS</th>
                                    <th>AMOUNT</th> 
                                    <th>Type</th>
                                    <th></th>
                                </tr>
                                @php
                                    $hrefdata = url('/customer/finances/invoice/' .  $order->booking_reference);
                                    //$invoice = Invoice::where('order_id',$order->id ?? '')->latest()->first();
                                    //$tddata  = '<td> INVOICE #'.optional($order->invoices->last())->invoice_number ?? '0'.' </td>';
                                     $tddata  ='';
                                    $img_url = asset('images/customer/images/download_icon.svg');
                                    $tdinv   = '<td><p class="view_details"><a href="'.$hrefdata.'" target="_blank">VIEW INVOICE <img src="'.$img_url.'" /></a></p></td>';
                                @endphp
                                <tr>
                                @if(($order->booking_fee ?? 0) > 0)
                                        {!! $tddata !!}
                                        <td data-content="Due By" class="fw-bold">With Order</td>
                                        <td data-content="Type">Booking Fee</td>
                                        <td data-content="Amount Due">{{ f_currency($order->booking_fee) }}</td>
                                        <td data-content="Outstanding">
                                            @php $amount = $order->booking_fee - min($order->paid, $order->booking_fee); @endphp
                                            @if($amount <= 0)
                                                <p class="paid">Paid</p> 
                                            @else
                                                <p class="unpaid"> {{ f_currency($amount) }}</p>
                                            @endif
                                        </td>
                                        {!! $tdinv !!}
                                    </tr>
                                @endif
                                @if(($order->deposit ?? 0) > 0)
                                    {!! $tddata !!}
                                    <td data-content="Due By" class="fw-bold">With Order</td>
                                    <td data-content="Type">Deposit</td>
                                    <td data-content="Amount Due">{{ f_currency($order->calculated_deposit) }}</td>
                                    <td data-content="Outstanding">
                                        @php $amount = $order->calculated_deposit - min(($order->paid - ($order->booking_fee ?? 0)), $order->calculated_deposit); @endphp
                                        @if($amount <= 0)
                                            <p class="paid">Paid</p> 
                                        @else
                                            <p class="unpaid">{{ f_currency($amount) }}</p>  
                                        @endif
                                    </td>
                                    {!! $tdinv !!}
                                </tr>
                                @endif
                                @foreach($order->installments as $installment)
                                    {!! $tddata !!}
                                    <td data-content="Due By" class="fw-bold">{{ f_date($installment->due_on) }}</td>
                                    <td data-content="Type">Instalment</td>
                                    <td data-content="Amount Due">{{ f_currency($installment->calculated_amount) }}</td>
                                    <td data-content="Outstanding">
                                        @php $amount = $installment->calculated_amount - $installment->repository->getAmountPaid(); @endphp
                                        @if($amount <= 0)
                                            <p class="paid">Paid</p> 
                                        @else
                                            <p class="unpaid">{{ f_currency($amount) }}</p>
                                        @endif
                                    </td>
                                    {!! $tdinv !!}
                                </tr>
                                @endforeach
                                    {!! $tddata !!}
                                    <td data-content="Due By" class="fw-bold">{{ f_date($order->tour->final_payment) }}</td>
                                    <td data-content="Type">Remaining</td>
                                    <td data-content="Amount Due">{{ f_currency($order->remaining_installment) }}</td>
                                    <td data-content="Outstanding">
                                        @php $amount = min($order->remaining, $order->remaining_installment); @endphp
                                        @if($amount <= 0)
                                            <p class="paid">Paid</p> 
                                        @else
                                            <p class="unpadi">{{ f_currency($amount) }}</p>
                                        @endif
                                    </td>
                                    {!! $tdinv !!}
                                </tr>                                
                            </table>
                        </div>
                    </div>
                @endforeach
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
