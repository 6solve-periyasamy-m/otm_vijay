@php
    /**
     * @var SentQuote $sent
    */
    use App\Models\Quote\SentQuote;use Carbon\Carbon;$quote = $sent->built;
    $travelling = $sent->free;
    $paying = $sent->paid;
    $brand = $sent->quote->brand;
    $showInbound = 0;
    $showOutbound = 0;
@endphp

@php
    if (!empty($quote->event->image_url)){
        $evenImg = $quote->event->image_url;
    } else{
        $evenImg = 'images/default_image.png';
    }

@endphp

        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Quote - {{ $quote->reference }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />
    <style type="text/css"><?php include(public_path() . '/css/kpt.css') ?></style>
</head>

<body class="body" style="padding:0; margin:0 auto !important; display:block !important; min-width:100% !important; width:100% !important; background:#ffffff; -webkit-text-size-adjust:none">
    <!-- Header -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="background-color:#ffffff; padding: 35px 0;" class="full-wrap">
        <tr>
            <td valign="top" style="padding-bottom: 10px;" colspan="2">
                <table width="100%" border="0" cellspacing="0">
                    <tr>
                        <td align="left" valign="top">
                            <table align="left" width="100%" border="0" cellspacing="0">
                                <tr>
                                    <td align="left" valign="top" style="padding: 0 20px;">
                                        <img src="{{ img_to_b64($brand->image_path) }}" alt="{{ $brand->name }}" width="100%" style="display: block; max-width: 160px">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="310" align="center" valign="top">
                            <table width="100%" align="center" border="0" cellspacing="0"
                                   style="margin: 10px auto; text-align: center;" cellpadding="0">
                                <tr>
                                    <td align="center" valign="center" class="text-dark"
                                        style="border-radius: 30px; padding: 20px 10px; font-weight: normal; background-color: #ffffff; color: #E95B15; margin: 0 auto; outline: 2px solid #E95B15; font-size: 15pt; min-width: 160px; max-width: 160px; display: block;">
                                        QUOTE
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" colspan="2">
                <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td width="50%" align="left" valign="top" style="background-color: #E95B15;">
                            <table align="left" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="200" align="left" valign="top"
                                            style="padding: 10px 15px 10px 25px; color: #ffffff; line-height: 20px; background-color: #353535; border-radius: 0 30px 30px 0;"
                                            class="fs-16">
                                            QUOTE REFERENCE: {{ $quote->ref }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="fs-12"
                                            style="padding: 10px 15px 0px 25px; color: #353535; font-weight: 700; ">
                                            CUSTOMER DETAILS
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top"
                                            style="padding: 10px 15px 0px 25px; line-height:10px; color: #ffffff;"
                                            class="fs-12">
                                            NAME: {{ $quote->leadTraveller->customer->first_name }} {{ $quote->leadTraveller->customer->last_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top"
                                            style="padding: 10px 15px 0px 25px; line-height:10px; color: #ffffff;"
                                            class="fs-12">
                                            PHONE: {{ $quote->leadTraveller->customer->mobile_number }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top"
                                            style="padding: 10px 15px 0px 25px; line-height:10px; color: #ffffff;"
                                            class="fs-12">
                                            EMAIL:
                                            <a href="mailto:{{ $quote->leadTraveller->customer->email_address }}">
                                                {{ $quote->leadTraveller->customer->email_address }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="120" align="left" valign="top"
                                            style="padding: 10px 15px 0px 25px; line-height:10px;" class="fs-12">
                                            &nbsp;
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;">&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" class="fs-12"
                                            style="padding: 10px 15px 0px 25px; color: #353535; font-weight: 700; ">
                                            AGENT DETAILS
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top"
                                            style="padding: 10px 15px 0px 25px; line-height:10px;color: #ffffff;"
                                            class="fs-12">
                                            NAME: {{ $brand->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top"
                                            style="padding: 10px 15px 0px 25px; line-height:10px;color: #ffffff;"
                                            class="fs-12">
                                            EMAIL:
                                            <a href="mailto:{{ $brand->email ?? setting('company.contact.email', 'Email not set') }}">
                                                {{ $brand->email ??  setting('company.contact.email', 'Email not set') }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top"
                                            style="padding: 10px 15px 0px 25px; line-height:10px;color: #ffffff;"
                                            class="fs-12">
                                            DATE
                                            CREATED: {{ Carbon::parse($brand->created_at)->format('d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="120" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                                            &nbsp;
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;">&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td width="50%" align="right" valign="top">
                            <table align="left" width="100%" border="0" cellspacing="0">
                                <tr>
                                    <td align="left" valign="top">
                                        <img src="{{img_to_b64($evenImg)}}"
                                             alt="{{ $quote->event?->name ?? '' }}" width="100%"
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
            <td width="50%" align="left" valign="top" style="background-color: #FBDED0; padding: 20px 0;">
                <table align="left" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td align="left" width="75" valign="top"
                                style="padding: 10px 15px 0px 25px; font-weight: 700; line-height:15px;"
                                class="fs-12 text-dark">
                                QUOTE NAME:
                            </td>
                            <td align="left" valign="top" style="padding: 10px 15px 0px 25px; line-height:15px;"
                                class="fs-12 text-dark">
                                {{ $quote->name }}
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="75" valign="top"
                                style="padding: 10px 15px 0px 25px; font-weight: 700; line-height:10px;"
                                class="fs-12 text-dark">
                                TRAVEL DATES:
                            </td>
                            <td align="left" valign="top" style="padding: 10px 15px 0px 25px; line-height:10px;"
                                class="fs-12 text-dark">
                                {{ Carbon::parse($quote->date_from)->format('d F Y') }}
                                - {{ Carbon::parse($quote->date_to)->format('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="75" valign="top"
                                style="padding: 10px 15px 10px 25px; font-weight: 700; line-height:10px;"
                                class="fs-12 text-dark">
                                EVENT:
                            </td>
                            <td align="left" valign="top" style="padding: 10px 15px 0px 25px; line-height:10px;"
                                class="fs-12 text-dark">
                                {{ $quote->event?->name ?? '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td width="50%" align="right" valign="top" style="background-color: #FBDED0; padding: 20px 0;">
                <table align="left" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td align="left" width="50%" valign="top"
                                style="padding: 10px 15px 0px 25px; font-weight: 700; line-height:10px;"
                                class="fs-12 text-dark">
                                TOTAL NUMBER OF PERSONS:
                            </td>
                            <td align="left" valign="top" style="padding: 10px 15px 0px 25px; line-height:10px;"
                                class="fs-12 text-dark">
                                {{ $paying }} Adult(s)
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" width="50%"
                                style="padding: 10px 15px 0px 25px; font-weight: 700; line-height:10px;"
                                class="fs-12 text-dark">
                                LEAD GUEST:
                            </td>
                            <td align="left" valign="top" style="padding: 10px 15px 0px 25px; line-height:10px;"
                                class="fs-12 text-dark">
                                {{ $quote->leadTraveller->customer->first_name }} {{ $quote->leadTraveller->customer->last_name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top" colspan="2">
                <table align="left" width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr style=" background-color: #353535; padding: 2px 15px;">
                            <th align="left" valign="top" style="padding: 10px 25px; color: #ffffff; font-weight: 700;"
                                class="fs-16">
                                TRIP ITINERARY AND INCLUSIONS
                            </th>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
    </table>

    @if(count($quote->flights))
        <table class="full-wrap quote-footer-table">
            <tr>
                <td colspan="2" align="left">
                    <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;"
                         class="fs-16 text-dark">
                        AIRPORT TRANSFER
                    </div>
                </td>
            </tr>
        </table>

        <table class="full-wrap quote-footer-table">
            @foreach($quote->repository->getFlightsForInvoice() as $component)
                @if($component->get()->flight_type !== 'Inbound')
                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            DATE:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">

                            {{ $component->getInventory()->getStartTime()->format('d M Y | h:i A') }}
                            to {{ $component->getInventory()->getEndTime()->format('d M Y | h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            DESCRIPTION:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {{ $component->getShortDescription() }}
                            ({{ $component->priceShown() ? f_currency($component->getSalesPrice()) : 'Included'}})
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            QUANTITY:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {{ $paying + $travelling }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    @endif
    @if(count($quote->repository->getTransportForInvoice()))
        <table class="full-wrap quote-footer-table">
            <tr>
                <td colspan="2" align="left">
                    <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;"
                         class="fs-16 text-dark">
                        TRANSFER
                    </div>
                </td>
            </tr>
        </table>

        <table class="full-wrap quote-footer-table">
            @foreach($quote->repository->getTransportForInvoice() as $component)
                <tr>
                    <td align="left" width="80" valign="top"
                        style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                        DATE:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ $component->getInventory()->getStartTime()->format('d M Y | h:i A') }}
                        to {{ $component->getInventory()->getEndTime()->format('d M Y | h:i A') }}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="80" valign="top"
                        style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                        DESCRIPTION:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ $component->getShortDescription() }}
                        ({{ $component->priceShown() ? f_currency($component->getSalesPrice()) : 'Included'}})
                    </td>
                </tr>
                <tr>
                    <td align="left" width="80" valign="top"
                        style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                        QUANTITY:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ $paying + $travelling }}
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
    @if(count($quote->accommodation))
        <table class="full-wrap quote-footer-table">
            <tr>
                <td colspan="2" align="left">
                    <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;"
                         class="fs-16 text-dark">
                        ACCOMMODATION
                    </div>
                </td>
            </tr>
        </table>
        <table class="full-wrap quote-footer-table">
            @foreach($quote->repository->getAccommodationForInvoice() as $component)
                <tr>
                    <td align="left" width="80" valign="top"
                        style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                        CHECK IN:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ $component->getInventory()->getStartTime()->format('d M Y | h:i A') }}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="80" valign="top"
                        style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                        CHECK OUT:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ $component->getInventory()->getEndTime()->format('d M Y | h:i A') }}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="80" valign="top"
                        style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                        HOTEL NAME:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{ $component->getHotelName() }}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="80" valign="top"
                        style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                        ADDRESS:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{$component->getHotelAddress()}}
                    </td>
                </tr>
                <tr>
                    <td align="left" width="80" valign="top"
                        style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                        ROOM TYPE:
                    </td>
                    <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                        {{$component->getRoomType()}}
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
    @php
        $is_events_heading = 0;
    @endphp
    @foreach($quote->repository->getActivitiesForInvoice() as $component)
        @if($component->getActivityData()->activity_category === 1)
            @php
                $is_events_heading = 1;
            @endphp
        @endif
    @endforeach
    @if(!empty($quote->event?->name) || $is_events_heading == 1)
        <table class="full-wrap quote-footer-table">
            <tr>
                <td colspan="2" align="left">
                    <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;"
                         class="fs-16 text-dark">
                        EVENT/S
                    </div>
                </td>
            </tr>
        </table>
    @endif

    @if(!empty($quote->event?->name))
        <table class="full-wrap quote-footer-table">

            <tr>
                <td align="left" width="80" valign="top"
                    style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                    DATE:
                </td>
                <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                    {{ date('d M Y', strtotime($quote->event->starts_at)) }}
                    to {{ date('d M Y', strtotime($quote->event->ends_at)) }}
                </td>
            </tr>
            <tr>
                <td align="left" width="80" valign="top"
                    style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                    EVENT:
                </td>
                <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                    {{ $quote->event->name }}
                </td>
            </tr>
            <tr>
                <td align="left" width="80" valign="top"
                    style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;" class="fs-12 text-dark">
                    DESCRIPTION:
                </td>
                <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;" class="fs-12 text-dark">
                    {{ $quote->event->description }}
                </td>
            </tr>
        </table>
    @endif

    @if(count($quote->activities))
        @foreach($quote->repository->getActivitiesForInvoice() as $component)
            @if($component->getActivityData()->activity_category == 1 )

                @php

                    $dateFrom = DateTime::createFromFormat('d/m/Y H:i', f_datetime($component->getInventory()->getStartTime()))->format('d M Y | h:i A');
                    $dateTo = DateTime::createFromFormat('d/m/Y H:i', f_datetime($component->getInventory()->getEndTime()))->format('d M Y | h:i A');
                @endphp
                <table class="full-wrap quote-footer-table">

                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            DATE:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {{ $dateFrom }} to {{ $dateTo }}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            EVENT:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {{ $component->getActivityData()->name }}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            DESCRIPTION:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {!! $component->getActivityData()->description !!}
                        </td>
                    </tr>
                </table>
            @endif
        @endforeach

    @endif

    @if(count($quote->repository->getActivitiesForInvoice()))

        @foreach($quote->repository->getActivitiesForInvoice() as $component)
            @if($component->getActivityData()->activity_category == 0 )

                @php
                    $activeInclusion = false;
                @endphp

                @foreach($quote->repository->getActivitiesForInvoice() as $component)
                    @if($component->getActivityData()->activity_category == 0 )
                        @php
                            $activeInclusion = true;
                        @endphp
                    @endif
                @endforeach

                @if($activeInclusion == true)
                    <table class="full-wrap quote-footer-table">
                        <tr>
                            <td colspan="2" align="left">
                                <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;"
                                     class="fs-16 text-dark">
                                    INCLUSION
                                </div>
                            </td>
                        </tr>
                    </table>
                @endif


                @php

                    $dateFrom = DateTime::createFromFormat('d/m/Y H:i', f_datetime($component->getInventory()->getStartTime()))->format('d M Y | h:i A');
                    $dateTo = DateTime::createFromFormat('d/m/Y H:i', f_datetime($component->getInventory()->getEndTime()))->format('d M Y | h:i A');
                @endphp
                <table class="full-wrap quote-footer-table">

                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            DATE:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {{ $dateFrom }} to {{ $dateTo }}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            EVENT:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {{ $component->getActivityData()->name }}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            DESCRIPTION:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {!! $component->getActivityData()->description !!}
                        </td>
                    </tr>
                </table>
            @endif
        @endforeach

    @endif

    @foreach($quote->repository->getFlightsForInvoice() as $component)
        @if($component->get()->flight_type == 'Inbound')
            @php $showInbound = 1; @endphp
        @endif
    @endforeach
    @if($showInbound == 1)
        <table class="full-wrap quote-footer-table">
            <tr>
                <td colspan="2" align="left">
                    <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;"
                         class="fs-16 text-dark">
                        DEPARTURE TRANSFER
                    </div>
                </td>
            </tr>
        </table>
        <table class="full-wrap quote-footer-table">
            @foreach($quote->repository->getFlightsForInvoice() as $component)
                @if($component->get()->flight_type === 'Inbound')
                    @php
                        $dateFrom = DateTime::createFromFormat('d/m/Y H:i', f_datetime($component->getInventory()->getStartTime()))->format('d M Y | h:i A');
                        $dateTo = DateTime::createFromFormat('d/m/Y H:i', f_datetime($component->getInventory()->getEndTime()))->format('d M Y | h:i A');
                    @endphp
                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            DATE:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {{ $dateFrom }} to {{ $dateTo }}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 2px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            DESCRIPTION:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {{ $component->getShortDescription() }}
                            ({{ $component->priceShown() ? f_currency($component->getSalesPrice()) : 'Included'}})
                        </td>
                    </tr>
                    <tr>
                        <td align="left" width="80" valign="top"
                            style="padding: 2px 10px 10px 40px; font-weight: bold; line-height: 10px;"
                            class="fs-12 text-dark">
                            QUANTITY:
                        </td>
                        <td align="left" valign="top" style="padding: 0px 40px; line-height: 10px;"
                            class="fs-12 text-dark">
                            {{ $paying + $travelling }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    @endif
    <!-- Notes Section -->
    @if(!empty($quote->external_notes))
        <table class="full-wrap quote-footer-table">
            <thead>
                <tr style=" background-color: #353535; padding: 2px 15px;">
                    <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="fs-16">
                        EXTERNAL NOTES
                    </th>
                </tr>
            </thead>
        </table>
        <table class="full-wrap quote-footer-table">

            <tr>
                <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td align="left" valign="top">
                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td align="left" valign="top" style="padding: 0px 40px;" class="fs-12 text-dark">
                                {!! $quote->external_notes !!}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                    &nbsp;
                </td>
            </tr>

        </table>
    @endif
    <table class="full-wrap quote-footer-table">
        <thead>
            <tr style=" background-color: #353535; padding: 2px 15px;">
                <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="fs-16">
                    PAYMENT SUMMARY
                </th>
            </tr>
        </thead>
    </table>
    <!-- Installments Section -->
    <table class="full-wrap quote-footer-table">
        <tr>
            <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                <table align="left" width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;"
                                class="fs-12 text-dark">
                                Total (Price per Person)
                            </td>
                            <td align="left" valign="top" style="padding: 0px 40px;" class="fs-12 text-dark">

                                {{f_currency($quote->repository->getPricePerPerson($paying + $travelling)?->price_per_person)}}
                            </td>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                &nbsp;
            </td>
        </tr>
    </table>
    @if($quote->deposit > 0 || count($quote->installments) > 0)
        <table class="quote-footer-table full-wrap">
            <tr>
                <td align="left" valign="top" class="fs-16"
                    style="background-color: #FBDED0; color: #000000; font-weight: bold; padding: 10px 15px 0px 25px;">
                    PAYMENT SCHEDULE
                </td>
            </tr>
            <tr>
                <td align="left" valign="top" style="background-color: #FBDED0; padding: 10px 30px;">
                    <table align="left" border="0" cellspacing="0" cellpadding="0" class="paymentTable" width="100%">
                        <thead>
                            <tr style="background-color: #353535;">
                                <th width="25%" align="left" valign="top"
                                    style="padding: 2px 15px; font-weight: bold; color: #ffffff;" class="fs-16">
                                    INSTALMENTS
                                </th>
                                <th width="25%" align="left" valign="top"
                                    style="padding: 2px 15px; font-weight: bold; color: #ffffff;" class="fs-16">
                                    AMOUNT DUE
                                </th>
                                <th width="25%" align="left" valign="top"
                                    style="padding: 2px 15px; font-weight: bold; color: #ffffff;" class="fs-16">
                                    DATE DUE
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="fs-12 text-dark">
                                    Deposit
                                </td>
                                <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="fs-12 text-dark">
                                    {{ f_currency($quote->deposit * $paying) }}
                                </td>
                                <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="fs-12 text-dark">
                                    With Order
                                </td>
                            </tr>
                            @foreach($quote->installments as $installment)
                                @php
                                    $datePayment = DateTime::createFromFormat('d/m/Y', f_date($installment->due_on))->format('d M Y');
                                @endphp
                                <tr>
                                    <td width="25%" align="left" valign="top" style="padding: 2px 15px;"
                                        class="fs-12 text-dark">
                                        Instalment
                                    </td>
                                    <td width="25%" align="left" valign="top" style="padding: 2px 15px;"
                                        class="fs-12 text-dark">
                                        {{ f_currency($installment->amount * $paying) }}
                                    </td>
                                    <td width="25%" align="left" valign="top" style="padding: 2px 15px;"
                                        class="fs-12 text-dark">
                                        {{ $datePayment }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="fs-12 text-dark">
                                    Remaining
                                </td>
                                <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="fs-12 text-dark">
                                    {{ f_currency($quote->repository->getRemaining($paying)) }}
                                </td>
                                <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="fs-12 text-dark">
                                    {{ DateTime::createFromFormat('d/m/Y', f_date($quote->final_payment))->format('d M Y') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    @endif
    <table class="full-wrap quote-footer-table">
        <tr>
            <td colspan="2" align="left">
                <div style="background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 190px; padding: 10px 25px; margin: 10px 0; color: #ffffff; display: block;"
                     class="fs-16 text-dark">
                    PAYMENT METHOD
                </div>
            </td>
        </tr>
    </table>
    <table class="full-wrap quote-footer-table">
        <tr>
            <td align="left" width="150" style="padding: 5px 25px; font-weight: bold;" class="fs-12">
                BANK TRANSFER
            </td>
            <td align="right" valign="top" style="padding: 0 15px;">&nbsp;</td>
        </tr>
    </table>
    <table class="full-wrap quote-footer-table">
        <tr>
            <td style=" padding: 10px 15px 10px 25px;" class="fs-12">
                {!! setting('company.bank_transfer', '-')  !!}
            </td>
        </tr>
    </table>
    <table class="full-wrap quote-footer-table">
        <thead>
            <tr style=" background-color: #353535; padding: 2px 15px;">
                <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="fs-16">
                    TERMS & CONDITIONS
                </th>
            </tr>
        </thead>
    </table>
    <div style="padding: 10px 15px 10px 25px;">
        <p class="fs-12 text-dark">
            {!! $quote->terms !!}
        </p>
    </div>
</body>
</html>
