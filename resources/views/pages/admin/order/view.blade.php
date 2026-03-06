@php
/**
* @param \App\Models\Order\Order $order;
*/
/** @var bool $nonSystem Is the order using a non-system currency */
$nonSystem = $order->currency !== null && $order->currency !== Settings::currency();
/** @var float|null $fromSystem conversion rate from system currency */
$fromSystem = \Settings::getConversionRate(\Settings::currency(), $order->currency);
/** @var float|null $toSystem conversion rate to system currency */
$toSystem = \Settings::getConversionRate($order->currency, \Settings::currency());
@endphp

@extends('layout.master')

@section('title', __('View Order') . ($order->tour->event->name ? ' - ' . $order->tour->event->name : ''))

{{-- TODO: Tidy up CSS --}}

@section('footer-script')
<script type="text/javascript">
    function resend() {
        hideOverlay('.panel-overlay')
        $.post('{{ route('api.order.resend.booking-confirmation') }}', {
            '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
            'order': {{ $order->id }},
        }).done(function (xhr, textStatus, errorThrown) {
            console.log(xhr);
            if (xhr.success) {
                showToast('Resent Successfully', xhr.message, 'success');
            } else {
                showToast('Resend Failed', xhr.message, 'danger');
            }
        }).fail(function (xhr, textStatus, errorThrown) {
            showToast('Resend Failed', xhr.responseText, 'danger');
        });
    }
    function updateInvoice(selector) {
        let url = "{{ route('orders.invoice', ['order' => $order, 'version' => '#replace#']) }}"
        $('.invoice-button').prop('href', url.replace('#replace#', $(selector).val()))
    }
    function showPopupModal() {
        return openModal('admin.order.controls', {'order': {{$order->id}},});
    }
</script>
<style>
    .view_order_row {
        display: flex;
    }

    .view_order_colone {
        width: 74.22%;
        margin-right: 0.79%;
    }

    .view_order_coltwo {
        width: 24.99%;
    }

    .otm-view-cust-top-sec {
        display: flex;
    }

    .view_order_row .view-cust-name {
        background-color: #295F92;
        border-radius: 16px;
        color: #ffffff;
        padding: 15px;
        box-shadow: 0px 4px 4px 0px #67676740;
        margin-bottom: 11px;
        width: 25%;
        margin-right: 0.84%;
    }

    .view_order_row .blue_div_row {
        display: flex;
        padding-bottom: 16px;
        padding-bottom: unset;

    }

    .view_order_row .left_blue_section {
        padding: 14px;
        width: 49.4%;
        margin-right: 0.84%;
        background-color: #A3CAEE;
        border-radius: 11px;
        box-shadow: 0px 4px 4px 0px #67676740;
        display: flex;
        flex-direction: column;
        justify-content: space-between;

    }

    .view_order_row .view-cust-mail-addr {
        width: 25%;
        margin-right: 0.84%;
        background-color: #295F92;
        border-radius: 16px;
        color: #ffffff;
        padding: 15px;
        box-shadow: 0px 4px 4px 0px #67676740;
        margin-bottom: 11px;
    }

    .view_order_row .view-cust-phn-numb {
        width: 25%;
        margin-right: 0.84%;
        background-color: #295F92;
        border-radius: 16px;
        color: #ffffff;
        padding: 15px;
        box-shadow: 0px 4px 4px 0px #67676740;
        margin-bottom: 11px;
    }

    .view_order_row .view-cust-organizaton {
        width: 25%;
        background-color: #295F92;
        border-radius: 16px;
        color: #ffffff;
        padding: 15px;
        box-shadow: 0px 4px 4px 0px #67676740;
        margin-bottom: 11px;
    }

    .view_order_coltwo .container {
        background: linear-gradient(90.05deg, #FFFFFF 0.05%, #33B5E5 437%);
        border: 1.94px solid #A3CAEE;
        box-shadow: 0px 3.88px 3.88px -2px #67676740;
        border-radius: 10px;
        padding: 8px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .view_order_coltwo .container .button_row_class:last-child {
        margin-bottom: unset !important;
    }

    .view_order_row .right_blue_section {
        width: 49.76%;
        background-color: #A3CAEE;
        padding: 14px 20px;
        border-radius: 11px;
        box-shadow: 0px 4px 4px 0px #67676740;
        justify-content: space-between;

    }

    /* .left_blue_section .col-md-6{
    width: 49%;
        word-break: break-word;
        overflow-wrap: anywhere;
    } */
    .left_blue_section .single_div_inner {
        /* display: flex;
        gap: 20px;
        justify-content: space-between; */
    }

    .single_div_right .single_div{
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 5px;
    }
    .single_div_left .single_div {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 5px;
        width: 47%;
    }

    .single_div_one {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 5px;
    }

    .single_div_two {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 5px;
    }

    .single_div_three {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .single_div_four {
        display: flex;
        align-items: center;
        margin-top: 50px;
        gap: 5px;
    }

    .single_div_three h6,
    .single_div_two h6,
    .single_div_one h6,
    .single_div_three p,
    .single_div_two p,
    .single_div_one p {
        margin-bottom: unset;
    }

    .view-cust-organizaton p,
    .view-cust-organizaton h6,
    .view-cust-mail-addr p,
    .view-cust-mail-addr h6,
    .view-cust-phn-numb p,
    .view-cust-phn-numb h6,
    .view-cust-name p,
    .view-cust-name h6 {
        margin-bottom: unset;
    }

    .single_div_right .single_div_two {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 5px;
    }

    .single_div_right .single_div_three {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .single_div_right .single_div_four {
        display: flex;
        align-items: center;
        margin-top: 50px;
        gap: 5px;
    }

    .single_div_right .single_div_one {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 5px;
    }

    .right_blue_section .single_div_inner {
        display: flex;
        gap: 20px;
        justify-content: space-between;
    }

    .single_div.button_div {
        display: flex;
        gap: 7px;
    }

    .right_blue_section .single_div_right .single_div {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 5px;
    }

    .right_blue_section .single_div_left .single_div,
    .right_blue_section .single_div_right .single_div {
        margin-bottom: unset;
    }

    .button_div .view_tour {
        color: #000000;
        display: inline-flex;
        align-items: center;
        background-color: #33B5E5;
        padding: 9px 8px;
        gap: 5px;
        font-size: 16px;
    }

    .button_div .edit_occupancy {
        color: #000000;
        display: inline-flex;
        align-items: center;
        background-color: #4285F4;
        padding: 9px 8px;
        gap: 5px;
        font-size: 16px;
    }

    .button_div .change_tour {
        color: #000000;
        display: inline-flex;
        align-items: center;
        background-color: #FFBB33;
        padding: 9px 8px;
        gap: 5px;
        font-size: 16px;
    }

    .button_row_class {
        gap: 8px;
        display: flex;
        margin-bottom: 9px;
        flex-wrap: wrap;
    }

    .button_row_class div a {
        width: 100px;
        height: 100px;
    }

    .button_row_class div .icon div {
        width: 16px;
        height: 16px;
        font-size: 16px;
    }

    .new_class_mod .otm-view-cust-top-sec {
        gap: unset !important;
    }

    .left_blue_section .single_div_inner .single_div_left{
           width: 100%;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    
    .left_blue_section .single_div_inner .single_div_right {
        width: 49%;
        word-break: break-word;
        overflow-wrap: anywhere;
    }



    .right_blue_section .single_div_right .single_div,
    .right_blue_section .single_div_left .single_div {
        margin-bottom: 25px;
    }

    .right_blue_section .single_div_right .single_div p,
    .right_blue_section .single_div_right .single_div h6,
    .right_blue_section .single_div_left .single_div p,
    .right_blue_section .single_div_left .single_div h6 {
        margin-bottom: unset !important;
    }

    .single_div p,
    .single_div h6 {
        margin-bottom: unset !important;
    }
    .single_div_left{
        display: flex;
        flex-wrap: wrap;
        align-items: baseline
    }
</style>
@endsection

@section('content')
@include('partials.admin.order.popup')
{{-- Header Details --}}


<div class="view_order_row">

    <div class="view_order_colone">
        <div class="new_class_mod">
            <div class="otm-view-cust-top-sec">

                <div class="view-cust-name">
                    <p>Booking Reference</p>
                    <h6 class="fw-bold"> {{ $order->booking_reference }}</h6>
                </div>
                <div class="view-cust-phn-numb">
                    <p>Lead Booker</p>
                    <h6 class="fw-bold">
                        {{ $order->leadBooker?->customer_name ?? 'N/A' }}
                    </h6>
                </div>
                <div class="view-cust-mail-addr">
                     <p class="text-nowrap">Consultant</p>
                    <h6 class="fw-bold">
                        {{ $order->consultant
                        ? $order->consultant->name 
                        : 'No Consultant Set'
                        }}
                    </h6>
                </div>
                <div class="view-cust-organizaton">
                    <p>Order Status</p>
                    <h6 class="fw-bold">
                        <h6 class="badge badge-{{ $order->status->color() }} fw-bold" style="margin-bottom:unset;">{{
                            $order->status->description() }}</h6>
                    </h6>
                </div>
            </div>
        </div>

        <div class="blue_div_row">

            <div class="left_blue_section">
                <div class="single_div_inner">
                    <div class="row">
                        <div class="col-md-6 single_div_left">
                            <div class="single_div">
                                <p>Tour Name:</p>
                                <h6 class="fw-bold">
                                    {{ $order->tour?->name ?? 'Tour Deleted' }}
                                </h6>
                            </div>
                            <div class="single_div mt-4">
                               <p></p>
                                <h6 class="fw-bold">
                                    
                                </h6>
                            </div>
                            <div class="single_div">
                                <p>Tour Date:</p>
                                <h6 class="fw-bold">
                                    @if($order->tour)
                                    {{ f_date($order->tour->date_from) }} to {{ f_date($order->tour->date_to) }}
                                    @else
                                    Tour Deleted
                                    @endif
                                </h6>
                            </div>
                             <div class="single_div">
                                <p>Order Created Date:</p>
                                <h6 class="fw-bold">
                                    {{ f_date($order->ordered_on) }}
                                </h6>
                            </div>
                            <div class="single_div">
                               <p>Organization:</p>
                               <h6 class="fw-bold">{!! $order->organization->name ?? '' !!}</h6>
                            </div>
                            <div class="single_div">
                                <p>Agent:</p>
                                <h6 class="fw-bold">
                                    {{ $order->agent?->first_name 
                                        ? $order->agent->first_name . ' ' . $order->agent->last_name 
                                        : '' }}
                                </h6>
                            </div>
                            <div class=" ">
                                <p class="text-nowrap">Booking Token:</p>
                               <h6 class="fw-bold">{!! $order->token ?? '' !!}</h6>
                            </div>
                            
                            </div>
                       
                    </div>
                </div>
                <div class="row text-break">
                    <div class="col-md-6 mb-0">
                        <span>Internal Notes:</span>
                        <h6 class="fw-bold">{!! nl2br($order->internal_notes ?? 'No Internal Notes') !!}</h6>
                    </div>
                    <div class="col-md-6">
                        <span>External Notes:</span>
                        <h6 class="fw-bold mb-1">{!! nl2br($order->external_notes ?? 'No External Notes') !!}</h6>

                    </div>
                </div>
            </div>
            <div class="right_blue_section d-flex  flex-column">
                <div >
                    <div class="single_div_left">
                        <div class="single_div">
                            <p>Order Value:</p>
                            <h6 class="fw-bold">
                                @if($order->cancelled)
                                {{ fr_currency($order->total, $order->currency) }}
                                @if($nonSystem) ({{ fr_currency($order->total * ($toSystem ?? 1), Settings::currency())
                                }}) @endif
                                ({{ fr_currency($order->cost, $order->currency) }}
                                @if($nonSystem) ({{ fr_currency($order->cost * ($toSystem ?? 1), Settings::currency())
                                }}) @endif
                                before cancellation)
                                @else
                                {{ fr_currency($order->total, $order->currency, false, 0) }} @if($nonSystem) ({{
                                fr_currency($order->total * ($toSystem ?? 1), Settings::currency()) }}) @endif
                                @if ($order->repository->getBeforeString() !== null)
                                ({{ $order->repository->getBeforeString() }})
                                @endif
                                @endif
                            </h6>
                        </div>
                        <div class="single_div">
                            <p style="margin-bottom:unset;">Cost to Company:</p>
                            <h6 class="fw-bold">
                                {{ fr_currency($order->repository->getCostToCompany() * ($fromSystem ?? 1),
                                $order->currency) }}
                                @if($nonSystem)
                                @if($fromSystem === null)
                                No FX Rate for Conversion
                                @else
                                ({{ f_currency($order->repository->getCostToCompany())}})
                                @endif
                                @endif
                                @if ($order->repository->getCostBeforeString() !== null)
                                ({{ $order->repository->getCostBeforeString() }})
                                @endif
                            </h6>
                        </div>
                        <div class="single_div">
                            <p>Total Paid:</p>
                            <h6 class="fw-bold">{{ fr_currency($order->paid, $order->currency) }} @if($nonSystem) ({{
                                fr_currency($order->paid * ($toSystem ?? 1), Settings::currency()) }}) @endif</h6>
                        </div>
                         <div class="single_div">
                            <p style="margin-bottom:unset;">Current Profit:</p>
                            <h6 class="fw-bold">
                                @if($order->cache->profit !== null)
                                {{ fr_currency($order->cache->profit * ($fromSystem ?? 1), $order->currency) }}
                                @if($nonSystem) ({{ f_currency($order->cache->profit)}}) @endif
                                @else
                                No FX Rate for Conversion
                                @endif
                            </h6>
                        </div>
                        <div class="single_div">
                            <p>Next Payment Due:</p>
                            <h6 class="fw-bold">
                                @if($order->next_installment !== null)
                                {{f_date($order->next_installment->due_on)}} -
                                {{fr_currency($order->next_installment->remaining, $order->currency)}}
                                @if($nonSystem) ({{ fr_currency($order->next_installment->remaining * ($toSystem ?? 1),
                                Settings::currency()) }}) @endif
                                @else
                                All installments paid
                                @endif
                            </h6>
                        </div>
                         <div class="single_div">
                            <p style="margin-bottom:unset;">Tax Amount:</p>
                            <h6 class="fw-bold text-break">
                                @if($order->getTaxes() !== null)
                                {{fr_currency($order->getTaxes(), $order->currency)}}
                                @if($nonSystem) ({{ fr_currency($order->getTaxes() * ($toSystem ?? 1),
                                Settings::currency()) }}) @endif
                                @else
                                No Taxes Due
                                @endif
                            </h6>
                        </div>
                        <div class="single_div">
                            <p>Balance Outstanding:</p>
                            <h6 class="fw-bold text-primary">{{ fr_currency($order->remaining, $order->currency) }} @if($nonSystem)
                                ({{ fr_currency($order->remaining * ($toSystem ?? 1), Settings::currency()) }}) @endif
                            </h6>
                        </div>
                        <div class="single_div">
                            <p style="margin-bottom:unset;">Commission:</p>
                            <h6 class="fw-bold">
                                @if($order->commission !== null)
                                    {{ fr_currency($order->commission_amount, $order->currency) }} ({{ $order->commission }}%)
                                @else
                                    -
                                @endif
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 flex-wrap mt-3 button_div">
                    <a href="{{ route('orders.edit', ['order' => $order,]) }}" class="btn btn-success">
                        <div class="d-inline-flex align-content-center justify-content-center">
                            <i class="fas fa-edit"></i>
                        </div>
                        Edit Order
                    </a>
                    <a class="view_tour" href="{{ route('tours.view', ['tour' => $order->tour_id]) }}">
                        <div class="icon">
                            <div class="d-inline-flex align-content-center justify-content-center">
                                <i class="fas fa-globe"></i>

                            </div>



                        </div>
                        <div class="text">
                            View Tour

                        </div>
                    </a>
                    <a class="edit_occupancy" href="{{ route('orders.occupancy', ['order' => $order,]) }}">
                        <div class="icon">

                            <div class="d-inline-flex align-content-center justify-content-center">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>
                        <div class="text">
                            Edit Occupancy
                        </div>
                    </a>
                    <a class="change_tour" href="{{ route('orders.migrate', ['order' => $order,]) }}">
                        <div class="icon">
                            <div class="d-inline-flex align-content-center justify-content-center">
                                <i class="fas fa-edit"></i>

                            </div>
                        </div>
                        <div class="text">
                            Change Tour
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </div>
    <div class="view_order_coltwo d-flex flex-colum">

        <div class="container">

            <!-- Row 1 -->
            <div class="button_row_class">
                <div class="">
                    <a class="popup-grid-item color-info row-1"
                        href="{{ route('orders.reservation', ['order' => $order,]) }}" target="_blank">
                        <div class="icon">
                            <div class="d-inline-flex align-content-center justify-content-center">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <div class="text">
                            View Reservation Document
                        </div>
                    </a>
                </div>

                <div class="">
                    <a class="popup-grid-item color-info row-1"
                        href="{{ route('orders.itinerary', ['order' => $order,]) }}" target="_blank">
                        <div class="icon">
                            <div class="d-inline-flex align-content-center justify-content-center">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <div class="text">
                            View Itinerary Document
                        </div>
                    </a>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="button_row_class">
                <div class="">
                    <a class="popup-grid-item color-mint row-4" href="#"
                        onclick="event.preventDefault(); if(confirm('Are you sure you want to send the Reservation document to email?')) { Livewire.emit('sendReservationToEmail'); }">
                        <div class="icon">
                            <div class="d-inline-flex align-content-center justify-content-center">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="text">
                            Send Reservation Document
                        </div>
                    </a>
                </div>

                <div class="">
                    <a class="popup-grid-item color-mint row-4" href="#"
                        onclick="event.preventDefault(); if(confirm('Are you sure you want to send the booking confirmation email?')) { Livewire.emit('sendBookingConfirmation'); }">
                        <div class="icon">
                            <div class="d-inline-flex align-content-center justify-content-center">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="text">
                            Send Booking Confirmation
                        </div>
                    </a>
                </div>

                <div class="">
                    <a class="popup-grid-item color-mint row-4" href="#"
                        wire:click.prevent="openPopupEmailForm('itinerary')">
                        <div class="icon">
                            <div class="d-inline-flex align-content-center justify-content-center">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="text">
                            Send Itinerary Document
                        </div>
                    </a>
                </div>

                <div class="">
                    <a class="popup-grid-item color-mint row-4" href="#"
                        wire:click.prevent="openPopupEmailForm('itinerary')">
                        <div class="icon">
                            <div class="d-inline-flex align-content-center justify-content-center">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="text">
                            Send Final Payment Overdue Mail
                        </div>
                    </a>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="button_row_class">
                <div class="">
                    <a class="popup-grid-item color-danger row-5" href="#" onclick="$('#order-delete').submit()">
                        <div class="icon">
                            <div class="d-inline-flex align-content-center justify-content-center">
                                <i class="fas fa-trash-alt"></i>
                            </div>
                        </div>
                        <div class="text">
                            Cancel Order
                        </div>
                    </a>
                </div>
            </div>

        </div>




    </div>

</div>



<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;">

{{-- Order Overview and Customers Section --}}
<div class="heading pt-2 pb-md-3 pb-2">
    <h2 class="fw-bold" style="margin-bottom: unset !important;">Customers</h2>
    <div class="py-2 mb-3 text-end" style="margin-bottom: unset !important;">
        <a href="{{ route('order-customers.create', ['order' => $order, ]) }}" class="btn btn-primary text-white">
            {{ Icon::create() }}
            <span>Add Customer</span>
        </a>
    </div>
</div>
<div class="card" id="section-1">
    <div class="card-body">
        @can('create', \App\Models\Order\OrderCustomer::class)

        @endcan
        <div class="row">
            @php //dd($orderCustomerFields); @endphp
            @foreach($order->orderCustomers as $key => $ordersCustomer)
            @php
            $isTbcCustomer = (strpos($ordersCustomer->customer->first_name, 'Unknown') !== false ||
            strpos($ordersCustomer->customer->last_name, 'Unknown') !== false);
            $customerName = $isTbcCustomer ? "TBC". $key. " - Paying - " . $ordersCustomer->customer->last_name :
            $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name;
            @endphp
            <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                <div class="otm-card">
                    <p class="d-flex justify-content-between align-items-center">
                        <span>
                            {{ ($order->lead_booker_id == $ordersCustomer->id) ? 'Lead Booker' : 'Additional
                            Customer'
                            }}
                        </span>
                        <span>
                            <i class="fas fa-eye text-primary cursor-pointer" data-order-id="{{ $order->id }}"
                                data-customer-id="{{ $ordersCustomer->id }}" data-customer-name="{{ $customerName }}"
                                data-customer-type="{{ ($order->lead_booker_id == $ordersCustomer->id) ? 'Lead Booker' : 'Additional Customer' }}"
                                data-bs-toggle="modal" data-bs-target="#customerDetailsModal"
                                onclick="loadCustomerComponents.call(this)">
                            </i>
                        </span>
                    </p>
                    @if(in_array('first_name', $orderCustomerFields) || in_array('last_name', $orderCustomerFields))
                    <h6 class="fw-bold">
                        @can('read', \App\Models\Order\OrderCustomer::class)
                        <a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $ordersCustomer]) }}"
                            class="link-info">
                            {{ $ordersCustomer->customer->first_name . ' ' . $ordersCustomer->customer->last_name }}
                        </a>
                        @else
                        {{ $ordersCustomer->customer->first_name . ' ' . $ordersCustomer->customer->last_name }}
                        @endcan
                    </h6>
                    @endif
                    @if(in_array('date_of_birth', $orderCustomerFields))
                    <p>Born</p>
                    <h6 class="fw-bold">{{ isset($ordersCustomer->customer->date_of_birth) ?
                        f_date($ordersCustomer->customer->date_of_birth) : 'Date of Birth not set' }}</h6>
                    @endif
                    @if(in_array('email', $orderCustomerFields))
                    <p>Email</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->email_address ?? 'Not Set' }}</h6>
                    @endif
                    @if(in_array('mobile_number', $orderCustomerFields))
                    <p>Phone Number</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->mobile_number ?? 'Not Set' }}</h6>
                    @endif
                    @if(in_array('policy_number', $orderCustomerFields))
                    <p>Insurance Policy</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->policy_number ?? 'No Insurance Policy' }}</h6>
                    @endif

                    @if(in_array('home_address', $orderCustomerFields))
                    <p>Home Address</p>
                    <h6 class="fw-bold">
                        {{ $ordersCustomer->customer->homeAddress->address_line_1 }}
                        {{ $ordersCustomer->customer->homeAddress->region }}
                        {{ $ordersCustomer->customer->homeAddress->country }}
                        {{ $ordersCustomer->customer->homeAddress->postcode }}
                    </h6>
                    @endif

                    @if(in_array('billing_address', $orderCustomerFields))
                    <p>Billing Address</p>
                    <h6 class="fw-bold">
                        {{ $ordersCustomer->customer->billingAddress->address_line_1 }}
                        {{ $ordersCustomer->customer->billingAddress->region }}
                        {{ $ordersCustomer->customer->billingAddress->country }}
                        {{ $ordersCustomer->customer->billingAddress->postcode }}
                    </h6>
                    @endif

                    @if(in_array('passport_first_name', $orderCustomerFields) || in_array('passport_middle_names',
                    $orderCustomerFields) || in_array('passport_last_name', $orderCustomerFields))
                    <p>Passport Name</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->passport_first_name . ' ' .
                        $ordersCustomer->customer->passport_middle_names . ' ' .
                        $ordersCustomer->customer->passport_last_name ?? 'Not Set' }}</h6>
                    @endif
                    @if(in_array('passport_number', $orderCustomerFields))
                    <p>Passport Number</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->passport_number ?? 'Not Set' }}</h6>
                    @endif
                    @if(in_array('passport_expiry_date', $orderCustomerFields))
                    <p>Passport Expires</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->passport_expiry_date ?? 'Not Set' }}</h6>
                    @endif

                    @if(in_array('emergency_contact_name', $orderCustomerFields))
                    <p>Contact Name (Emergency)</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->emergency_contact_name ?? 'Not set' }}</h6>
                    @endif
                    @if(in_array('emergency_contact_relationship', $orderCustomerFields))
                    <p>Contact Relationship (Emergency)</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->emergency_contact_relationship ?? 'Not set' }}
                    </h6>
                    @endif
                    @if(in_array('emergency_contact_telephone', $orderCustomerFields))
                    <p>Contact telephone (Emergency)</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->emergency_contact_telephone ?? 'Not set' }}
                    </h6>
                    @endif
                    @if(in_array('loyalty_number', $orderCustomerFields))
                    <p>Loyalty Number</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->loyalty_number ?? 'Not set' }}</h6>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;">

@include('partials.admin.order.order-components', ['order' => $order])

<div class="heading pt-2 pb-md-3 pb-2">
    <h2 class="fw-bold">Billing & Payments</h2>
</div>

<div id="billing-section">
    {{-- Payments Table--}}
    <div class="row">
        <div class="col-xl-6" id="payments-section">
            <x-admin.section.card>
                <x-slot:title>
                    Payments
                </x-slot:title>
                <div class="pb-3 row">
                    <div class="col-7">
                        <select class="form-select" onchange="updateInvoice(this)">
                            <option value="latest" selected>Latest</option>
                            @foreach($order->invoices as $invoice)
                            <option value="{{ $invoice->invoice_number }}">Invoice #{{$invoice->invoice_number}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <a href="{{ route('orders.invoice', ['order' => $order,]) }}" target="_blank"
                            class="invoice-button btn btn-primary text-white mb-1">View Invoice</a>
                    </div>
                    @can('create', \App\Models\Order\Payment\Payment::class)
                    <div class="col-3">
                        <a href="{{ route('payments.create', ['order' => $order, ]) }}"
                            class="btn btn-success text-white mb-1">
                            {{ Icon::create() }}
                            New Payment
                        </a>
                    </div>
                    @endcan
                </div>
                <div class="pt-1">
                    <table class="datatable table" id="payment-table" data-ordering="false">
                        <thead>
                            <tr>
                                <th scope="col">Type</th>
                                <th scope="col">Method</th>
                                <th scope="col">Payer</th>
                                <th scope="col">Total</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Paid</th>
                                <th scope="col">Notes</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        @foreach($order->payments as $index => $payment)
                        @php
                        $row_class = $index % 2 == 0 ? 'odd-row' : 'even-row';
                        @endphp
                        <tr class="{{ $row_class }}">
                            <td>{{ $payment->payment_type }}</td>
                            <td>{{ $payment->paymentMethod->name }}</td>
                            <td>{{ $payment->payer_name ?? "No Customer Found" }}</td>
                            <td>
                                @if($payment->payment_fee !== null)
                                <abbr
                                    title="{{ fr_currency($payment->totalWithFee(), $payment->currency) }} with payment fee">{{
                                    fr_currency($payment->amount, $payment->currency) }}</abbr>
                                @else
                                {{ fr_currency($payment->amount, $payment->currency) }}
                                @endif
                            <td>{{ fr_currency($payment->amount, $payment->currency) }}</td>
                            <td>{{ f_datetime($payment->paid_on) }}</td>
                            @php
                            $internalNotes = $payment->internal_notes ?? " -Nil- ";
                            $fee = $payment->payment_fee ? fr_currency($payment->payment_fee, $payment->currency) :
                            null;
                            @endphp

                            <td class="w-15 align-top text-justify">
                                {{ $internalNotes }}
                            </td>

                            <td class="actions align-middle">
                                @can('update', \App\Models\Order\Payment\Payment::class)
                                <a href="{{ route('payments.edit', ['order' => $order, 'payment' => $payment,]) }}"
                                    class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                @else
                                <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                    {{ Icon::edit() }}
                                </span>
                                @endcan
                                @can('delete', \App\Models\Order\Payment\Payment::class)
                                <a href="#" onclick="$('#payment-{{$payment->id}}-delete').submit()"
                                    class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete()
                                    }}</a>
                                <form
                                    action="{{ route('payments.delete', ['order' => $order, 'payment' => $payment,]) }}"
                                    method="post" id="payment-{{$payment->id}}-delete">
                                    @csrf
                                </form>
                                @else
                                <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                    {{ Icon::delete() }}
                                </span>
                                @endcan
                            </td>
                        </tr>
                        @if($payment->payment_fee !== null)
                        <tr class="{{ $row_class }}">
                            <td>Payment Fee</td>
                            <td>{{ $payment->paymentMethod->name }} Fee</td>
                            <td>{{ $payment->payer_name ?? "No Customer Found" }}</td>
                            <td>{{ $fee }}</td>
                            <td>{{ f_datetime($payment->paid_on) }}</td>
                            <td></td>
                            <td></td>
                            <td hidden></td>
                        </tr>
                        @endif
                        @endforeach
                    </table>
                </div>
            </x-admin.section.card>
            <x-admin.section.card>
                <x-slot:title>
                    Sell Price Per Person
                </x-slot:title>
                <div>
                    <table class="datatable table table-striped" id="cost-table">
                        <thead>
                            <tr>
                                <th scope="col">Type</th>
                                <th scope="col">Value</th>
                            </tr>
                        </thead>
                        @foreach($order->orderCustomers()->where('is_charged', '=', 1)->get() as $ordersCustomer)
                        <tr>
                            <td>Base: {{ $ordersCustomer->customer->first_name . ' ' .
                                $ordersCustomer->customer->last_name }}</td>
                            <td>{{ fr_currency($ordersCustomer->tour_cost, $order->currency) }}</td>
                        </tr>
                        @if($ordersCustomer->has_surcharge)
                        <tr>
                            <td>Single Occupancy Surcharge: {{ $ordersCustomer->customer->full_name }}</td>
                            <td>{{ fr_currency($ordersCustomer->single_occupancy_surcharge, $order->currency) }}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        @foreach($order->getAdditionalCosts()['upgrades'] as $upgrade)
                        <tr>
                            <td>Upgrade: {{ $upgrade['description'] }}</td>
                            <td>{{ fr_currency($upgrade['upgrade']->cost, $order->currency) }}</td>
                        </tr>
                        @endforeach
                        @foreach($order->getAdditionalCosts()['addons'] as $addon)
                        <tr>
                            <td>Add-on: {{ $addon['description'] }}</td>
                            <td>{{ fr_currency($addon['addon']->cost, $order->currency) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </x-admin.section.card>

            <x-admin.section.card>
                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="fw-bold">
                        <h4 class="fw-bold">Schedule</h4>
                    </div>
                    <div class="d-flex gap-2">
                        @can('update', \App\Models\Order\Order::class)
                        <a href="{{ route('order-installments.create', ['order' => $order, ]) }}"
                            class="btn btn-success text-white mb-1">
                            {{ Icon::create() }}
                            New Installment
                        </a>
                        <a href="{{ route('order-installments.resync', ['order' => $order, ]) }}"
                            class="btn btn-warning mb-1">
                            {{ Icon::refresh() }}
                            Refresh from Tour
                        </a>
                        @endcan
                    </div>
                </div>
                <div>
                    <table class="datatable table table-striped" id="schedule-table">
                        <thead>
                            <tr>
                                <th scope="col">Type</th>
                                <th scope="col">Due</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Received</th>
                                <th scope="col">Outstanding</th>
                                <th scope="col">Paid On</th>
                                <th scope="col" class="actions">Actions</th>
                            </tr>
                        </thead>
                        @if(($order->booking_fee ?? 0) > 0)
                        <tr>
                            <th scope="row">Booking Fee</th>
                            <td>With Order</td>
                            <td>{{ fr_currency($order->booking_fee, $order->currency) }}</td>
                            <td>
                                {{ fr_currency(min($order->booking_fee, $order->paid), $order->currency) }}
                            </td>
                            <td>
                                @if($order->booking_fee <= $order->paid)
                                    Paid
                                    @else
                                    {{ fr_currency($order->booking_fee - min($order->booking_fee, $order->paid),
                                    $order->currency) }}
                                    @endif
                            </td>
                            <td>
                                @php $covering = $order->repository->getBookingFeePayment(); @endphp
                                {{ $covering !== null ? f_datetime($covering->paid_on) : "Not Paid" }}
                            </td>
                            <td class="actions">
                                <a href="{{route('orders.edit', ['order' => $order,])}}" title="Edit"
                                    class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </a>
                            </td>
                        </tr>
                        @endif
                        @if(($order->calculated_deposit ?? 0) > 0)
                        <tr>
                            <th scope="row">Deposit</th>
                            <td>With Order</td>
                            <td>{{ fr_currency($order->calculated_deposit, $order->currency) }} ({{
                                $order->deposit_percentage }}%)</td>
                            <td>
                                @php $amount = $order->calculated_deposit - min(($order->paid - ($order->booking_fee
                                ??
                                0)), $order->calculated_deposit); @endphp
                                @if($amount <= 0) {{ fr_currency($order->calculated_deposit, $order->currency) }}
                                    @else
                                    {{ fr_currency($order->paid, $order->currency) }}
                                    @endif
                            </td>
                            <td>
                                @if($amount <= 0) Paid @else {{ fr_currency($amount, $order->currency) }}
                                    @endif
                            </td>
                            <td>
                                @php $covering = $order->repository->getDepositPayment(); @endphp
                                {{ $covering !== null ? f_datetime($covering->paid_on) : "Not Paid" }}
                            </td>
                            <td class="actions">
                                <a href="{{route('orders.edit', ['order' => $order,])}}" title="Edit"
                                    class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </a>
                            </td>
                        </tr>
                        @endif
                        @foreach($order->installments as $installment)
                        @php $paid = $installment->repository->getAmountPaid(); @endphp
                        @php $amount = $installment->calculated_amount - $installment->repository->getAmountPaid();
                        @endphp
                        <tr>
                            <th scope="row">Installment</th>
                            <td>{{ f_date($installment->due_on) }}</td>
                            <td>{{ fr_currency($installment->calculated_amount, $order->currency) }} ({{
                                $installment->percentage }}%)</td>
                            <td>
                                @if($amount <= 0) {{ fr_currency($installment->calculated_amount, $order->currency)
                                    }}
                                    @else
                                    {{ fr_currency($installment->repository->getAmountPaid(), $order->currency) }}
                                    @endif
                            </td>
                            <td>
                                @if($amount <= 0) Paid @else {{ fr_currency($amount, $order->currency) }}
                                    @endif
                            </td>
                            <td>
                                @php $paidOn = $installment->paid_on @endphp
                                {{ $paidOn === null ? 'Not Paid' : f_datetime($paidOn) }}
                            </td>
                            <td class="actions">
                                <a href="{{route('order-installments.edit', ['order' => $order, 'orderInstallment' => $installment,])}}"
                                    title="Edit" class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm mb-1" title="Delete"
                                    onclick="event.preventDefault();document.getElementById('orderInstallment-{{ $installment->id }}-delete').submit();">
                                    {{ Icon::delete() }}
                                </a>
                                <form id="orderInstallment-{{ $installment->id }}-delete"
                                    action="{{ route('order-installments.delete', ['order' => $order, 'orderInstallment' => $installment,]) }}"
                                    method="POST" style="display: none;">{{ csrf_field() }}</form>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <th scope="row">Remaining Balance</th>
                            <td>{{ f_date($order->tour?->final_payment) }}</td>
                            <td>{{ fr_currency($order->remaining_installment, $order->currency) }} ({{
                                $order->remaining_percentage }}%)</td>
                            <td> {{ fr_currency($order->paid, $order->currency) }} </td>
                            <td>
                                @php $amount = min($order->remaining, $order->remaining_installment); @endphp
                                @if($amount <= 0) Paid @else {{ fr_currency($amount, $order->currency) }}
                                    @endif
                            </td>
                            <td>
                                @php $covering = $order->repository->getRemainingPayment(); @endphp
                                {{ $covering !== null ? f_datetime($covering->paid_on) : "Not Paid" }}
                            </td>
                            <td class="actions">
                                @if($order->tour !== null)
                                <a href="{{route('tours.edit', ['tour' => $order->tour,])}}" title="Edit"
                                    class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </a>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </x-admin.section.card>
        </div>
        <div class="col-xl-6">
            <x-admin.section.card>



                <div class="d-flex justify-content-between align-items-center pb-3">
                    <div class="fw-bold">
                        <h4 class="fw-bold">Order Adjustments</h4>
                    </div>
                    <div class="d-flex gap-2">
                        @can('create', \App\Models\Order\Adjustment\ManualAdjustment::class)
                        <div class="pb-3 text-end">
                            <a href="{{ route('manual-adjustments.create', ['order' => $order, ]) }}"
                                class="btn btn-success text-white">
                                {{ Icon::create() }}
                                Add Adjustment
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>


                <div class="pt-2">
                    <table class="datatable table table-striped" id="order-adjustment-table">
                        <thead>
                            <tr>
                                <th scope="col">Sell Price</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        @if($order->commission !== null)
                        <tr>
                            <td>N/A</td>
                            <td>{{ fr_currency($order->commission_amount, $order->currency) }}</td>
                            <td>-</td>
                            <td>Commission: {{ $order->commission }}%</td>
                            <td class="actions">
                                <a href="{{ route('orders.edit', ['order' => $order,]) }}"
                                    class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                            </td>
                        </tr>
                        @endif
                        @foreach($order->adjustments as $adjustment)
                        <tr>
                            <td>{{ fr_currency($adjustment->amount, $order->currency) }}</td>
                            <td>{{ fr_currency($adjustment->cost, $order->currency) }}</td>
                            <td>{{ $adjustment->reason }}</td>

                            <td>{{ f_date($adjustment->date) }}</td>
                            <td class="actions">
                                @can('update', \App\Models\Order\Adjustment\ManualAdjustment::class)
                                <a href="{{ route('manual-adjustments.edit', ['order' => $order, 'manualAdjustment' => $adjustment,]) }}"
                                    class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                @else
                                <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                    {{ Icon::edit() }}
                                </span>
                                @endcan
                                @can('delete', \App\Models\Order\Adjustment\ManualAdjustment::class)
                                <a href="#" onclick="$('#madjustment-{{$adjustment->id}}-delete').submit()"
                                    class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete()
                                    }}</a>
                                <form
                                    action="{{ route('manual-adjustments.delete', ['order' => $order, 'manualAdjustment' => $adjustment,]) }}"
                                    method="post" id="madjustment-{{$adjustment->id}}-delete">
                                    @csrf
                                </form>
                                @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::delete() }}
                                </span>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </x-admin.section.card>
            <x-admin.section.card>
                <x-slot:title>
                    Customer Adjustments
                </x-slot:title>
                <div>
                    <table class="datatable table table-striped" id="customer-adjustment-table">
                        <thead>
                            <tr>
                                <th scope="col">Customer</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        @foreach($order->orderCustomers as $ordersCustomer)
                        @foreach($ordersCustomer->adjustments as $adjustment)
                        <tr>
                            <td>{{ $ordersCustomer->customer->first_name . " " .
                                $ordersCustomer->customer->last_name }}
                            </td>
                            <td>{{ fr_currency($adjustment->amount, $order->currency) }}</td>
                            <td>{{ $adjustment->reason }}</td>
                            <td>{{ f_date($adjustment->date) }}</td>
                            <td class="actions">
                                @can('update', \App\Models\Order\Adjustment\OrderCustomerAdjustment::class)
                                <a href="{{ route('order-customer-adjustments.edit', ['order' => $order, 'orderCustomer' => $ordersCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}"
                                    class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                @else
                                <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                    {{ Icon::edit() }}
                                </span>
                                @endcan
                                @can('delete', \App\Models\Order\Adjustment\OrderCustomerAdjustment::class)
                                <a href="#" onclick="$('#oadjustment-{{$adjustment->id}}-delete').submit()"
                                    class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete()
                                    }}</a>
                                <form
                                    action="{{ route('order-customer-adjustments.delete', ['order' => $order, 'orderCustomer' => $ordersCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}"
                                    method="post" id="oadjustment-{{$adjustment->id}}-delete">
                                    @csrf
                                </form>
                                @else
                                <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                    {{ Icon::delete() }}
                                </span>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </table>
                </div>
            </x-admin.section.card>
        </div>
    </div>
</div>

<div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-labelledby="customerDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerDetailsModalLabel">Customer Components: <span
                        id="customerNamePlaceholder" class="fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="customerModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

{{-- Closing Container--}}
@endsection

<script>
    function loadCustomerComponents() {
    const content = document.getElementById("customerModalContent");
    const modalTitleLink = document.getElementById("customerNamePlaceholder");
    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>`;
    const orderId = event.target.getAttribute('data-order-id');
    const customerId = event.target.getAttribute('data-customer-id');
    const customerName = event.target.getAttribute('data-customer-name');

    modalTitleLink.textContent = customerName;
    modalTitleLink.href = `/admin/orders/${orderId}/customer/${customerId}`;

    fetch(`/admin/orders/${orderId}/customer/${customerId}/components`)
    .then(response => {
        if (!response.ok) throw new Error('Failed to load customer components');
        return response.text();
    })
    .then(html => {
        content.innerHTML = html;
    })
    .catch(err => {
        console.error(err);
        content.innerHTML = '<div class="text-danger">Unable to load customer components.</div>';
    });
}
</script>