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
    <div class="logos">
        <table class="logo-table">
            <tr>
                <td class="text-left v-top">
                    <table class="brand-logo-table">
                        <tr>
                            <td>
                                <img src="{{img_to_b64($itinerary->brand->logo)}}" alt="{{ $itinerary->brand->name }}" />
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="circle-td">
                    <table class="cell-padding-0 document-logo-table debug">
                        <tr>
                            <td class="document-logo">
                                TRAVEL ITINERARY
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <!-- Header -->
    <table class="header">
        <!-- Order Information -->
        <tr>
            <td align="left" valign="top" colspan="2">
                <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td width="50%" align="left" valign="top" style="background-color: #E95B15;">
                            <table align="left" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td class="text-left v-top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="reference">
                                            REFERENCE: {{ $itinerary->reference }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left v-top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="header-detail-title">
                                            CUSTOMER DETAILS
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="header-detail-data">
                                            NAME: {{ $itinerary->booker->full_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="header-detail-data">
                                            PHONE: {{ $itinerary->booker->mobile_number }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="header-detail-data">
                                            EMAIL: <a href="mailto:{{ $itinerary->booker->email_address }}">{{ $itinerary->booker->email_address }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left v-top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left v-top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="header-detail-title">
                                            AGENT DETAILS
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="header-detail-data">
                                            NAME: {{ $itinerary->brand->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="header-detail-data">
                                            EMAIL:
                                            <a href="mailto:{{ $itinerary->brand->email ?? setting('company.contact.email', 'Email not set') }}">
                                                {{ $itinerary->brand->email ??  setting('company.contact.email', 'Email not set') }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="header-detail-data">
                                            DATE CREATED: {{ $itinerary->brand->created_at->format('d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left v-top">&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td class="header-image-td">
                            <table class="header-image-table">
                                <tr>
                                    <td class="text-left v-top">
                                        <img src="{{ $itinerary->image }}" alt="{{ $itinerary->package }}" class="header-image">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="lower-header-td text-left">
                <table class="lower-header-table">
                    <tbody>
                        <tr>
                            <td class="lower-header-detail-title">
                                PACKAGE:
                            </td>
                            <td class="lower-header-detail">
                                {{ $itinerary->package }}
                            </td>
                        </tr>
                        <tr>
                            <td class="lower-header-detail-title">
                                TRAVEL DATES:
                            </td>
                            <td class="lower-header-detail">
                                {{ $itinerary->start->format('d F Y') }} - {{ $itinerary->end->format('d F Y') }}
                            </td>
                        </tr>
                        @if(!empty($itinerary->event))
                            <tr>
                                <td class="lower-header-detail-title">
                                    EVENT:
                                </td>
                                <td class="lower-header-detail">
                                    {{ $itinerary->event }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </td>
            <td class="lower-header-td text-right">
                <table class="lower-header-table">
                    <tbody>
                        <tr>
                            <td class="lower-header-detail-title">
                                TOTAL NUMBER OF PERSONS:
                            </td>
                            <td class="lower-header-detail">
                                {{ $itinerary->travellerCount }} Adult(s)
                            </td>
                        </tr>
                        <tr>
                            <td class="lower-header-detail-title">
                                LEAD GUEST:
                            </td>
                            <td class="lower-header-detail">
                                {{ $itinerary->booker->full_name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- Itinerary Divider -->
    <table class="divider">
        <thead>
            <tr>
                <th class="text">
                    TRIP ITINERARY AND INCLUSION
                </th>
            </tr>
        </thead>
    </table>

    <!-- Components -->
    @php $day = 0; @endphp
    @foreach($itinerary->items as $date => $items)
        @php
            $date = \Carbon\Carbon::createFromTimestamp($date);
            $day += isset($previous) ? diff_in_nights($date, $previous) : 1;
            $previous = $date;
        @endphp

        <table class="date-header">
            <tr>
                <td colspan="2" class="text-left">
                    <div class="date-content">
                        DAY {{ $day }} - {{ $date->format('l jS F Y') }}
                    </div>
                </td>
            </tr>
        </table>

        @php /** @var \App\Repository\Storage\Itinerary\ItineraryItem $item */ @endphp
        @foreach($items as $item)
            <table class="item-table">
                <tr>
                    <td class="item-title">
                        {{ strtoupper($item->type) }}
                    </td>
                </tr>
                @foreach($item->details as $key => $value)
                    <tr>
                        <td class="item-header">
                            {{ strtoupper($key) }}:
                        </td>
                        <td class="item-detail">
                            {!! $value !!}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="item-header">
                        QUANTITY:
                    </td>
                    <td class="item-detail">
                        {{ $item->quantity }}
                    </td>
                </tr>
                <tr>
                    <td class="item-header">
                        DATES:
                    </td>
                    <td class="item-detail">
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
        <div class="avoid-break">
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text">
                            NOTES
                        </th>
                    </tr>
                </thead>
            </table>
            <div class="text-section">
                <p>{!! $order->external_notes !!}</p>
            </div>
        </div>
    @endif

    @if(!empty($itinerary->description))
        <div class="avoid-break">
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text">
                            EVENT INFORMATION
                        </th>
                    </tr>
                </thead>
            </table>
            <div class="text-section">
                <p>{!! $itinerary->description !!}</p>
            </div>
        </div>
   @endif

    @if(!empty($itinerary->footer))
        <div class="avoid-break">
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text">
                            FINAL DETAILS
                        </th>
                    </tr>
                </thead>
            </table>
            <div  class="text-section">
                <p>{!! $itinerary->footer !!}</p>
            </div>
        </div>
    @endif
</body>
</html>
