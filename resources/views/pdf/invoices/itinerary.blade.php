@php /** @var \App\Repository\Storage\Itinerary\Itinerary $itinerary */ @endphp

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

    <style type="text/css"><?php include(public_path() . '/css/kpt.css') ?></style>
    <title>{{ $itinerary->package }} Itinerary | {{ $itinerary->reference }}</title>
</head>

<body class="body">
    <!-- Header -->
    <table class="header full-wrap">
        <!-- Logos -->
        <tr>
            <td class="logos">
                <table class="logo-table">
                    <tr>
                        <td class="text-left v-top">
                            <table class="brand-logo-table">
                                <tr>
                                    <td>
                                        <img src="{{img_to_b64($itinerary->brand->logo)}}" alt="{{ $itinerary->brand->name }}">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td class="circle-td">
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
        <!-- Order Information -->
        <tr>
            <td class="text-left v-top">
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
                                            {{ strtoupper($itinerary->package) }}
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
                                            {{$itinerary->reference}}
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
    </table>

    <!-- Itinerary Header -->
    <table class="divider">
        <thead>
            <tr>
                <th class="text fs-16">
                    TRIP ITINERARY AND INCLUSION
                </th>
            </tr>
        </thead>
    </table>

    <!-- Components -->
    @php $day = 0; @endphp
    @foreach($order->repository->getItineraryItems() as $date => $items)
        @php
            $date = \Carbon\Carbon::createFromTimestamp($date);
            $day += isset($previous) ? diff_in_nights($date, $previous) : 1;
            $previous = $date;
        @endphp

        <table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="background-color:#ffffff; padding: 5px 0 10px 0;" class="full-wrap">
            <tr>
                <td colspan="2" align="left">
                    <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 300px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;" class="fs-16 text-dark ">
                        DAY {{ $day }} - {{ $date->format('l jS F Y') }}
                    </div>
                </td>
            </tr>
        </table>

        @php /** @var \App\Repository\Storage\Itinerary\ItineraryItem $item */ @endphp
        @foreach($items as $item)
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="background-color:#ffffff; padding: 5px 0 10px 0; page-break-inside: avoid;" class="full-wrap">
                <tr>
                    <td align="left" colspan="2" style="padding: 0 40px; color: #E95B15; font-size: 11pt;" class="fs-16 ">
                        {{ strtoupper($item->type) }}
                    </td>
                </tr>
                @foreach($item->details as $key => $value)
                    <tr>
                        <td align="left" width="75" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                            {{ strtoupper($key) }}:
                        </td>
                        <td align="left" valign="top" style="padding: 0 40px; max-width: 100%;" class="fs-12 text-dark">
                            {!! $value !!}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td align="left" width="75" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                        QUANTITY:
                    </td>
                    <td align="left" valign="top" style="padding: 0 40px; max-width: 100%;" class="fs-12 text-dark">
                        {{ $item->quantity }}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="75" valign="top" style="padding: 0 40px;" class="fs-12 text-dark">
                        DATES:
                    </td>
                    <td align="left" valign="top" style="padding: 0 40px; max-width: 100%;" class="fs-12 text-dark">
                        {{ f_datetime($item->start) }} to {{ f_datetime($item->end) }}
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">&nbsp;</td>
                    <td class="empty-cell">&nbsp;</td>
                </tr>
            </table>
        @endforeach
    @endforeach

    @if(!empty($order->external_notes))
        <div style="page-break-inside: avoid">
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text fs-16">
                            NOTES
                        </th>
                    </tr>
                </thead>
            </table>
            <div style="padding: 10px 15px 10px 25px;">
                <p class="fs-12 text-dark">{!! $order->external_notes !!}</p>
            </div>
        </div>
    @endif

    <div style="page-break-inside: avoid">
        <table class="divider">
            <thead>
                <tr>
                    <th class="text fs-16">
                        EVENT INFORMATION
                    </th>
                </tr>
            </thead>
        </table>
        <div style="padding: 10px 15px 10px 25px;">
            <p class="fs-12 text-dark">{!! $order->tour->event?->description ?? $order->tour->description !!}</p>
        </div>
    </div>
    <div style="page-break-inside: avoid">
        <table class="divider">
            <thead>
                <tr>
                    <th class="text fs-16">
                        FINAL DETAILS
                    </th>
                </tr>
            </thead>
        </table>
        <div style="padding: 10px 15px 0 25px;">
            <p class="fs-12 text-dark">{!! $order->invoice_footer !!}</p>
        </div>
    </div>
</body>
</html>
