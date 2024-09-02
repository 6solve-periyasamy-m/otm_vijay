@php
    /** @var \App\Models\Order\Invoice\Invoice $invoice */
    //var_dump($invoice);
    $due_date = 'No due';
   //if (!sizeof($invoice->installments) === 0)
    foreach($invoice->installments as $installment) {
        if (!$installment->paid) {
            $due_date = date('d M Y', strtotime($installment->due));
            break; 
        }
    }
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
            padding: 20px 20px 5px 20px;
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
            padding-left:20px;
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
        .details tr th h6{
            font-family: 'Lato', sans-serif;
            font-weight: 400;
            text-align: left;
            color: #F35B15;
            font-size: 9px;
            line-height: 10.18px;
            padding: 0px 0px 0px 20px;
            margin-top:8px;
        }
        .details tr td  {
            max-width: 112px;
            padding-right: 32px;
        }
        .details tr td:first-child p, .details tr td:nth-child(2) p {
            font-family: 'Lato', sans-serif;
            font-weight: 400;
            font-size: 10px;
            line-height: 17px;
            padding: 0px 0px 0px 20px !important;
            vertical-align: top;
        }
        .details tr td {
            font-family: 'Lato', sans-serif;
            font-weight: 400;
            font-size: 10px;
            line-height: 17px;
            vertical-align: top;
        }
        .details tr td span {font-weight:500;}
        .details tr td:last-child {
            background-color: #F9F4EE;
            padding: 0px 0px 0px 30px; 
            position:relative;
            top:-13px;          
        }
        .details tr td p.bg-box-contain {
            border-right: 10px solid #f35b15;
        }
        .details tr p.bg-box-contain.f-1 {
            padding-top:15px!important;
        }
        .details tr p.bg-box-contain.f-3 {
            padding-bottom:15px!important;
        }
        .bg-box-contain
        /* .details tr .bg-box-contain:after {
            content:"";
            height:100%;
            width:10px;
            background-color:#f35b15;
            display:inline-block;
            position:absolute;
            right:0px;
            top:0px;
        } */
        .items th {
            background-color: #f35b15;
            padding:4px 30px;
            
        }
        .items th h2 {
            font-family: 'Lato', sans-serif;
            font-weight: 700;
            font-size: 16px;
            line-height: 21.33px;
            color: #fff;
            text-align:center;
        }
        .items td {
            padding:4px 30px;
            font-family: 'Lato', sans-serif;
            font-weight: 400;
            font-size: 10px;
            line-height: 12px;
            color:#000000;
            padding-top:18px;
            padding-bottom:18px;
            text-align:center;
        }
        .items tr:nth-child(even) td  {
            background-color: #F9F4EE;     
        }       
        .items tr td:nth-child(2)  {
            text-align:left;
        }
        .payment-options {
            margin-top:10px;
            border-top: 1px solid #f35b15;
            border-bottom: 1px solid #f35b15;
        }
        .payment-options th {padding:0;}
        .payment-options th h3 {
            font-family: 'Lato', sans-serif;
            font-weight: 700;
            font-size: 16px;
            line-height: 21.33px;
            color: #ffffff;
            border-right: 1px solid #f35b15;
            padding: 5px;
            background: #f35b15;
       }
       .payment-options tr td:first-child {
            padding:25px 0px 25px 20px;
       }
       .payment-options th:last-child, .payment-options td:last-child {
            border-left: 1px solid #f35b15;
            line-height: 23px;
            position: relative;
            vertical-align: top;
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
            font-weight: 400;
            text-align: left;
            color: #000000;
            font-size: 10px;
            line-height: 12px;
            width:398px;
        }
       .payment-options tr td p.terms-conditions {
            margin-top: 30px !important;
            text-decoration: underline;
         }
       .payment-options td:last-child p {
           padding-left:15px!important; 
        }
       .payment-options td span:first-child {
            width:200px;
            display:inline-block;
        }
       .payment-options td:last-child h3 {
            background: #F9F4EE;
            width: 100%;
            margin-top:30px;
            height:60px;    
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
       .whole-items-cls {min-height:520px;}
    </style>
    <title>Invoice - {{ $invoice->booking_reference }}</title>
</head>

<body class="body" style="padding:0; margin:0 auto !important; display:block !important; min-width:100% !important; width:100% !important; background:#ffffff; -webkit-text-size-adjust:none">
<div class="container">
    <!-- Header Section -->
    <table class="header">
        <tr>
            <td class="left-column"> <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('https://qa.octopustravelmatrix.com/images/pdf_assets/images/KeithProwse_Logo.png'))?>"  alt="logo-ch"></td>
            <td class="right-column">
                <p>{{$invoice->brand->address_line_1}} <br>{{$invoice->brand->address_line_2}}, {{$invoice->brand->town}}, {{$invoice->brand->region}}, {{$invoice->brand->postcode}}<br>
                {{$invoice->brand->vat_code}}</p>
            </td>
        </tr>
    </table>

    <!-- Invoice Title -->
    <div class="invoice-title">
        <h1>Tax Invoice</h1>
    </div>

    <!-- Details Table -->
    <table class="details">
        <tr>
            <th><h6>Invoice To</h6></th>
            <th><h6>Details</h6></th>
        </tr>
        <tr>
            <td>
            <p class="name">{{ $invoice->lead->full_name }}</p>
            <p class="address">
                {{ implode(', ', array_filter([$invoice->lead->address_line_1, $invoice->lead->address_line_2, $invoice->lead->town, $invoice->lead->region, $invoice->lead->country, $invoice->lead->postcode])) }}
            </p>
            </td>
            <td>
            <p class="event-name"><span>Event Name:</span> <span>{{ $invoice->event }}</span></p>
            <p class="event-name"> <span>Email:</span> <span>{{$invoice->lead->email}}</span> </p>
            <p class="no-of-pax"><span>Number of Pax:</span> <span>{{count($invoice->customers)}}</span></p>
            </td>
            <td>
            <p class="bg-box-contain f-1"><span>Invoice No:</span> <span>{{ $invoice->invoice_number }}</span></p>
            <p class="bg-box-contain"><span>Invoice Date:</span> <span>{{ date('d M Y', strtotime($invoice->generated)) }}</span></p>
            <p class="bg-box-contain f-3"><span>Due Date:</span> <span>{{$due_date}}</span></p>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <div class="whole-items-cls"> 
    <table class="items">
        <tr>
            <th><h2>No</h2></th>
            <th><h2>Description</h2></th>
            <th><h2>Qty</h2></th>
        </tr>
        @php
            $billables = $invoice->repository->getItemsByQuantity(); 
            $counter = 1;
        @endphp

        @foreach($billables as $b_index => $billable)
            @continue($billable->isGroupedBase())
            <tr>
                <td>{{$counter}}</td>
                <td>{{$billable->description}}</td>
                <td>{{$billable->getQuantity()}}</td>
            </tr>

        @php $counter++; @endphp
        @endforeach
        <!-- <tr>
            <td>1</td>
            <td>Single Occupancy Surcharge</td>
            <td>1</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Carlton Hotel Singapore (19/09/2024 15:00 to 23/09/2024 11:00) (Double 1 Room, Bed & Breakfast)</td>
            <td>1</td>
        </tr> -->
    </table>
    </div>

    <!-- Payment Options Table -->
    <table class="payment-options">
        <tr>
            <th><h3>Payment Options</h3></th>
            <th></th>
        </tr>
        <tr>
            <td>
                <!-- <h4>Bank Transfer</h4>
                <p>Example bank transfer details.</p>
                <p>IBAN: Test formatting</p>
                <br>
                <p>1. ABC</p>
                <p>2. DEF</p> -->
                {!! setting('company.bank_transfer', '-')  !!}
                <p class="terms-conditions">
                    <a href="#">Terms & conditions</a>
                </p>
            </td>
            <td>
                <p><span>Invoice Total:</span> <span> {{f_currency($invoice->total_cost)}}</span></p>
                <p><span>GST (included):</span> <span>{{f_currency($invoice->tax_amount)}}</span></p>
                <p><span>Received:</span> <span>{{f_currency($invoice->total_paid)}}</span></p>
                <p><span>Balance Due:</span> <span>{{f_currency($invoice->total_cost - $invoice->total_paid)}}</span></p>
                <h3><span>GRAND TOTAL:</span> <span>{{f_currency($invoice->total_cost + $invoice->tax_amount)}}</span></h3>
            </td>
        </tr>
    </table>
    <!-- Footer Section -->
    <div class="footer">
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
   </div>
</body>

</html>
