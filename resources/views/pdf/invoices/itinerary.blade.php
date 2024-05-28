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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

    <style type="text/css">
        .page-break {
            page-break-before: always;
        }

        @page {
            margin: 0px;
            padding: 0px;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0px;
            mso-table-rspace: 0px;
        }

        td,
        a,
        span {
            border-collapse: collapse;
            mso-line-height-rule: exactly;
        }

        p {
            padding: 0 !important;
            margin: 0 !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            display: block;
            margin: 0;
        }

        img {
            border: 0;
            outline: none;
            text-decoration: none;
        }

        p,
        a,
        li,
        td,
        blockquote {
            mso-line-height-rule: exactly;
        }

        p,
        a,
        li,
        td,
        body,
        table,
        blockquote {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .mcnPreviewText {
            display: none !important;
        }

        /*assets css start end*/
        body {
            margin: 0 !important;
            padding: 0 !important;
            -webkit-text-size-adjust: 100% !important;
            -ms-text-size-adjust: 100% !important;
            -webkit-font-smoothing: antialiased !important;
            font-family: 'Lato', sans-serif;
        }

        .oc_f20 {
            font-family: 'Lato', sans-serif;
            font-size: 20px;
            line-height: 30px;
        }

        .oc_f18 {
            font-size: 18px;
            line-height: 28px;
        }

        .oc_f16 {
            font-size: 16px;
            line-height: 24px;
        }

        .oc_f14 {
            font-size: 14px;
            line-height: 22px;
        }

        .oc_f12 {
            font-size: 12px;
            line-height: 18px;
        }

        .oc_black {
            color: #000000;
        }

        .oc_lblack {
            color: #1a1a1a;
        }

        .oc_gray1 {
            color: #21314B;
        }

        .oc_bglight {
            background: #E9EAF0;
        }

        .oc_center {
            text-align: center;
        }

        .oc_left {
            text-align: left;
        }

        .oc_right {
            text-align: right;
        }

        .paymentTable {
            border: 2px solid #353535;
            border-collapse: collapse;
        }

        .paymentTable th {
            border: 2px solid #353535;
            border-collapse: collapse;
        }

        .paymentTable td {
            border: 2px solid #353535;
            border-collapse: collapse;
        }
    </style>
    <title>Itinerary - {{ $invoice->booking_reference }}</title>
</head>

<body class="body" style="padding:0; margin:0 auto !important; display:block !important; width:100% !important; background:#ffffff; -webkit-text-size-adjust:none">
    <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="background-color:#ffffff; padding: 35px 0;" class="full-wrap">
        <tr>
            <td colspan="2" align="left" valign="top" style="padding-bottom: 10px;">
                <table style="width: 100%;" border="0" cellspacing="0">
                    <tr>
                        <td align="left" valign="top">
                            <table align="left" width="100%" border="0" cellspacing="0">
                                <tr>

                                    <td align="left" valign="top">
                                        <img src="{{img_to_b64($invoice->brand->logo)}}" alt="logo" width="300" style="display: block;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="310" align="center" valign="top">
                            <table width="100%" align="center" border="0" cellspacing="0" style="margin: 10px auto; text-align: center;" cellpadding="0">
                                <tr>
                                    <td align="center" valign="center" class="oc_black" style="border-radius: 30px; padding: 20px 10px; font-weight: normal; background-color: #ffffff; 
                                                            color: #E95B15; margin: 0 auto; outline: 2px solid #E95B15; font-size: 15pt; max-width: 220px; display: inline-block;">
                                        TRAVEL ITINERARY
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" colspan="2" valign="top">
                <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td width="50%" align="left" valign="top" style="background-color: #E95B15;">
                            <table align="left" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="oc_f16" style="padding: 10px 15px 0 25px; color: #ffffff; ">
                                            {{ strtoupper($tour->name) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">
                                            &nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="oc_f12" style="padding: 10px 15px 0px 25px; color: #353535; ">
                                            CUSTOMER DETAILS
                                        </td>
                                    </tr>
                                    @foreach($order->orderCustomers as $ordersCustomer)
                                    <tr>
                                        <td width="120" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff; " class="oc_f12">
                                            {{ ($order->lead_booker_id == $ordersCustomer->id) ? 'LEAD GUEST:' : ' OTHER GUESTS:'}}

                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px; color: #ffffff; " class="oc_f12">
                                            {{$ordersCustomer->customer->title}} {{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}
                                        </td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td width="120" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff; " class="oc_f12">
                                            BOOKING REFERENCE:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px; color: #ffffff; " class="oc_f12">
                                            {{$order->booking_reference}}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td width="120" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12">
                                            &nbsp;
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                                            &nbsp;</td>
                                    </tr>
                                    <tr>
                                        @foreach($order->orderCustomers as $ordersCustomer)
                                        @if($order->lead_booker_id == $ordersCustomer->id)

                                    <tr>
                                        <td colspan="2" class="oc_f12" style="padding: 10px 15px 0px 25px; color: #353535; ">
                                            ONSITE AGENT DETAILS
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff; " class="oc_f12">
                                            NAME: {{$ordersCustomer->customer->title}} {{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff; " class="oc_f12">
                                            PHONE: {{$ordersCustomer->customer->mobile_number}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff; " class="oc_f12">
                                            EMAIL: <a href="mailto:{{$ordersCustomer->customer->email_address}}">
                                                {{$ordersCustomer->customer->email_address}}
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    <tr>
                                        <td width="120" align="left" valign="top" style="padding: 10px 15px 0px 25px; ">
                                            &nbsp;
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                                            &nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        @php
                        $imageUrl = 'uploads/images/events.jpg';

                        @endphp
                        <td width="50%" align="right" valign="top">
                            <table align="left" width="100%" border="0" cellspacing="0">
                                <tr>
                                    <td align="left" valign="top">
                                        <img src="{{ img_to_b64($imageUrl) }}" alt="logo" width="300" style="display: block;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="right" colspan="2" valign="top">
                <table align="left" width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr style=" background-color: #353535; padding: 2px 15px;">
                            <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="oc_f16 ">TRIP ITINERARY AND INCLUSIONS</th>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
    </table>
    <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="background-color:#ffffff; padding: 35px 0;" class="full-wrap">

        @php
        // Start and end dates
        $date_from = date('Y-m-d', strtotime($tour->date_from));
        $date_to = date('Y-m-d', strtotime($tour->date_to));
        $start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $date_from);
        $end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $date_to);
        @endphp

        @foreach ($start_date->daysUntil($end_date) as $keyAcc => $date)

        <tr>

            <td align="left" width="150" style="padding: 10px 40px; color: #ffffff; background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px;" class="oc_f12 oc_lblack">DAY {{ $loop->iteration }} - {{ $date->format('d M Y') }}</td>

            <td></td>


        </tr>
    </table>
    <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="background-color:#ffffff; padding: 35px 0;" class="full-wrap">
        @foreach($tour->flightInventoryTours as $tourComponent)

        @php
        $departs_at = new DateTime($tourComponent->inventory->departs_at);
        $arrives_at = new DateTime($tourComponent->inventory->arrives_at);
        @endphp
        @if($date->format('Y-m-d') >= $arrives_at->format('Y-m-d') && $date->format('Y-m-d') <= $departs_at->format('Y-m-d'))
            <tr>


                <td align="left" colspan="2" width="" style="padding: 0px 20px; font-weight: bold; font-size: 11pt;" class="oc_f12 oc_lblack">Arrival from {{ $tourComponent->inventory->flight->departureAirport->name }} to {{ $tourComponent->inventory->flight->arrivalAirport->name }} </td>

            </tr>




            @endif
            @endforeach

            @foreach($tour->accommodationInventoryTours as $tourComponent)
            @if($date->format('Y-m-d') >= date('Y-m-d', strtotime($tourComponent->inventory->check_in)) && $date->format('Y-m-d') <= date('Y-m-d', strtotime($tourComponent->inventory->check_out)))
                @php
                $nights = 0;
                if($keyAcc == 0){
                $date1 = date('Y-m-d', strtotime($tourComponent->inventory->check_in));
                $date2 = date('Y-m-d', strtotime($tourComponent->inventory->check_out));

                // Convert the dates to DateTime objects
                $datetime1 = new DateTime($date1);
                $datetime2 = new DateTime($date2);

                // Calculate the difference between the two dates
                $interval = $datetime1->diff($datetime2);

                // Get the number of nights
                $nights = $interval->format('%a') - 1;
                }

                @endphp
                <tr>

                    <td align="left" width="" style="padding: 0px 40px; color: #E95B15; font-size: 11pt;" class="oc_f16 ">ACCOMMODATION
                    </td>
                    <td align="right" valign="top" style="padding: 2px 15px;">&nbsp;</td>

                </tr>
                <tr>
                    <td align="left" valign="top" style="padding: 0 15px;">&nbsp;
                    </td>
                    <td width="" align="center" valign="top" style="padding: 0 15px;">
                        &nbsp;</td>
                </tr>
                <tr>
                    <td align="left" width="40" valign="top" style="padding: 0px 40px;"
                        class="oc_f12 oc_lblack">Hotel:
                    </td>
                    <td align="left" valign="top"
                        style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                        {{ $tourComponent->inventory->component->name }}
                    </td>
                </tr>
                @if($nights > 0)
                <tr>
                    <td align="left" width="" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">No Of Nights:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                        {{$nights}}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">Address:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                        {{$tourComponent->inventory->component->address}}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">Check In Date:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                        {{ date('d M y', strtotime($tourComponent->inventory->check_in)) }}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">Check Out Date:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                        {{ date('d M y', strtotime($tourComponent->inventory->check_out)) }}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="" valign="top" style="padding: 0px 40px;">&nbsp;
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px;">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td align="left" width="" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">Hotel Description:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                        {!! $tourComponent->inventory->component->description !!}
                    </td>
                </tr>
                @endif

                @endif
                @endforeach
                @foreach($tour->activityInventoryTours as $tourComponent)
                @php
                $startDateTime = new DateTime($tourComponent->inventory->starts_at);
                $endDateTime = new DateTime($tourComponent->inventory->ends_at);
                @endphp
                @if($date->format('Y-m-d') >= $startDateTime->format('Y-m-d') && $date->format('Y-m-d') <= $endDateTime->format('Y-m-d'))
                    <tr>
                        <td align="left" width="" style="padding: 0px 40px; color: #E95B15; font-size: 11pt;" class="oc_f16 ">
                            {{ ($tourComponent->inventory->component->activity_category == 0 ) ? 'INCLUSION' : 'EVENT';  }}
                        </td>


                        <td width="" align="center" valign="top" style="padding: 0 15px;">
                            &nbsp;</td>

                    </tr>
                    <tr>


                        <td align="left" width="40" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">Event:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                            {{ $tourComponent->inventory->component->name }}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="40" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">Venue:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                            {{$tourComponent->inventory->component->address}}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="40" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">Ticket Type/s:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                            {{$tourComponent->inventory->ticketType->name}}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="40" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">Date:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                            {{ f_datetime($tourComponent->inventory->starts_at) }}
                            to {{ f_datetime($tourComponent->inventory->ends_at) }}
                        </td>
                    </tr>

                    <tr>
                        <td align="left" width="40" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">&nbsp;
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px;">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="40" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">Description:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                            {!! $tourComponent->inventory->component->description !!}
                        </td>
                    </tr>

                    @endif
                    @endforeach

                    @endforeach
    </table>
    <table align="left" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr style=" background-color: #353535; padding: 2px 15px;">
                <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="oc_f16 ">EVENT INFORMATION</th>
            </tr>
           
            </tr>
        </thead>
        <tr>
            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">

</td>
    </table>
    <p>{!! $tour->description !!}</p>
           
   

    <table align="left" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr style=" background-color: #353535; padding: 2px 15px;">
                <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="oc_f16 ">TERMS & CONDITIONS</th>
            </tr>
        </thead>
    </table>
    <p class="oc_f12 oc_lblack" style="padding: 10px 15px 0px 25px;">{!! $tour->terms !!}</p>
   
    <p class="oc_f12 oc_lblack" style="padding: 10px 15px 0px 25px;">{!! $tour->invoice_footer !!}</p>
    <!-- <table style="width: 100%;" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="background-color:#ffffff; padding: 35px 0;" class="full-wrap">


        <tr>
            <td colspan="2" align="left" valign="top" class="oc_f12 oc_lblack" style="padding: 10px 15px 0px 25px;">
                {!! $tour->terms !!}
            </td>
        </tr>
        <tr>
            <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; ">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2" align="left" valign="top" class="oc_f12 oc_lblack" style="page-break-inside: avoid; padding: 10px 15px 0px 25px;">
                {!! $tour->invoice_footer !!}
               
            </td>
        </tr>

    </table> -->
</body>

</html>