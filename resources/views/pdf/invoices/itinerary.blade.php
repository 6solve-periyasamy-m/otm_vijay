@php use App\Models\Quote\Quote; @endphp
@php use App\Models\Tour\Tour; @endphp
@php use App\Models\Accommodation\AccommodationInventoryTour; @endphp
@php use App\Models\Activity\ActivityInventoryTour; @endphp
@php use App\Models\Flight\FlightInventoryTour; @endphp
@php use App\Models\Transport\TransportInventoryTour; @endphp
@php use App\Models\Merchandise\Merchandise; @endphp
@php use App\Models\Customer\Customer; use Carbon\Carbon; @endphp
@php
    /**
    * @var Tour $tour
    */
@endphp

        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

    <style type="text/css"><?php include(public_path() . '/css/kpt.css') ?></style>
    <title>{{ $order->tour->name }} Itinerary | {{ $order->booking_reference }}</title>
</head>

<body class="body">
    <table class="header full-wrap">
        <tr>
            <td align="left" valign="top" style="padding-bottom: 10px;">
                <table width="100%" border="0" cellspacing="0">
                    <tr>
                        <td align="left" valign="top">
                            <table align="left" width="100%" border="0" cellspacing="0">
                                <tr>
                                    <td align="left" valign="top" style="padding: 0 20px;">
                                        <img src="{{img_to_b64($invoice->brand->logo)}}" alt="{{ $invoice->brand->name }}"
                                             width="100%" style="display: block; max-width: 190px">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="310" align="center" valign="top">
                            <table class="cell-padding-0 document-logo-table">
                                <tr>
                                    <td class="document-logo">
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
                                        <td colspan="2" align="left" valign="top"
                                            style="padding: 10px 15px 2px 25px; color: #ffffff; line-height: 20px;"
                                            class="fs-16">
                                            {{ strtoupper($tour->name) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="fs-14"
                                            style="padding: 10px 15px 0 25px; color: #353535; font-weight: 700;">
                                            CUSTOMER DETAILS
                                        </td>
                                    </tr>
                                    @php
                                        $otherGuest = 0;
                                        $isTBC = 0;
                                    @endphp
                                    @foreach($order->orderCustomers as $ordersCustomer)
                                        <tr>
                                            <td align="left" valign="top"
                                                style="padding: 10px 15px 0 25px; color: #ffffff; line-height:20px;"
                                                class="fs-14">
                                                @if($order->lead_booker_id == $ordersCustomer->id)
                                                    LEAD GUEST
                                                @else
                                                    @if($otherGuest == 0)
                                                    OTHER GUESTS

                                                    @endif
                                                    @php
                                                        $otherGuest++;
                                                    @endphp
                                                @endif


                                            </td>
                                            @if($otherGuest < 4)
                                                <td align="left" valign="top"
                                                    style="padding: 10px 15px 0 25px; color: #ffffff; line-height:20px;"
                                                    class="fs-14">
                                                    {{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}

                                                </td>
                                            @else
                                                @if($otherGuest > 3 && $isTBC == 0)
                                                    @php
                                                        $isTBC++;
                                                    @endphp
                                                    <td align="left" valign="top"
                                                        style="padding: 10px 15px 0 25px; color: #ffffff; line-height:20px;"
                                                        class="fs-14">
                                                        TBC
                                                    </td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="fs-14"
                                            style="padding: 10px 15px 0 25px; color: #ffffff; line-height:20px;">
                                            BOOKING REFERENCE:
                                        </td>
                                        <td class="fs-14"
                                            style="padding: 10px 15px 0 25px; color: #ffffff; line-height:10px;">
                                            {{$order->booking_reference}}
                                        </td>
                                    </tr>
                                    @foreach($order->orderCustomers as $ordersCustomer)
                                        @if($order->lead_booker_id == $ordersCustomer->id)
                                            <tr>
                                                <td colspan="2" class="fs-14"
                                                    style="padding: 10px 15px 0 25px; color: #353535; font-weight: 700;">
                                                    ONSITE AGENT DETAILS
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="left" valign="top"
                                                    style="padding: 10px 15px 0 25px; line-height:10px; color: #ffffff; "
                                                    class="fs-14">
                                                    NAME: {{$ordersCustomer->customer->title}} {{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="left" valign="top"
                                                    style="padding: 10px 15px 0 25px; line-height:10px; color: #ffffff;"
                                                    class="fs-14">
                                                    PHONE: {{$ordersCustomer->customer->mobile_number}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="left" valign="top"
                                                    style="padding: 10px 15px 0 25px; line-height:10px; color: #ffffff;"
                                                    class="fs-14">
                                                    EMAIL:
                                                    <a href="mailto:{{$ordersCustomer->customer->email_address}}">
                                                        {{$ordersCustomer->customer->email_address}}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                        @php
                            if (!empty($order->tour->event->image_url)){
                                $imageUrl = $order->tour->event->image_url;

                            } else{
                                $imageUrl = 'images/default_image.png';
                            }

                        @endphp
                        <td width="50%" align="right" valign="top">
                            <table align="left" width="100%" border="0" cellspacing="0">
                                <tr>
                                    <td align="left" valign="top">
                                        <img src="{{ img_to_b64($imageUrl) }}" width="100%"
                                             alt="Event Image"
                                             style="display: block; height: 100%; max-height: 350px; object-fit: cover">

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
                        <tr style=" background-color: #353535;">
                            <th align="left" valign="top" style="padding: 10px 25px; color: #ffffff; font-weight: 700;"
                                class="fs-16 ">TRIP ITINERARY AND INCLUSIONS
                            </th>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
    </table>
    <table border="0" width="60%" cellspacing="0" cellpadding="0" bgcolor="#ffffff"
           style="background-color:#ffffff; padding: 5px 0 10px 0;" class="full-wrap">
        @php
            $is_accommodation = 0;

            // Start and end dates
            $date_from = date('Y-m-d', strtotime($tour->date_from));
            $date_to = date('Y-m-d', strtotime($tour->date_to));
            $start_date = Carbon::createFromFormat('Y-m-d', $date_from);
            $end_date = Carbon::createFromFormat('Y-m-d', $date_to);
        @endphp

        @foreach ($start_date->daysUntil($end_date) as $keyAcc => $date)
            @php
                $daysData = 0;
            @endphp
            <tr>
                <td colspan="2" align="left">
                    <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 190px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;"
                         class="fs-16 text-dark " id="day_heading_{{ $loop->iteration }}">
                        DAY {{ $loop->iteration }} - {{ $date->format('d M Y') }}
                    </div>
                </td>
            </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff"
           style="background-color:#ffffff; padding: 5px 0 10px 0;" class="full-wrap">
        @foreach($tour->flightInventoryTours as $tourComponent)
            @php
                $departs_at = new DateTime($tourComponent->inventory->departs_at);
                $arrives_at = new DateTime($tourComponent->inventory->arrives_at);

                $f_starts_at = DateTime::createFromFormat('d/m/Y H:i', f_datetime($tourComponent->inventory->departs_at))->format('d M Y | h:i A');
                $f_ends_at = DateTime::createFromFormat('d/m/Y H:i', f_datetime($tourComponent->inventory->arrives_at))->format('d M Y | h:i A');



            @endphp
            @if($date->format('Y-m-d') >= $arrives_at->format('Y-m-d') && $date->format('Y-m-d') <= $departs_at->format('Y-m-d'))
                @php
                    $daysData = 1;
                @endphp
                <tr>
                    <td align="left" colspan="2" style="padding: 0 40px; color: #E95B15; font-size: 11pt;"
                        class="fs-16 ">TRANSFER
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                        TYPE:
                    </td>
                    <td align="left" valign="top" style="padding: 0 40px; line-height: 10px;" class="fs-12 text-dark">
                        Flight
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                        DATE:
                    </td>
                    <td align="left" valign="top" style="padding: 0 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{$f_starts_at }} to {{$f_ends_at}}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                        DESCRIPTION:
                    </td>
                    <td align="left" valign="top" style="padding: 0 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ $tourComponent->inventory->flight->departureAirport->name }}
                        to {{ $tourComponent->inventory->flight->arrivalAirport->name }}
                    </td>
                </tr>
                <tr>

                <tr>
                    <td class="cell-header fs-12 text-dark">&nbsp;
                    </td>
                    <td class="empty-cell">
                        &nbsp;
                    </td>
                </tr>

            @endif
        @endforeach

        @foreach($tour->transportInventoryTours as $tourComponent)
            @if($date->format('Y-m-d') >= date('Y-m-d', strtotime($tourComponent->inventory->departs_at)) && $date->format('Y-m-d') <= date('Y-m-d', strtotime($tourComponent->inventory->arrives_at)))

                <tr>
                    <td align="left" colspan="2" style="padding: 0 40px; color: #E95B15; font-size: 11pt;"
                        class="fs-16 ">TRANSFER
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                        TYPE:
                    </td>
                    <td align="left" valign="top" style="padding: 0 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ $tourComponent->inventory->component->arrivalTransferType() }}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                        DATE:
                    </td>
                    <td align="left" valign="top" style="padding: 0 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ Carbon::parse($tourComponent->inventory->departs_at)->format('d F Y | G A') }}
                        to {{ Carbon::parse($tourComponent->inventory->arrives_at)->format('d F Y | G A') }}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                        DESCRIPTION:
                    </td>
                    <td align="left" valign="top" style="padding: 0 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ $tourComponent->inventory->component->name }} {{ $tourComponent->inventory->transport_number }}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                        QUANTITY:
                    </td>
                    <td align="left" valign="top" style="padding: 0 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ $order->customer_count }}
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        &nbsp;
                    </td>
                    <td class="empty-cell">
                        &nbsp;
                    </td>
                </tr>

            @endif
        @endforeach

        @foreach($tour->accommodationInventoryTours as $tourComponent)
            @if($date->format('Y-m-d') >= date('Y-m-d', strtotime($tourComponent->inventory->check_in)) && $date->format('Y-m-d') <= date('Y-m-d', strtotime($tourComponent->inventory->check_out)))
                @php
                    $is_accommodation++;
                @endphp
                @php
                    $daysData = 1;
                @endphp
                <tr>
                    <td align="left" colspan="2" style="padding: 0 40px; color: #E95B15; font-size: 11pt;" class="fs-16 ">
                        ACCOMMODATION
                    </td>
                </tr>

                <tr>
                    <td align="left" width="50" valign="top" style="padding: 10px 40px; background-color: #ffffff;" class="fs-12 text-dark">
                        Hotel:
                    </td>
                    <td align="left" valign="top" style="padding: 10px 40px; background-color: #ffffff;" class="fs-12 text-dark">
                        {{ $tourComponent->inventory->component->name }}
                    </td>
                </tr>
                @if($is_accommodation == 1)
                    <tr>
                        <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                            No Of Nights:
                        </td>
                        <td class="cell-data fs-12 text-dark">
                            {{ diff_in_nights($tourComponent->inventory->check_in, $tourComponent->inventory->check_out) }}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                            Address:
                        </td>
                        <td class="cell-data fs-12 text-dark">
                            {{$tourComponent->inventory->component->address}}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                            Check In Date:
                        </td>
                        <td class="cell-data fs-12 text-dark">
                            {{ date('d M y', strtotime($tourComponent->inventory->check_in)) }}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                            Check Out Date:
                        </td>
                        <td class="cell-data fs-12 text-dark">
                            {{ date('d M y', strtotime($tourComponent->inventory->check_out)) }}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="50" valign="top" style="padding: 0 40px;">
                            &nbsp;
                        </td>
                        <td class="empty-cell">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="50" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                            Hotel Description:
                        </td>
                        <td class="cell-data fs-12 text-dark">
                            {!! $tourComponent->inventory->component->description !!}
                        </td>
                    </tr>
                @endif

            @endif
        @endforeach
        @if(isset($order->tour->event))
            @if($date->format('Y-m-d') >= date('Y-m-d', strtotime($order->tour->event->starts_at)) && $date->format('Y-m-d') <= date('Y-m-d', strtotime($order->tour->event->ends_at)))
                @php
                    $daysData = 1;
                @endphp
                <tr>
                    <td align="left" width="" style="padding: 0 40px; color: #E95B15; font-size: 11pt;" class="fs-16 ">
                        EVENT
                    </td>
                    <td width="" align="center" valign="top" style="padding: 0 15px;">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        Event:
                    </td>
                    <td class="cell-data fs-12 text-dark">
                        {{ $order->tour->event->name }}
                    </td>
                </tr>

                <tr>
                    <td class="cell-header fs-12 text-dark">
                        Date:
                    </td>
                    <td class="cell-data fs-12 text-dark">
                        {{ f_date($order->tour->event->starts_at) }} to {{ f_date($order->tour->event->ends_at) }}
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        &nbsp;
                    </td>
                    <td class="empty-cell">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        Description:
                    </td>
                    <td class="cell-data fs-12 text-dark">
                        {!! $order->tour->event->name !!}
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        &nbsp;
                    </td>
                    <td class="empty-cell">
                        &nbsp;
                    </td>
                </tr>
            @endif
        @endif


        @foreach($tour->activityInventoryTours as $tourComponent)
            @php
                $startDateTime = new DateTime($tourComponent->inventory->starts_at);
                $endDateTime = new DateTime($tourComponent->inventory->ends_at);

                $starts_at = DateTime::createFromFormat('d/m/Y H:i', f_datetime($tourComponent->inventory->starts_at))->format('d M Y | h:i A');
                $ends_at = DateTime::createFromFormat('d/m/Y H:i', f_datetime($tourComponent->inventory->ends_at))->format('d M Y | h:i A');
            @endphp
            @if($date->format('Y-m-d') >= $startDateTime->format('Y-m-d') && $date->format('Y-m-d') <= $endDateTime->format('Y-m-d'))
                @php
                    $daysData = 1;
                @endphp
                <tr>
                    <td align="left" width="" style="padding: 0 40px; color: #E95B15; font-size: 11pt;" class="fs-16 ">
                        {{ ($tourComponent->inventory->component->activity_category == 0 ) ? 'INCLUSION' : 'EVENT'  }}
                    </td>
                    <td width="" align="center" valign="top" style="padding: 0 15px;">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        Event:
                    </td>
                    <td class="cell-data fs-12 text-dark">
                        {{ $tourComponent->inventory->component->name }}
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        Venue:
                    </td>
                    <td class="cell-data fs-12 text-dark">
                        {{$tourComponent->inventory->component->address}}
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        Ticket Type/s:
                    </td>
                    <td class="cell-data fs-12 text-dark">
                        {{$tourComponent->inventory->ticketType->name}}
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        Date:
                    </td>
                    <td class="cell-data fs-12 text-dark">
                        {{ $starts_at }} to {{ $ends_at }}
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        &nbsp;
                    </td>
                    <td class="empty-cell">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">
                        Description:
                    </td>
                    <td class="cell-data fs-12 text-dark">
                        {!! $tourComponent->inventory->component->description !!}
                    </td>
                </tr>

            @endif
        @endforeach
        @endforeach
        <tr>
            <td align="left" colspan="2" width="100%" style="padding: 0 40px; font-weight: bold; font-size: 11pt;"
                class="fs-12 text-dark ">
                End of experience
            </td>

        </tr>
    </table>


    @if(!empty($order->external_notes))
        <table align="left" width="100%" cellspacing="0" cellpadding="0">
            <thead>
                <tr style=" background-color: #353535; padding: 2px 15px;">
                    <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="fs-16 ">
                        NOTE
                    </th>
                </tr>
            </thead>
        </table>
        <div style="padding: 10px 15px 10px 25px;">
            <p class="fs-12 text-dark">{!! $order->external_notes !!}</p>
        </div>
    @endif

    <table align="left" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr style=" background-color: #353535; padding: 2px 15px;">
                <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="fs-16 ">
                    EVENT INFORMATION
                </th>
            </tr>
        </thead>
    </table>
    <div style="padding: 10px 15px 10px 25px;">
        <p class="fs-12 text-dark">{!! $order->tour->event->description !!}</p>
    </div>
    <table align="left" width="100%" cellspacing="0" cellpadding="0">
        <thead>
            <tr style=" background-color: #353535; padding: 2px 15px;">
                <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="fs-16 ">
                    TERMS & CONDITIONS
                </th>
            </tr>
        </thead>
    </table>
    <div style="padding: 10px 15px 0 25px;">
        <p class="fs-12 text-dark">{!! $tour->terms !!}</p>
    </div>

    <div style="padding: 10px 15px 0 25px;">
        <p class="fs-12 text-dark">{!! $tour->invoice_footer !!}</p>
    </div>
</body>
</html>
