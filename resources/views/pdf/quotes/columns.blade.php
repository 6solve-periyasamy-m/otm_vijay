@php
/**
 * @var \App\Models\Quote\SentQuote $sent
*/
use App\Models\Helper\QuoteStatus;
$quote = $sent->built;
$travelling = $sent->free;
$paying = $sent->paid;
$brand = $sent->quote->brand;

@endphp

@php
if (!empty($quote->event->image_url)){
    $evenImg = $quote->event->image_url;
} else{
    $evenImg = 'images/default_image.png';
}
@endphpa

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Quote - {{ $quote->reference }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />
    <style type="text/css">
        @page {margin: 0px; padding: 0px;}
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
                                        <img src="{{ img_to_b64($brand->image_path) }}" alt="{{ $brand->name }}" width="300" style="display: block;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="310" align="center" valign="top">
                            <table width="100%" align="center" border="0" cellspacing="0" style="margin: 10px auto; text-align: center;" cellpadding="0">
                                <tr>
                                    <td align="center" valign="center" class="oc_black" style="border-radius: 30px; padding: 20px 10px; font-weight: normal; background-color: #ffffff; color: #E95B15; margin: 0 auto; outline: 2px solid #E95B15; font-size: 15pt; min-width: 160px; max-width: 160px; display: block;">
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
                                            QUOTE REFERENCE: {{ $quote->ref }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="oc_f12" style="padding: 10px 15px 0px 25px; color: #353535;">
                                            CUSTOMER DETAILS
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff;" class="oc_f12">
                                            NAME: {{ $quote->leadTraveller->customer->first_name }} {{ $quote->leadTraveller->customer->last_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff;" class="oc_f12">
                                            PHONE: {{ $quote->leadTraveller->customer->mobile_number }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff;" class="oc_f12">
                                            EMAIL: 
                                            <a href="mailto:{{ $quote->leadTraveller->customer->email_address }}">
                                                {{ $quote->leadTraveller->customer->email_address }}
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
                                    <tr>
                                        <td colspan="2" class="oc_f12" style="padding: 10px 15px 0px 25px; color: #353535;">
                                            AGENT DETAILS
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff;" class="oc_f12">
                                            NAME: {{ $brand->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff;" class="oc_f12">
                                            EMAIL: 
                                            <a href="mailto:{{ $brand->email ?? setting('company.contact.email', 'Email not set') }}">
                                                {{ $brand->email ??  setting('company.contact.email', 'Email not set') }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700; color: #ffffff;" class="oc_f12">
                                            DATE CREATED: {{ \Carbon\Carbon::parse($brand->created_at)->format('d F Y') }}
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
                                        <img src="{{img_to_b64($evenImg)}}" alt="{{ isset($quote->event->name) ? $quote->event->name : '' }}" width="100%" style="display: block; height: 100%; max-height: 350px; object-fit: cover">
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
                        <td width="50%" align="left" valign="top" style="background-color: #FBDED0;">
                            <table align="left" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            QUOTE NAME:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                            {{ $quote->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            TRAVEL DATES:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                            {{ \Carbon\Carbon::parse($quote->date_from)->format('d F Y') }} - {{ \Carbon\Carbon::parse($quote->date_to)->format('d F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            EVENT:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                            {{ isset($quote->event->name) ? $quote->event->name : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="120" align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12">&nbsp;</td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;">&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td width="50%" align="right" valign="top" style="background-color: #FBDED0;">
                            <table align="left" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            TOTAL NUMBER OF PERSONS:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                            {{ $paying }} Adult(s)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            LEAD GUEST:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                        {{ $quote->leadTraveller->customer->first_name }} {{ $quote->leadTraveller->customer->last_name }}
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12 oc_lblack">
                                            OTHER GUESTS:
                                        </td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;" class="oc_f12 oc_lblack">
                                            Test
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px; font-weight: 700;" class="oc_f12">&nbsp;</td>
                                        <td align="left" valign="top" style="padding: 10px 15px 0px 25px;">&nbsp;</td>
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
                            <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="oc_f16">
                                TRIP ITINERARY AND INCLUSIONS
                            </th>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">&nbsp;</td>
        </tr>
        @if(sizeof($quote->flights))
        <tr>
            <td align="left" valign="top">
                <table align="left" width="60%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" width="150" style="padding: 10px 40px; color: #ffffff; background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px;" class="oc_f12 oc_lblack">
                        ARRIVAL TRANSFER
                        </td>
                        <td align="right" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">&nbsp;</td>
                    </tr>
                </table>
                <table align="right" width="40%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td align="left" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                            <td width="180" align="center" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" style="">
                <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td align="left" valign="top">
                            <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tbody>
                                    @foreach($quote->repository->getFlightsForInvoice() as $component)
                                    @if($component->get()->flight_type != 'Inbound')
                                    <tr>
                                        <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                            DATE: 
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                      
                                        {{ f_datetime($component->getInventory()->getStartTime()) }} to {{ f_datetime($component->getInventory()->getEndTime()) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                        DESCRIPTION:
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                        {{ $component->getShortDescription() }} ({{ $component->priceShown() ? f_currency($component->getSalesPrice()) : 'Included'}})
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                        QUANTITY:
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                        {{ $paying + $travelling }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
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
        @endif
        @if(sizeof($quote->repository->getTransportForInvoice()))
        <tr>
            <td align="left" valign="top">
                <table align="left" width="60%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" width="150" style="padding: 10px 40px; color: #ffffff; background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px;" class="oc_f12 oc_lblack">
                            TRANSFER
                        </td>
                        <td align="right" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">&nbsp;</td>
                    </tr>
                </table>
                <table align="right" width="40%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td align="left" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                            <td width="180" align="center" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" style="">
                <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td align="left" valign="top">
                            <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tbody>
                                    @foreach($quote->repository->getTransportForInvoice() as $component)
                                    <tr>
                                        <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                            DATE:
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                        {{ f_datetime($component->getInventory()->getStartTime()) }} to {{ f_datetime($component->getInventory()->getEndTime()) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                        DESCRIPTION:
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                        {{ $component->getShortDescription() }} ({{ $component->priceShown() ? f_currency($component->getSalesPrice()) : 'Included'}})
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                        QUANTITY:
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                        {{ $paying + $travelling }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif
        @if(sizeof($quote->accommodation))
        <tr>
            <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <table align="left" width="60%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" width="150" style="padding: 10px 40px; color: #ffffff; background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px;" class="oc_f12 oc_lblack">
                            ACCOMMODATION
                        </td>
                        <td align="right" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">&nbsp;</td>
                    </tr>
                </table>
                <table align="right" width="40%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td align="left" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                            <td width="180" align="center" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        
            @foreach($quote->repository->getAccommodationForInvoice() as $component)
                @php
                    $checIn = DateTime::createFromFormat('d/m/Y H:i', f_datetime($component->getInventory()->getStartTime()))->format('d M Y | h:i A');
                    $checkOut = DateTime::createFromFormat('d/m/Y H:i', f_datetime($component->getInventory()->getEndTime()))->format('d M Y | h:i A');
                @endphp
                <tr>
                    <td align="left" valign="top" style="">
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <td align="left" valign="top">
                                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                                    CHECK IN:
                                                </td>
                                                <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                                    {{ $checIn }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                                    CHECK OUT:
                                                </td>
                                                <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                                    {{ $checkOut }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                                    HOTEL NAME:
                                                </td>
                                                <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                                    {{ $component->getHotelName() }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                                    ADDRESS:
                                                </td>
                                                <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                                    {{$component->getHotelAddress()}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                                    ROOM TYPE:
                                                </td>
                                                <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                                {{$component->getRoomType()}}
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
                    <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                        &nbsp;
                    </td>
                </tr>
            @endforeach
        @endif
        @if(sizeof($quote->activities))
        <tr>
            <td align="left" valign="top">
                <table align="left" width="60%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" width="150" style="padding: 10px 40px; color: #ffffff; background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px;" class="oc_f12 oc_lblack">
                            EVENT/S
                        </td>
                        <td align="right" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">&nbsp;</td>
                    </tr>
                </table>
                <table align="right" width="40%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td align="left" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                            <td width="180" align="center" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        
            @foreach($quote->repository->getActivitiesForInvoice() as $component)
                <tr>
                    <td align="left" valign="top">
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <td align="left" valign="top">
                                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                                    DATE:
                                                </td>
                                                <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                                    {{ \Carbon\Carbon::parse($quote->event->starts_at)->format('d F Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                                    EVENT:
                                                </td>
                                                <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                                    {{ $quote->event->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                                    DESCRIPTION:
                                                </td>
                                                <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                                    {{ $quote->event->description }}
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
                    <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                        &nbsp;
                    </td>
                </tr>
            @endforeach
        @endif
       
        @if(sizeof($quote->flights))
        <tr>
            <td align="left" valign="top">
                <table align="left" width="60%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" width="150" style="padding: 10px 40px; color: #ffffff; background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px;" class="oc_f12 oc_lblack">
                            DEPARTURE TRANSFER
                        </td>
                        <td  align="right" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">&nbsp;</td>
                    </tr>
                </table>
                <table align="right" width="40%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td align="left" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                            <td width="180" align="center" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" style="">
                <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td align="left" valign="top">
                            <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tbody>
                                    @foreach($quote->repository->getFlightsForInvoice() as $component)
                                    @if($component->get()->flight_type == 'Inbound')
                                    <tr>
                                        <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                            DATE: 
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                    
                                        {{ f_datetime($component->getInventory()->getStartTime()) }} to {{ f_datetime($component->getInventory()->getEndTime()) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                        DESCRIPTION:
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                        {{ $component->getShortDescription() }} ({{ $component->priceShown() ? f_currency($component->getSalesPrice()) : 'Included'}})
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="120" valign="top" style="padding: 2px 10px 2px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                        QUANTITY:
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                        {{ $paying + $travelling }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif
        <!-- Notes Section -->
        @if(!empty($quote->external_notes))
        <tr>
            <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <table align="left" width="60%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" width="150" style="padding: 10px 40px; color: #ffffff; background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px;" class="oc_f12 oc_lblack">
                            NOTE
                        </td>
                        <td  align="right" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">&nbsp;</td>
                    </tr>
                </table>
                <table align="right" width="40%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td align="left" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                            <td width="180" align="center" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                            {!! $quote->external_notes !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                <table align="left" width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr style=" background-color: #353535; padding: 2px 15px;">
                            <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="oc_f16">
                                PAYMENT SUMMARY
                            </th>
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
        <!-- Installments Section -->
        @if($quote->deposit > 0 || sizeof($quote->installments))
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
                                {{ f_currency($quote->deposit * $paying) }}
                            </td>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                With Order
                            </td>
                        </tr>
                        @foreach($quote->installments as $installment)
                        <tr>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                Instalment
                            </td>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                {{ f_currency($installment->amount * $paying) }}
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
                                {{ f_currency($quote->repository->getRemaining($paying)) }}
                            </td>
                            <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                {{ f_date($quote->final_payment) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <table align="left" width="60%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" width="150" style="padding: 10px 40px; color: #ffffff; background-color: #E95B15; border-radius: 0 30px 30px 0; max-width: 200px;" class="oc_f12 oc_lblack">
                            PAYMENT METHOD
                        </td>
                        <td align="right" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">&nbsp;</td>
                    </tr>
                </table>
                <table align="right" width="40%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td align="left" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                            <td width="180" align="center" valign="top" style="padding: 2px 15px;" class="oc_f14 oc_lblack">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top">
                <table align="left" width="60%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" width="150" style="padding: 0px 40px; font-weight: bold;" class="oc_f12">
                            BANK TRANSFER
                        </td>
                        <td align="right" valign="top" style="padding: 2px 15px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top" style="">
                <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td align="left" valign="top">
                            <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="left" width="120" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                            Keith Prowse Travel PTY LTD
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                            SWIFT: WPACAU2S
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="150" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                            ABN: 31 003 276 775
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                            BANK: Westpac
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="150" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                            BSB: 032 298
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                            BRANCH: Crows Nest
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="150" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                            ACC: 540726
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                            REFERENCE NUMBER: KPAD106032
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="150" valign="top" style="padding: 0px 40px;">&nbsp;</td>
                                        <td align="left" valign="top" style="padding: 0px 40px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="150" valign="top" style="padding: 0px 40px; font-weight: bold;" class="oc_f12 oc_lblack">PAYMENT GATE
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px; font-weight: bold;" class="oc_f12 oc_lblack">
                                            CREDIT CARD
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="150" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                            KPTVL   
                                        </td>
                                        <td align="left" valign="top" style="padding: 0px 40px;" class="oc_f12 oc_lblack">
                                            If paying by credit card, follow this link
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
            <td colspan="2" align="left" valign="top" style="padding: 10px 15px 0px 25px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                <table align="left" width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr style=" background-color: #353535; padding: 2px 15px;">
                            <th align="left" valign="top" style="padding: 10px 15px; color: #ffffff; font-weight: 700;" class="oc_f16">
                                TERMS & CONDITIONS
                            </th>
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
        <tr>
            <td colspan="2" align="left" valign="top" class="oc_f12 oc_lblack" style="padding: 10px 15px 0px 25px;"> 
                {!! $quote->terms !!}
            </td>
        </tr>
    </table>
</body>
</html>