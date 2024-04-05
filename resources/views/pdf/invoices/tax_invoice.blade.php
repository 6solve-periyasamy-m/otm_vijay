<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

    <style type="text/css">
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
    </style>
</head>

<body class="body"
    style="padding:0; margin:0 auto !important; display:block !important; min-width:100% !important; width:100% !important; background:#ffffff; -webkit-text-size-adjust:none">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="background-color:#ffffff;"
        class="full-wrap">
        <tr>
            <td align="center" valign="top">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="670"
                    style="width: 794px; background-color: #fff">
                    <tr>
                        <td align="center" valign="top" style="padding: 35px 35px; border-top: 10px solid #CAA974;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="top" style="padding-bottom: 10px;">
                                        <table width="100%" border="0" cellspacing="0">
                                            <tr>
                                                <td align="left" valign="top">
                                                    <table align="left" width="100%" border="0" cellspacing="0">
                                                        <tr>
                                                            <td align="left" valign="top">
                                                                <img src="{{img_to_b64($invoice->brand->logo)}}" alt="logo" width="300"
                                                                    style="display: block;">
                                                            </td>
                                                        </tr>

                                                    </table>
                                                </td>
                                                <td width="310" align="right" valign="top">
                                                    <table width="100%" align="right" border="0" cellspacing="0"
                                                        cellpadding="0">
                                                        <tr>
                                                            <td align="right" valign="top" class="oc_f14 oc_black"
                                                                style="padding: 10px 0 5px; font-weight: 700; ">{{$invoice->brand->name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" valign="top" class="oc_f12 oc_lblack"
                                                                style="padding-bottom: 0; font-weight: 700;">ABN: {{$invoice->brand->vat_code}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" valign="top" class="oc_f12 oc_lblack"
                                                                style="padding-bottom: 0; font-weight: 700;">{{$invoice->brand->address_line_1}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" valign="top" class="oc_f12 oc_lblack"
                                                                style="padding-bottom: 0; font-weight: 700;">{{$invoice->brand->address_line_2}}, {{$invoice->brand->town}}, {{$invoice->brand->region}}, {{$invoice->brand->postcode}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" valign="top" class="oc_f12 oc_lblack"
                                                                style="padding-bottom: 5px; font-weight: 700;">{{$invoice->brand->country}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" valign="top" class="oc_f12 oc_lblack"
                                                                style="padding-bottom: 0; font-weight: 700;">Phone:
                                                                {{$invoice->brand->telephone}}</td>
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
                                            <thead>
                                                <tr>
                                                    <th align="center" valign="top"
                                                        style="border-bottom: 2px solid #CAA974; padding: 5px 15px; color: #CAA974; "
                                                        class="oc_f18">
                                                        T A X &nbsp; &nbsp; I N V O I C E</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">
                                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                            <tr>
                                                <td width="50%" align="left" valign="top">
                                                    <table align="left" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <td width="40" align="left" valign="top"
                                                                    style="padding: 2px 15px; font-weight: 700;"
                                                                    class="oc_f12 oc_lblack">
                                                                    To:
                                                                </td>
                                                                <td align="left" valign="top" style="padding: 2px 15px;"
                                                                    class="oc_f12 oc_lblack">
                                                                    {{ $invoice->lead->full_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40" align="left" valign="top"
                                                                    style="padding: 2px 15px; font-weight: 700;"
                                                                    class="oc_f12 oc_lblack">
                                                                    Email:
                                                                </td>
                                                                <td align="left" valign="top" style="padding: 2px 15px;"
                                                                    class="oc_f12 oc_lblack">
                                                                    <a href="mailto:{{ $invoice->lead->email ?? 'N/A' }}">
                                                                    {{ $invoice->lead->email ?? 'N/A' }} 
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40" align="left" valign="top"
                                                                    style="padding: 2px 15px; font-weight: 700;"
                                                                    class="oc_f12 oc_lblack">
                                                                    For:
                                                                </td>
                                                                <td align="left" valign="top" style="padding: 2px 15px;"
                                                                    class="oc_f12 oc_lblack">
                                                                    {{$invoice->name}} | {{ $invoice->lead->full_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="40" align="left" valign="top"
                                                                    style="padding: 2px 15px; font-weight: 700;"
                                                                    class="oc_f12 oc_lblack">
                                                                    &nbsp;
                                                                </td>
                                                                <td align="left" valign="top" style="padding: 2px 15px;"
                                                                    class="oc_f12 oc_lblack">
                                                                    &nbsp;</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td width="50%" align="right" valign="top">
                                                    <table align="right" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <td width="180" align="left" valign="top"
                                                                    style="padding: 2px 15px; font-weight: 700;"
                                                                    class="oc_f12 oc_lblack">
                                                                    Booking Number:
                                                                </td>
                                                                <td align="right" valign="top"
                                                                    style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                                                    {{ $invoice->booking_reference }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="180" align="left" valign="top"
                                                                    style="padding: 2px 15px; font-weight: 700;"
                                                                    class="oc_f12 oc_lblack">
                                                                    Invoice Date:
                                                                </td>
                                                                <td align="right" valign="top"
                                                                    style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                                                    {{ f_date($invoice->generated) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="180" align="left" valign="top"
                                                                    style="padding: 2px 15px; font-weight: 700;"
                                                                    class="oc_f12 oc_lblack">
                                                                    Payment Reference:
                                                                </td>
                                                                <td align="right" valign="top"
                                                                    style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                                                    N/A</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="180" align="left" valign="top"
                                                                    style="padding: 2px 15px; font-weight: 700;"
                                                                    class="oc_f12 oc_lblack">
                                                                    Number of Pax:
                                                                </td>
                                                                <td align="right" valign="top"
                                                                    style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                                                    {{count($invoice->customers)}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="180" align="left" valign="top"
                                                                    style="padding: 2px 15px; font-weight: 700;"
                                                                    class="oc_f12 oc_lblack">
                                                                    &nbsp;
                                                                </td>
                                                                <td align="right" valign="top"
                                                                    style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                                                    &nbsp;</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="">
                                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                            <tr>
                                                <td align="left" valign="top">
                                                    <table align="left" border="0" cellspacing="0" cellpadding="0"
                                                        width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th align="left" valign="top"
                                                                    style="border-bottom: 2px solid #CAA974; padding: 2px 15px; color: #CAA974;"
                                                                    class="oc_f16">
                                                                    DESCRIPTION</th>
                                                                <th width="180" align="left" valign="top"
                                                                    style="border-bottom: 2px solid #CAA974; padding: 2px 15px; color: #CAA974;"
                                                                    class="oc_f16">QUANTITY
                                                                </th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" valign="top" style="padding: 2px 15px;"
                                                                    class="oc_f12 oc_lblack">The Victoria Hotel - 1 Twin
                                                                </td>
                                                                <td width="180" align="left" valign="top"
                                                                    style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                                                    1 Room x 3 Nights
                                                                </td>
                                                            </tr>
                                                            <tr style="background-color: #f5f5f5;">
                                                                <td align="left" valign="top"
                                                                    style="padding: 2px 15px; "
                                                                    class="oc_f12 oc_lblack">Waite Grandstand - 4 Day
                                                                    Pass </td>
                                                                <td width="180" align="left" valign="top"
                                                                    style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                                                    2 Person
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left" valign="top" style="padding: 2px 15px;"
                                                                    class="oc_f14 oc_lblack">&nbsp;
                                                                </td>
                                                                <td width="180" align="center" valign="top"
                                                                    style="padding: 2px 15px;" class="oc_f14 oc_lblack">
                                                                    &nbsp;</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top">
                                                    <table align="left" width="100%" cellspacing="0" cellpadding="0">
                                                        <thead>
                                                            <tr
                                                                style=" border-bottom: 2px solid #CAA974; padding: 2px 15px;">
                                                                <th align="right" valign="top"
                                                                    style="padding: 2px 15px; color: #CAA974; font-weight: 700;"
                                                                    class="oc_f16 ">PAYMENT DETAILS</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top">
                                                    <table align="left" width="40%" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" valign="top" style="padding: 2px 15px;"
                                                                    class="oc_f14 oc_lblack">&nbsp;
                                                                </td>
                                                                <td width="180" align="center" valign="top"
                                                                    style="padding: 2px 15px;" class="oc_f14 oc_lblack">
                                                                    &nbsp;</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table align="right" width="60%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="right" style="padding: 2px 15px;"
                                                                class="oc_f12 oc_lblack">Invoice Total:</td>
                                                            <td width="150" align="right" valign="top"
                                                                style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                                                {{f_currency($invoice->total_cost)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" style="padding: 2px 15px;"
                                                                class="oc_f12 oc_lblack">GST (included):</td>
                                                            <td width="150" align="right" valign="top"
                                                                style="padding: 2px 15px; " class="oc_f12 oc_lblack">
                                                                {{f_currency($invoice->tax_amount)}}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td align="right" style="padding: 2px 15px;"
                                                                class="oc_f12 oc_lblack">Received:</td>
                                                            <td width="150" align="right" valign="top"
                                                                style="padding: 2px 15px;" class="oc_f12 oc_lblack">
                                                                {{f_currency($invoice->total_paid)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" style="padding: 2px 15px;"
                                                                class="oc_f12 oc_lblack">Final Amount Remaining:</td>
                                                            <td width="150" align="right" valign="top"
                                                                style="padding: 2px 15px; border-top: 1px solid #CAA974; "
                                                                class="oc_f12 oc_lblack">{{f_currency($invoice->total_cost - $invoice->total_paid)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" style="padding: 2px 15px;"
                                                                class="oc_f14 oc_lblack">&nbsp;</td>
                                                            <td width="150" align="right" valign="top"
                                                                style="padding: 2px 15px; border-top: 1px solid #CAA974; "
                                                                class="oc_f14 oc_lblack">&nbsp;</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="left" valign="top">
                                                    <table align="left" border="0" cellspacing="0" cellpadding="0"
                                                        width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="25%" align="left" valign="top"
                                                                    style="border-bottom: 2px solid #CAA974; padding: 2px 15px; color: #CAA974;"
                                                                    class="oc_f16">
                                                                    DUE DATE</th>
                                                                <th width="25%" align="left" valign="top"
                                                                    style="border-bottom: 2px solid #CAA974; padding: 2px 15px; color: #CAA974;"
                                                                    class="oc_f16">AMOUNT
                                                                </th>
                                                                <th width="25%" align="left" valign="top"
                                                                    style="border-bottom: 2px solid #CAA974; padding: 2px 15px; color: #CAA974;"
                                                                    class="oc_f16">
                                                                    PAID</th>
                                                                <th width="25%" align="left" valign="top"
                                                                    style="border-bottom: 2px solid #CAA974; padding: 2px 15px; color: #CAA974;"
                                                                    class="oc_f16">RECEIVED
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (sizeof($invoice->installments) === 0)
                                                                <tr>
                                                                    <td colspan="4" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">No Installments recorded.</td>
                                                                </tr>
                                                            @else
                                                                @foreach($invoice->installments as $index => $installment)
                                                                    <tr style="background-color: {{ $index % 2 == 0 ? '#f5f5f5' : '#ffffff' }};"> 
                                                                        <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">{{ f_date($installment->due) }}</td>
                                                                        <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">{{ $installment->description }}</td>
                                                                        <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">{{ f_bool($installment->paid) }}</td>
                                                                        <td width="25%" align="left" valign="top" style="padding: 2px 15px;" class="oc_f12 oc_lblack">03/04/2024</td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif

                                                            <tr>
                                                                <td width="25%" align="center" valign="top"
                                                                    style="padding: 2px 15px;">
                                                                    &nbsp;
                                                                </td>
                                                                <td width="25%" align="center" valign="top"
                                                                    style="padding: 2px 15px;">
                                                                    &nbsp;
                                                                </td>
                                                                <td width="25%" align="center" valign="top"
                                                                    style="padding: 2px 15px;">
                                                                    &nbsp;
                                                                </td>
                                                                <td width="25%" align="center" valign="top"
                                                                    style="padding: 2px 15px;">
                                                                    &nbsp;
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
                                    <td align="left" valign="top">
                                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th align="left" valign="top"
                                                        style=" border-bottom: 2px solid #CAA974; padding: 2px 15px; color: #CAA974; "
                                                        class="oc_f16">
                                                        PAYMENT OPTIONS
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">
                                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="400">
                                            <tbody>
                                                <tr>
                                                    <td width="80" align="left" valign="top"
                                                        style="padding: 2px 15px; color: #CAA974; font-weight: 700;"
                                                        class="oc_f12">BANK TRANSFER
                                                    </td>
                                                    <td width="180" align="left" valign="top" style="padding: 2px 15px;"
                                                        class="oc_f12 oc_lblack">
                                                        Keith Prowse Travel PTY LTD <br />
                                                        ABN: 31 003 276 775 <br />
                                                        BSB: 032-298 <br />
                                                        ACC: 540726 <br />
                                                        SWIFT: WPACAU2S <br />
                                                        BANK: Westpac <br />
                                                        BRANCH: Crows Nest <br />
                                                        Reference Number: KPAD106102
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" valign="top" style="padding: 2px 15px;"
                                                        class="oc_f14 oc_lblack">&nbsp;
                                                    </td>
                                                    <td width="180" align="center" valign="top"
                                                        style="padding: 2px 15px;" class="oc_f14 oc_lblack">
                                                        &nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">
                                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th align="left" valign="top"
                                                        style=" border-bottom: 2px solid #CAA974; padding: 20px 15px 2px; color: #CAA974; "
                                                        class="oc_f16">
                                                        TERMS AND CONDITIONS
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">
                                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
                                            <tbody>
                                                <tr>

                                                    <td align="left" valign="top" style="padding: 2px 15px;"
                                                        class="oc_f12 oc_lblack">
                                                        Terms and conditions apply. Please see our website for a copy or
                                                        <a href="http://https://www.kpt.com.au/terms-and-conditions"
                                                            target="_blank"
                                                            style="color: #CAA974; text-decoration: underline;">view
                                                            them here</a>
                                                        .
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" valign="top" style="padding: 2px 15px;"
                                                        class="oc_f14 oc_lblack">&nbsp;
                                                    </td>
                                                    <td width="180" align="center" valign="top"
                                                        style="padding: 2px 15px;" class="oc_f14 oc_lblack">
                                                        &nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>