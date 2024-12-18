@php
    /** @var \App\Models\Order\Invoice\Invoice $invoice */
    use App\Repository\Storage\Itinerary\ItineraryScheduleType;
@endphp
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

    <style type="text/css">
        /* Define margin-top for all pages  #f35b15 */
        @page {
            margin: 20px 0px 0px 0px; /* Adjust the value as needed */
            padding: 0px;
        }
        /* Override margin-top for the first page */
        @page :first {
            margin-top: 0;
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
         
        .container {
            width: 100%;
            max-width: 796px;
            margin: 0 auto;
            padding: 0px;
        }
        .header, .footer {
            width: 100%;
            margin-bottom: 0px;
        }
        .header td {
            vertical-align: top;
        }
        .header .left-column {
            padding: 32px 20px 32px 35px;
        }
        .header .right-column  {
            padding: 20px 28px 0px 20px;
        }
        .header .right-column p {
            text-align: right;
            font-family: 'Lato', sans-serif;
            font-weight: 500;
            font-size: 10px;
            line-height: 12px;
        }
        .invoice-title h1 {
            text-align: left;
            font-size: 40px;
            margin: 0px;
            font-family: 'Lato', sans-serif;
            font-weight: 600;
            line-height: 53.8px;
            padding-left:35px;
        }
        .items, .payment-options, .totals {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            padding-left:20px;
        }
        .details tr h6{
            font-family: 'Lato', sans-serif;
            font-weight: 700;
            text-align: left;
            color: #F35B15;
            font-size:12px;
            line-height: 10.18px;
            padding: 0px 0px 0px 20px;
            margin-top:0px;
            margin-bottom:8px;
        }
        .details tr td  {
            max-width: 112px;
            padding-right: 32px;
        }
        .details tr td:first-child p, .details tr td:nth-child(2) p {
            font-family: 'Lato', sans-serif;
            font-weight: 400;
            font-size: 12px;
            line-height: 17px;
            padding: 0px 0px 0px 20px !important;
            vertical-align: top;
        }
        .details tr td {
            font-family: 'Lato', sans-serif;
            font-weight: 400;
            font-size: 12px;
            line-height: 17px;
            vertical-align: top;
        }
        .details tr td span {font-weight:600;}
        .details tr p.bg-box-contain.f-1 {
            padding-top:15px!important;
        }
        .details tr p.bg-box-contain.f-3 {
            padding-bottom:15px!important;
        }
        .items th {
            background-color: #f35b15;
            padding:4px 30px;
            font-family: 'Lato', sans-serif;
        }
        .items th h2 {
            font-family: 'Lato', sans-serif;
            font-weight: 700;
            font-size: 16px;
            line-height: 21.33px;
            color: #fff;
            text-align: center;
            padding: 4px 0px 6px 0px;
        }
        .items td {
            padding:3px 30px;
            font-family: 'Lato', sans-serif;
            font-weight: 400;
            font-size: 12px;
            line-height: 18px;
            color:#000000;
            text-align:center;
            height: 30px;
        }
        .whole-items-cls .items tr:nth-child(2) td {padding-top:10px;}
        .items tr td:nth-child(2)  {
            text-align:left;
        }
        .payment-options {
            margin-top:10px;
            padding-left: 30px;
            padding-right: 30px;
        }
        .payment_schedule_order_total { padding-left: 30px; }
        .payment_schedule_order_total .order_total h3{margin-right: 30px !important;}
        .payment-options th {padding:0;}
        .payment-options th h3,.payment_schedule_order_total h3 {
            font-family: 'Lato', sans-serif;
            font-weight: 700;
            font-size: 16px;
            line-height: 21.33px;
            color: #f35b15;
            border-bottom: 2px solid #f35b15;
            padding: 5px;
            text-align: left;
            text-transform: uppercase;
       }
       .payment-options tr h4 {
            font-family: 'Lato', sans-serif;
            font-weight: 500;
            text-align: left;
            color: #F35B15;
            font-size: 10px;
            line-height: 12px;
            margin-bottom: 12px;
        }
       .payment-options tr td {
            font-family: 'Lato', sans-serif;
            font-weight: 600;
            text-align: left;
            color: #000000;
            font-size: 15px;
            line-height: 20px;
        }
       .payment-options td span:first-child {
            width:200px;
            display:inline-block;
        }
       .payment-options td:last-child h3 {
            background: #F9F4EE;
            width: 100%;
            margin-top:20px;
            height:68px;    
        }
        .details tr td:last-child {
            vertical-align: middle;
        }
       .details tr td p span:last-child {
           font-weight:400!important;
        }       
       .payment-options td:last-child h3 span:first-child {padding-left:15px;}
       .payment-options td:last-child h3 span {
            padding-top: 0px;
            padding-bottom: 0px;
            top: 25px;
            position: relative;
       }
       .payment-options td:last-child h3 span {
            font-family: 'Lato', sans-serif;
            font-size: 16px;
            line-height: 20px;
            font-weight: 700;
            color: #f35b15;
            height: 20px;
            display: inline-block;
       }
       .footer > div {
            float: left;
            width: 22%;
            padding: 0px 0px;
            margin: 30px 0px;
       }
       .footer div:first-child {
        width: 29%;
        margin-top: 40px;
       }
       .footer div:nth-child(2) {
        border-left: 2px solid #f35b15;
        padding-left: 4%;
       }
       .footer p {
        color: #f35b15;
        font-family: 'Lato', sans-serif;
        font-size: 9px;
        line-height: 10.8px;
        font-weight: 500;
        margin-bottom: 6px !important;
        text-transform:uppercase;
       }
       .footer a  {
        color: #000000;
        font-family: 'Lato', sans-serif;
        font-size: 10px;
        line-height: 12px;
        font-weight: 400;
       }
       .footer img {padding-left:20px;}
       .payment-options tr td figure.table {margin:0;} 
       .payment-options tr td figure.table table tbody tr td:nth-child(2) {display:none;}
       .payment-options tr td figure.table table tbody tr td {
        border: 0px !important;
        padding: 0;
        font-weight: 400;
       }
       .bank-info-block table td:first-child { vertical-align: top;}
       .payment_schedule_order_total{
            margin-top: 100px;
            margin-bottom: 30px;
       }
       .payment-options { 
        bottom: 0cm;
        height: 228px; 
        page-break-inside: avoid;
        }
        .full-btm-cls-mod p span {
            font-family: 'Lato', sans-serif!important;
            font-weight: 400!important;
            text-align: left!important;
            color: #000000!important;
            font-size: 12px!important;
            line-height: 16px!important;
        }
        .payment_schedule_order_total h3{margin-bottom: 15px;}
        .payment_schedule{width: 415px;padding-right: 25px;vertical-align: baseline;}
        .order_total{width: 322px;vertical-align: baseline;}
        .payment_schedule tr{border-bottom: 1px solid #000;}
        .payment_schedule td{padding: 10px;text-align: center;font-family: 'Lato', sans-serif;font-size: 13px;}
        .order_total table{width: 100%;}
        .full-btm-cls-mod p{display: flex;justify-content: space-between;margin-bottom: 12px !important;}
        .order_total h4{
            font-family: 'Lato', sans-serif;
            font-weight: 700;
            font-size: 13px;
            line-height: 21.33px;
            color: #000;
            background-color: #F9F4EE;
            padding: 10px 10px 10px 5px;
            text-align: left;
            display: flex;
            justify-content: space-between;
            position: relative;
            border-right: 10px solid #f35b15;
            margin-top: 20px;
        }
        .payment_schedule th{ 
            font-family: 'Lato', sans-serif;
            font-weight: 600;
            font-size: 13px;
            line-height: 10px;
            color: #000;
            padding: 5px 10px 10px 10px;
            text-align: center;
        }
        .payment-options tr td figure{float: left;}
        .payment-options tr td figure.table table tbody tr td:nth-child(1){width: 300px;}
        .payment-options tr td figure.table table tbody tr td:nth-child(2){width: 250px;}
        .payment_mode div{width: 200px;margin-top: -12px;float:left; margin-left: 40px;}
        .payment-options tr td figure.table table tbody tr td:nth-child(1) strong{margin-bottom: 20px;}
        .text-weight{ font-weight: 600; }
        .tbl-bg-style{ background-color: #F9F4EE; padding: 0px 0px 0px 20px; position:relative;  border-right: 10px solid #f35b15;}
        .order-total-inner {margin-left: 5px; font-size: 12px; font-family: 'Lato', sans-serif;}
        .tbl-font-style{ font-family: 'Lato', sans-serif; font-weight: 400;font-size: 12px;line-height: 17px;}
        .event-name { white-space: nowrap; }
        /* .payment-schedule-font, .terms-condition-block, .bank-info-block{ font-family: 'Lato', sans-serif; font-weight: 400; font-size: 12px;} */
        .payment-schedule-font th {font-family: 'Lato', sans-serif; font-weight: 700; font-size: 12px; }
        .payment-schedule-font td, .terms-condition-block p, .bank-info-block td p{ font-family: 'Lato', sans-serif; font-weight: 400; font-size: 12px;}
        .terms-condition-block {padding-top: 13px;}
        p.title-heading {font-weight: 700;}
        .vertical-align-top {vertical-align: top;}
        .bank-details {padding-top:15px;}
        .terms-condition{ font-family: 'Lato', sans-serif; font-weight: 400; font-size: 13px;}
        .bank-info-block td:first-child { font-family: 'Lato', sans-serif; font-weight: 400; font-size: 13px;}
    </style>
    <title>Invoice - {{ $invoice->booking_reference }}</title>
</head>

<body class="body" style="padding:0; margin:0 auto !important; display:block !important; min-width:100% !important; width:100% !important; background:#ffffff; -webkit-text-size-adjust:none">
<div class="container">
    <!-- Header Section -->
  <div class="head-dv-cls">
    <table class="header">
        <tr>
            <td class="left-column"> <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('images/pdf_assets/images/KeithProwse-Travel-Logo.png'))?>"  alt="logo-ch"></td>
            <td class="right-column">
                <p><span>Address:</span><span style="font-weight:400;margin-left:5px;">{{$invoice->brand->address_line_1}}, {{$invoice->brand->address_line_2}} <br /> {{$invoice->brand->town}}, {{$invoice->brand->region}}, {{$invoice->brand->postcode}}<br>
                </span></p>
                <p><span>Company ABN:</span><span style="font-weight:400;margin-left:5px;">{{$invoice->brand->vat_code}}</span></p>               
                <p><span>Email:</span><span style="font-weight:400;margin-left:5px;">{{$invoice->brand->email}}</span></p>
                <p><span>Phone:</span><span style="font-weight:400;margin-left:5px;">{{$invoice->brand->telephone}}</span></p>
            </td>
        </tr>
    </table>
  </div>
    <!-- Invoice Title -->
    <div class="invoice-title">
        <h1>Tax Invoice</h1>
       
    </div>

    <!-- Details Table -->
    <table class="details">
        <tr>
            <td><h6>Invoice To</h6></td>
            <td><h6>Details</h6></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 32%;">
                @if (!is_null($invoice->organization))
                    <p class="name">{{ $invoice->organization->name }}</p>
                    <p class="name">{{ $invoice->agent ? $invoice->agent->first_name . ' ' . $invoice->agent->last_name : $invoice->organization->name }}</p>
                    <p class="address">
                        {{ implode(', ', array_filter([$invoice->organization->deliveryAddress->address_line_1, $invoice->organization->deliveryAddress->address_line_2, $invoice->organization->deliveryAddress->town, $invoice->organization->deliveryAddress->region, $invoice->organization->deliveryAddress->country, $invoice->organization->deliveryAddress->postcode])) }}
                    </p>
                @elseif (!is_null($invoice->organization) || !is_null($invoice->agent))
                    <p class="name">{{ $invoice->agent->first_name . ' ' . $invoice->agent->last_name }}</p>
                    <p class="name">{{ $invoice->agent->email ?? $invoice->organization->contact_email }}</p>
                @else
                    <p class="name">{{ $invoice->lead->full_name }}</p>
                    <p class="name">{{$invoice->lead->email}}</p>
                    <p class="address">
                        {{ implode(', ', array_filter([$invoice->lead->address_line_1, $invoice->lead->address_line_2, $invoice->lead->town, $invoice->lead->region, $invoice->lead->country, $invoice->lead->postcode])) }}
                    </p>
                @endif
            </td>
            <td style="width: 32%;">
                <p class="event-name"><span>Reference:</span> <span>{{$invoice->booking_reference}}</span></p>
                <p class="event-name"><span>Event Name:</span> <span>{{ $invoice->event }}</span></p>
                <p class="no-of-pax"><span>Number of Pax:</span> <span>{{$invoice->getTravellingTravellersAtribute()}}</span></p>
            </td>
            <td class="tbl-bg-style" style="width: 23%;">
                <p class="bg-box-contain"><span>Invoice No:</span> <span>{{ $invoice->invoice_number }}</span></p>
                <p class="bg-box-contain"><span>Invoice Date:</span> <span>{{ date('d M Y', strtotime($invoice->generated)) }}</span></p>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <div class="whole-items-cls"> 
    <table class="items">
        <tr style="background-color: #f35b15;">
            <th><h2>No.</h2></th>
            <th><h2>Description</h2></th>
            <th><h2>Qty</h2></th>
        </tr>
        @php
            $billables = $invoice->repository->getItemsByQuantity(); 
            $counter = 1;
        @endphp

        @foreach($billables as $b_index => $billable)
            @continue($billable->shared_key === 'surcharge')
            @continue($billable->isGroupedBase())
            <tr>
                <td>{{$counter}}</td>
                <td>{{$billable->description}}</td>
                <td>{{$billable->getQuantity()}}</td>
            </tr>
            @php $counter++; @endphp
        @endforeach
    </table>
    </div>
    <!-- Payment Options Table -->
    <table class="payment_schedule_order_total" style="width:100%;">
        <tr>
            <td class="payment_schedule" style="width:45%; padding-right:5%;">
                <h3>Payment Schedule</h3>
                <table class="payment-schedule-font" style="width:100%;">
                    <tr>
                        <th>Instalment</th>
                        <th>Received</th>
                        <th>Outstanding</th>
                        <th>Date Due</th>
                    </tr>
                    @php
                        $balance_received = 0;
                        $balance_received_total = 0;
                    @endphp
                    @foreach ($invoice->payment_schedule as $key => $installment)
                        @if($installment->type === ItineraryScheduleType::BOOKING_FEE)
                        <tr>
                            <td>{{ f_currency($installment->amount) }}</td>
                            <td>
                                {{ f_currency(min($installment->amount, $installment->received)) }}
                                @php $balance_received = $balance_received + min($installment->amount, $installment->received) @endphp
                            </td>
                            <td>
                            @if($installment->amount <= $installment->received)
                                Paid
                            @else
                                {{ f_currency($installment->amount - min($installment->amount, $installment->received)) }}
                            @endif
                            </td>
                            <td class="text-weight"></td>
                        </tr>
                        @endif
                        @if ($installment->type === ItineraryScheduleType::DEPOSIT)
                        <tr>
                            <td>{{ f_currency($installment->amount) }}</td>
                            <td>
                            @php $amount = $installment->amount - min(($installment->received - ($installment->balance ?? 0)), $installment->amount); @endphp
                            @if($amount <= 0)
                                {{ f_currency($installment->amount) }}
                                @php $balance_received = $balance_received + $installment->amount @endphp
                            @else
                                {{ f_currency($installment->received) }}
                                @php $balance_received = $balance_received + $installment->received @endphp
                            @endif
                            </td>
                            <td>
                            @if($amount <= 0)
                                Paid
                            @else
                                {{ f_currency($amount) }}
                            @endif
                            </td>
                            <td class="text-weight"></td>
                        </tr>
                        @endif
                        @if ($installment->type === ItineraryScheduleType::INSTALLMENT)
                            @php $amount = $installment->amount - $installment->received; @endphp
                            <tr>
                                <td>{{ f_currency($installment->amount) }}</td>
                                <td>
                                @if($amount <= 0)
                                    {{ f_currency($installment->amount) }}
                                    @php $balance_received = $balance_received + $installment->amount @endphp
                                @else
                                    {{ f_currency($installment->received) }}
                                    @php $balance_received = $balance_received + $installment->received @endphp
                                @endif
                                </td>
                                <td>
                                @if($amount <= 0)
                                    Paid
                                @else
                                    {{ f_currency($amount) }}
                                @endif
                                </td>
                                <td>
                                @if(!is_null(optional($installment->due)))
                                    {{ optional($installment->due)->format('d M Y') }}
                                @endif
                                </td>
                            </tr>
                        @endif
                        @if ($installment->type === ItineraryScheduleType::REMAINING)
                            <tr>
                                <td>{{ f_currency($installment->amount) }}</td>
                                <td>
                                @php $balance_received_total = $installment->received - $balance_received; @endphp
                                {{ f_currency($balance_received_total) }}
                                </td>
                                <td>
                                @php $amount = min($installment->balance, $installment->amount); @endphp
                                @if($amount <= 0)
                                    Paid
                                @else
                                    {{ f_currency($amount) }}
                                @endif
                                </td>
                                <td>
                                @if(!is_null(optional($installment->due)))
                                    {{ optional($installment->due)->format('d M Y') }}
                                @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </td>
            <td style="width:10%;">&nbsp;</td>
            <td class="order_total" style="width:35%; padding-left:5%;">
                <h3>Order Total</h3>
                <table class="order-total-inner">
                    <tr>
                        <td>
                            <div class="full-btm-cls-mod" style="">
                                <p><span style="font-weight:700 !important;">Invoice Total:</span> <span style="font-weight:700 !important;">{{f_currency($invoice->total_cost + $invoice->commission_amount)}}</span></p>
                                @if($invoice->commission_amount > 0)
                                    <p><span>Commission ({{$invoice->commission_percentage}}%):</span> <span>{{f_currency($invoice->commission_amount)}}</span></p>
                                    <p><span>Booking Total: </span> <span>{{f_currency($invoice->total_cost)}}</span></p>
                                @endif
                                <p><span>GST (included):</span> <span>{{f_currency($invoice->tax_amount)}}</span></p>
                                <p><span>Received:</span> <span>{{f_currency($invoice->total_paid)}}</span></p>
                                @if($invoice->total_fees > 0)
                                    <p><span>Fees Paid:</span> <span>{{f_currency($invoice->total_fees)}}</span></p>
                                @endif
                            </div>
                            <h4><span>Balance Due:</span> <span>{{f_currency($invoice->total_cost - $invoice->total_paid)}}</span></h4>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="payment-options">
        <tr>
            <th><h3>Payment Options</h3></th>
        </tr>
        <tr>
        <td class="payment_mode">
                <table class="bank-details" style="width: 100%;">
                    <tr>
                        <td style="width:75%">{!! setting('company.bank_transfer', '-')  !!}</td>
                        <td class="vertical-align-top"  style="width:25%">
                            <div class="terms-condition-block"><p class="title-heading">Terms & Conditions</p>
                                <p class="terms-condition">
                                    Visit our website for full details or view them <a style="color: blue !important; text-decoration: underline !important;" target="_blank" href="https://www.kpt.com.au/terms-and-conditions/">here.</a>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- Footer Section -->
    <!-- <div class="footer">
        <div class="logo">
          <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('https://qa.octopustravelmatrix.com/images/pdf_assets/images/KeithProwse_Logo.png'))?>"  alt="logo-ch">
        </div>
        <div class="phone">
           <p>Phone</p>
           <a href="tel:+{{$invoice->brand->telephone}}">{{$invoice->brand->telephone}}</a>
        </div>
        <div class="email">
           <p>email</p>
           <a href="mailto:{{$invoice->brand->email}}">{{$invoice->brand->email}}</a>
        </div>
        <div class="phone">
           <p>website</p>
           <a target="_blank" href="{{$invoice->brand->website}}">{{$invoice->brand->website}}</a>
        </div>
    </div>
   </div> -->
</body>
</html>
