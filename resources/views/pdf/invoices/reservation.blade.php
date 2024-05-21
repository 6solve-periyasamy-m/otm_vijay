@php use App\Models\Quote\Quote; @endphp
@php use App\Models\Tour\Tour; @endphp
@php use App\Models\Accommodation\AccommodationInventoryTour; @endphp
@php use App\Models\Activity\ActivityInventoryTour; @endphp
@php use App\Models\Flight\FlightInventoryTour; @endphp
@php use App\Models\Transport\TransportInventoryTour; @endphp
@php use App\Models\Merchandise\Merchandise; @endphp
@php use App\Models\Customer\Customer; @endphp
@php
/**
 * @var Tour $tour
 */
@endphp
@php
if (!empty($order->tour->event->image_url)){
    $evenImg = $order->tour->event->image_url;
} else{
    $evenImg = 'images/default_image.png';
}
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />
    <style type="text/css">
        .page-break {
            page-break-before: always;
        }
        @page {margin: 10px 0; padding: 0; size: A4;}
        table {border-collapse: collapse; mso-table-lspace: 0px; mso-table-rspace: 0px;}
        td,
        a,
        span {border-collapse: collapse; mso-line-height-rule: exactly;}
        p {padding: 0 !important; margin: 0 !important;}
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {display: block; margin: 0;}
        img {border: 0; outline: none; text-decoration: none;}
        p,
        a,
        li,
        td,
        blockquote {mso-line-height-rule: exactly;}
        p,
        a,
        li,
        td,
        body,
        table,
        blockquote {-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;}
        a {color: inherit; text-decoration: none;}
        .mcnPreviewText {display: none !important;}
        /*assets css start end*/
        body {margin: 0 !important; padding: 0 !important; -webkit-text-size-adjust: 100% !important; -ms-text-size-adjust: 100% !important; -webkit-font-smoothing: antialiased !important; font-family: 'Lato', sans-serif;}
        .oc_f20 {font-family: 'Lato', sans-serif; font-size: 20px; line-height: 30px;}
        .oc_f18 {font-size: 18px; line-height: 28px;}
        .oc_f16 {font-size: 16px; line-height: 24px;}
        .oc_f14 {font-size: 14px; line-height: 22px;}
        .oc_f12 {font-size: 12px; line-height: 18px;}
        .oc_black {color: #000000;}
        .oc_lblack {color: #1a1a1a;}
        .oc_gray1 {color: #21314B;}
        .oc_bglight {background: #E9EAF0;}
        .oc_center {text-align: center;}
        .oc_left {text-align: left;}
        .oc_right {text-align: right;}
        .paymentTable {border: 2px solid #353535; border-collapse: collapse;}
        .paymentTable th {border: 2px solid #353535; border-collapse: collapse;}
        .paymentTable td {border: 2px solid #353535; border-collapse: collapse;}
    </style>
</head>

<body class="body" style="padding:0; margin:0 auto !important; display:block !important; min-width:100% !important; width:100% !important; background:#ffffff; -webkit-text-size-adjust:none">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="background-color:#ffffff; padding: 35px 0;" class="full-wrap">
        <tr>
            <td align="left" valign="top" style="padding-bottom: 10px;">
                <table width="100%" border="0" cellspacing="0">
                    <tr>
                        <td align="left" valign="top">
                            <table align="left" width="100%" border="0" cellspacing="0">
                                <tr>
                                    <td align="left" valign="top">
                                        <img src="{{img_to_b64($invoice->brand->logo)}}" alt="{{ $invoice->brand->name }}" width="100%" style="display: block; max-width: 160px">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="310" align="center" valign="top">
                            <table width="100%" align="center" border="0" cellspacing="0" style="margin: 10px auto; text-align: center;" cellpadding="0">
                                <tr>
                                    <td align="center" valign="center" class="oc_black" style="border-radius: 30px; padding: 20px 10px; font-weight: normal; background-color: #ffffff; color: #E95B15; margin: 0 auto; outline: 2px solid #E95B15; font-size: 15pt; min-width: 160px; max-width: 160px; display: block;">
                                        RESERVATION
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td width="50%" align="left" valign="top" style="background-color: #E95B15;">
                            <table align="left" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="200" align="left" valign="top" style="padding: 10px 15px 10px 25px; color: #ffffff; line-height: 20px; background-color: #353535; border-radius: 0 30px 30px 0;" class="oc_f16">
                                            BOOK REFERENCE: {{$order->booking_reference}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="oc_f14" style="padding: 10px 15px 0px 25px; color: #353535; font-weight: 700;">
                                            CUSTOMER DETAILS
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; color: #ffffff; line-height:10px;" class="oc_f14">
                                            NAME: {{ $order->leadBooker->customer_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; color: #ffffff; line-height:10px;" class="oc_f14">
                                            PHONE: {{ $order->leadBooker->customer->mobile_number }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; color: #ffffff; line-height:10px;" class="oc_f14">
                                            EMAIL: 
                                            <a href="mailto:{{ $order->leadBooker->customer->email_address }}">
                                                {{ $order->leadBooker->customer->email_address }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="120" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12">
                                            &nbsp;
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="oc_f14" style="padding: 10px 15px 0px 25px; color: #353535; font-weight: 700;">
                                            AGENT DETAILS
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; line-height:10px; color: #ffffff;" class="oc_f14">
                                            NAME: {{ $invoice->brand->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; line-height:10px; color: #ffffff;" class="oc_f14">
                                            EMAIL: 
                                            <a href="mailto:{{ $invoice->brand->email ?? setting('company.contact.email', 'Email not set') }}">
                                                {{ $invoice->brand->email ??  setting('company.contact.email', 'Email not set') }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; line-height:10px; color: #ffffff;" class="oc_f14">
                                            DATE CREATED: {{ \Carbon\Carbon::parse($invoice->brand->created_at)->format('d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="120" align="left" valign="top" style="padding: 10px 15px 0px 25px;">&nbsp;</td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;">&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td width="50%" align="right" valign="top">
                            <table align="left" width="100%" border="0" cellspacing="0">
                                <tr>
                                    <td align="left" valign="top">
                                        <img src="{{img_to_b64($evenImg)}}" alt="{{ $order->tour->event?->name }}" width="100%" style="display: block; height: 100%; max-height: 350px; object-fit: cover">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td width="50%" align="left" valign="top" style="background-color: #FBDED0; padding: 20px 0">
                            <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tbody>
                                    
                                    <tr>
                                        <td  align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            BOOKING NAME:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                            {{ $order->tour->event->name ?? 'Event name not available'}} | {{ $order->leadBooker->customer_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            TRAVEL DATES:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                            {{ \Carbon\Carbon::parse($order->tour->date_from)->format('d F Y') }} - {{ \Carbon\Carbon::parse($order->tour->date_to)->format('d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            EVENT:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                            {{ $order->tour->event->name ?? 'Event name not available'}}
                                        </td>
                                    </tr>
                                   
                                </tbody>
                            </table>
                        </td>
                        <td width="50%" align="right" valign="top" style="background-color: #FBDED0; padding: 20px 0">
                            <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tbody>
                                   
                                    <tr>
                                        <td  align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            TOTAL NUMBER OF PERSONS:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                            {{ $order->customer_count }} Adult(s)
                                        </td>
                                    </tr>
                                    @php 
                                        $counter = 0; 
                                        $totalRecords = count($order->orderCustomers); 
                                        $otherGuestsShown = false; // Introduce a flag to track if "OTHER GUESTS" has been shown
                                    @endphp

                                    @foreach($order->orderCustomers as $ordersCustomer)
                                        @if($counter < 5)
                                            <tr>
                                                <td align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                                    {{ ($order->lead_booker_id == $ordersCustomer->id) ? 'LEAD GUEST:' : ($otherGuestsShown ? '' : 'OTHER GUESTS:') }}
                                                    {{-- Check if "OTHER GUESTS" has been shown --}}
                                                    @if(!$otherGuestsShown && $order->lead_booker_id != $ordersCustomer->id)
                                                        @php $otherGuestsShown = true; @endphp
                                                    @endif
                                                </td>
                                                <td align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                                    {{$ordersCustomer->customer->title}} {{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}
                                                </td>
                                            </tr>
                                            @php $counter++; @endphp
                                        @else
                                            @break
                                        @endif
                                    @endforeach

                                    @if($totalRecords > 5)
                                        <tr>
                                            <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                                &nbsp;
                                            </td>
                                            <td align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                                TBC
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td  align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            BOOKING REFERENCE:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                            {{$order->booking_reference}}
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                <table align="left" width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr style=" background-color: #353535; padding: 2px 15px;">
                            <th align="left" valign="top" style="padding: 10px 25px; color: #ffffff; font-weight: 700;" class="oc_f16">
                                TRIP ITINERARY AND INCLUSIONS
                            </th>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <tr>
            <td colspan="2" align="left" >
                <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;" class="oc_f16 oc_lblack">
                ARRIVAL TRANSFER
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap">
        @foreach($tour->flightInventoryTours as $tourComponent)
        @if($tourComponent->flight_type != 'Inbound')
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                DATE: 
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ f_datetime($tourComponent->inventory->departs_at) }} to {{ f_datetime($tourComponent->inventory->arrives_at) }}
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
            DESCRIPTION:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $tourComponent->inventory->flight->departureAirport->name }} to {{ $tourComponent->inventory->flight->arrivalAirport->name }} ({{ $tourComponent->inventory->flight_number }})
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
            QUANTITY:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $order->customer_count }}
            </td>
        </tr>
        @endif
        @endforeach
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <tr>
            <td colspan="2" align="left" >
                <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 190px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;" class="oc_f16 oc_lblack">
                TRANSFER
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        @foreach($tour->transportInventoryTours as $tourComponent)
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                DATE:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ f_datetime($tourComponent->inventory->departs_at) }} to {{ f_datetime($tourComponent->inventory->arrives_at) }}
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
            DESCRIPTION:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $tourComponent->inventory->component->name }} {{ $tourComponent->inventory->transport_number }} 
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
            QUANTITY:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $order->customer_count }}
            </td>
        </tr>
        @endforeach
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <tr>
            <td colspan="2" align="left" >
                <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 190px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;" class="oc_f16 oc_lblack">
                    ACCOMMODATION
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        @foreach($tour->accommodationInventoryTours as $tourComponent)
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                CHECK IN:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ \Carbon\Carbon::parse($tourComponent->inventory->check_in)->format('d F Y | h:i A') }}
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                CHECK OUT:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ \Carbon\Carbon::parse($tourComponent->inventory->check_out)->format('d F Y | h:i A') }}
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                HOTEL NAME:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $tourComponent->inventory->component->name }}
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                ADDRESS:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $tourComponent->inventory->component->address }}
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                ROOM TYPE:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $tourComponent->inventory->roomType->name }}
            </td>
        </tr>
        @endforeach
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <tr>
            <td colspan="2" align="left" >
                <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 190px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;" class="oc_f16 oc_lblack">
                EVENT/S
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                DATE:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                @if (!empty($order->tour->event->starts_at))
                    {{ \Carbon\Carbon::parse($order->tour->event->starts_at)->format('d F Y') }}
                @endif
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                EVENT:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $order->tour->event->name ?? 'Event name not available'}}
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                DESCRIPTION:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $order->tour->event->description ?? 'Event description not available' }}
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <tr>
            <td colspan="2" align="left">
                <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 190px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;" class="oc_f16 oc_lblack">
                    DEPARTURE TRANSFER
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap">
        @foreach($tour->flightInventoryTours as $tourComponent)
        @if($tourComponent->flight_type == 'Inbound')
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
                DATE: 
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ f_datetime($tourComponent->inventory->departs_at) }} to {{ f_datetime($tourComponent->inventory->arrives_at) }}
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
            DESCRIPTION:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $tourComponent->inventory->flight->departureAirport->name }} to {{ $tourComponent->inventory->flight->arrivalAirport->name }} ({{ $tourComponent->inventory->flight_number }})
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;" class="oc_f12 oc_lblack">
            QUANTITY:
            </td>
            <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="oc_f12 oc_lblack">
                {{ $order->customer_count }}
            </td>
        </tr>
        @endif
        @endforeach
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <!-- Notes Section -->
        @if(!empty($order->external_notes))       
        <tr>
            <td align="left" width="150" style="padding: 10px 40px; color: #ffffff; background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px;" class="oc_f12 oc_lblack">
                <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 190px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;" class="oc_f16 oc_lblack">
                    NOTE
                </div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                {!! $order->external_notes !!}
            </td>
        </tr>
        @endif
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <thead>
            <tr style=" background-color: #353535; padding: 2px 15px;">
                <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="oc_f16">
                    PAYMENT SUMMARY
                </th>
            </tr>
        </thead>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;;" class="oc_f12 oc_lblack">
                BOOKING TOTAL
            </td>
            <td align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                {{ f_currency($tour->base_price_per_person) }}
            </td>
        </tr>
        <tr>
            <td align="left" width="100" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;;" class="oc_f12 oc_lblack">
                GST (included)
            </td>
            <td align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                {{ f_currency($tour->tax_amount) }}
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" width="120">&nbsp;</td>
            <td align="center" valign="top" style="padding: 10px 15px 0px 25px;">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" width="140" valign="top" style="padding: 2px 10px 10px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                FINAL BOOKING AMOUNT    
            </td>
            <td align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                {{ f_currency($tour->base_price_per_person) }}
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <tr>
            <td align="left" valign="top" class="oc_f16" style="background-color: #FBDED0; color: #000000; font-weight: bold; padding: 10px 15px 0px 25px;">
            PAYMENT SCHEDULE
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" style="background-color: #FBDED0; padding: 10px 30px;">
                <table align="left" border="0" cellspacing="0" cellpadding="0" class="paymentTable" width="100%">
                    <thead>
                        <tr style="background-color: #353535;">
                            <th width="25%" align="left" valign="top" style="padding: 2px 15px; font-weight: bold; color: #ffffff;" class="oc_f16">
                                INSTALMENTS
                            </th>
                            <th width="25%" align="left" valign="top" style="padding: 2px 15px; font-weight: bold; color: #ffffff;" class="oc_f16">
                                AMOUNT DUE
                            </th>
                            <th width="25%" align="left" valign="top" style="padding: 2px 15px; font-weight: bold; color: #ffffff;" class="oc_f16">
                                DATE DUE
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                Deposit
                            </td>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                               {{ f_currency($tour->deposit) }} ({{ $tour->deposit_percentage }}%)
                            </td>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                With Order
                            </td>
                        </tr>
                        @foreach($tour->paymentInstallments as $installment)
                        <tr>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                Instalment
                            </td>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                {{ f_currency($installment->cost) }} ({{ $installment->percentage }}%)
                            </td>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                {{ f_date($installment->due_on) }}
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                Remaining
                            </td>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                {{ f_currency($tour->remaining_installment) }} ({{ $tour->remaining_percentage }}%)
                            </td>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                {{ f_date($tour->final_payment) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap">
        <tr>
            <td colspan="2" align="left">
                <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 190px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;" class="oc_f16 oc_lblack">
                    PAYMENT METHOD
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap">
        <tr>
            <td align="left" width="150" style="padding: 5px 25px; font-weight: bold;" class="oc_f12">
                BANK TRANSFER
            </td>
            <td align="right" valign="top" style="padding: 0 15px;">&nbsp;</td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap">
        <tr >
            <td style=" padding: 10px 15px 10px 25px;" class="oc_f12">
                {!! setting('company.bank_transfer', '-')  !!}
            </td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="full-wrap"> 
        <thead>
            <tr style=" background-color: #353535; padding: 2px 15px;">
                <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="oc_f16">
                TERMS & CONDITIONS
                </th>
            </tr>
        </thead>
    </table>
    <div style="padding: 10px 15px 10px 25px;">
        <p class="oc_f12 oc_lblack" >
               {!! $tour->terms !!}
        </p>
    </div>
    <div style="padding: 10px 15px 10px 25px;">
        <p class="oc_f12 oc_lblack" >
            {!! $tour->invoice_footer !!}
        </p>
    </div>
</body>
</html>