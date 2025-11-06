@extends('layout.customer')

@section('title', 'Balance & Payment')

@php
    $gateways = \Gateway::getDefaultGateway() !== null;
    use App\Models\Order\Invoice\Invoice;
    use Carbon\Carbon;
@endphp

@section('content')
<style>
.btn-success{ background-color: #00c851; border-radius: 40px !important;}
.btn-warning{ background-color: #fb3; border-radius: 40px !important;} 
</style>
    <div class="inner_content">
        <x-customer.overview-top-bar title="Finances" :search="false" />
        <div class="tours_list">
            <div class="upcoming_payments_finances">
                @php
                    $activeOrderCount = $orders->filter(function ($order) {
                            return $order->cache && ($order->cache->status->value != 0);
                        })->count();
                @endphp
                <h2>Upcoming Payments</h2>
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
                                            <!-- <h6 class="btn btn-warning">{{ $order->status->description() }}</h6> -->
                                             <h6 class="btn btn-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6>
                                            <h4>{{ $order->tour?->event?->name}}</h4>
                                            <!-- <h6>Lead Guest : {{$order->leadBooker->customer->first_name ?? '' . ' ' .$order->leadBooker->customer->last_name ?? ''}}</h6> -->
                                            <p class="calendar_date"><img src="{{ asset('images/customer/images/calendar.svg') }}" />{{ Carbon::parse($order->tour->date_from)->format('d M Y') }}  - {{ Carbon::parse($order->tour->date_to)->format('d M Y')}}</p>
                                            <h6 class="lead_guest">Lead Guest : {{ ($order->leadBooker->customer->first_name ?? '') . ' ' . ($order->leadBooker->customer->last_name ?? '') }}</h6>
                                            <!-- <h6>Booking Reference : {{ $order->booking_reference }}</h6> -->                                            
                                    </div> 
                                </div>
                            </div>
                            <div class="order_value_amount_div">
                                <h6>Booking Reference : {{$order->booking_reference}}</h6>
                                <div class="order_value_amount">
                                    <div class="total_order">
                                        <p>{{ fr_currency($order->total, $order->currency, false, 0) }}</p>
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
                            <table>
                                <tr>
                                    <td class="left-side">
                                        <!-- Left Side -->
                                        <table class="invoice_tbl" style="width: 100%;">
                                            <tr>
                                                <th>DUE DATE</th> 
                                                <th>INSTALLMENT</th>
                                                <th>AMOUNT</th> 
                                                <th>Status</th>
                                            </tr>
                                            @php
                                                $hrefdata = url('/customer/finances/invoice/' .  $order->booking_reference);
                                                //$invoice = Invoice::where('order_id',$order->id ?? '')->latest()->first();
                                                //$tddata  = '<td> INVOICE #'.optional($order->invoices->last())->invoice_number ?? '0'.' </td>';
                                                $tddata  ='';
                                                $img_url = asset('images/customer/images/download_icon.svg');
                                                //$tdinv   = '<td><p class="view_details"><a href="'.$hrefdata.'" target="_blank">VIEW INVOICE <img src="'.$img_url.'" /></a></p></td>';
                                                $tdinv ='';
                                            @endphp
                                            <tr>
                                            @if(($order->booking_fee ?? 0) > 0)
                                                    {!! $tddata !!}
                                                    <td data-content="Due By">With Order</td>
                                                    <td data-content="Type">Booking Fee</td>
                                                    <td data-content="Amount Due">{{ f_currency($order->booking_fee) }}</td>
                                                    <td data-content="Outstanding">
                                                        @php $amount = $order->booking_fee - min($order->paid, $order->booking_fee); @endphp
                                                        @if($amount <= 0)
                                                            <p class="paid">Paid</p> 
                                                        @else
                                                        <p class="unpaid badge btn-warning fw-bold overdue_btn">Un Paid</p>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                            @if(($order->deposit ?? 0) > 0)
                                                {!! $tddata !!}
                                                <td data-content="Due By">With Order</td>
                                                <td data-content="Type">Deposit</td>
                                                <td data-content="Amount Due">{{ f_currency($order->calculated_deposit) }}</td>
                                                <td data-content="Outstanding">
                                                    @php $amount = $order->calculated_deposit - min(($order->paid - ($order->booking_fee ?? 0)), $order->calculated_deposit); @endphp
                                                    @if($amount <= 0)
                                                        <p class="paid">Paid</p> 
                                                    @else
                                                        <p class="unpaid badge btn-warning fw-bold overdue_btn">Un Paid</p>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            @foreach($order->installments as $installment)
                                                {!! $tddata !!}
                                                <td data-content="Due By">{{ f_date($installment->due_on) }}</td>
                                                <td data-content="Type">Instalment</td>
                                                <td data-content="Amount Due">{{ f_currency($installment->calculated_amount) }}</td>
                                                <td data-content="Outstanding">
                                                    @php $amount = $installment->calculated_amount - $installment->repository->getAmountPaid(); @endphp
                                                    @if($amount <= 0)
                                                        <p class="paid">Paid</p> 
                                                    @else
                                                        <p class="unpaid badge btn-warning fw-bold overdue_btn">Un Paid</p>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                                {!! $tddata !!}
                                                <td data-content="Due By">{{ f_date($order->tour->final_payment) }}</td>
                                                <td data-content="Type">Remaining</td>
                                                <td data-content="Amount Due">{{ f_currency($order->remaining_installment) }}</td>
                                                <td data-content="Outstanding">
                                                    @php $amount = min($order->remaining, $order->remaining_installment); @endphp
                                                    @if($amount <= 0)
                                                        <p class="paid">Paid</p> 
                                                    @else
                                                        <p class="unpaid badge btn-warning fw-bold overdue_btn">Un Paid</p>
                                                    @endif
                                                </td>
                                            </tr>                                
                                        </table>
                                        <!-- End of Left Side --> 
                                    </td>
                                    <td class="right-side">
                                        @php
                                            $hrefdata = url('/customer/finances/invoice/' .  $order->booking_reference);
                                            $img_url = asset('images/customer/images/download_icon.svg');
                                            $tdinv   = '<p class="view_details"><a href="'.$hrefdata.'" target="_blank">VIEW INVOICE <img src="'.$img_url.'" /></a></p>';
                                        @endphp
                                        {!! $tdinv !!}
                                    </td>
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
