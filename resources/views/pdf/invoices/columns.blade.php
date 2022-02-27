@php
/**
 * @var App\Models\Invoice $invoice
 * @var App\Models\Order $order
 */
$order = $invoice->order;
//dd($invoice->customers);
@endphp

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invoice</title>
        <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
    </head>
    <body>
        <div class="background center-screen">
            <!-- Header Section -->
            <div class="section">
                <div class="header">
                    <div class="flex-container titles">
                        <div class="flex-items site-info">
                            <img src="{{ asset(\App\Repository\SettingsRepository::getOrDefault('company.logo', 'images/octlogo.png')) }}" class="header-logo" alt="{{ \App\Repository\SettingsRepository::get('company.name') }}" />
                        </div>
                        <div class="flex-items">
                            <h2 class="header-title">{{ $order->tour->name }}</h2>
                        </div>
                        <div class="flex-items">
                            <h1 class="header-title">Invoice</h1>
                        </div>
                    </div>
                    <div class="flex-container">
                        <div class="flex-items site-info">
                            Website: <a href="{{ URL::to('/') }}">{{ URL::to('/') }}</a>
                            <br />Email: {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.email', 'Email not set') }}
                            <br />Telephone: {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.phone', 'Phone number not set') }}
                        </div>
                        <div class="flex-items metadata-wrapper">
                            <div class="metadata divider">Date<br /><span class="metadata-text">{{ StringFormatter::formatDateTime($invoice->generated) }}</span></div>
                            <div class="metadata divider">Invoice No.<br /><span class="metadata-text">{{ $invoice->number }}</span></div>
                            <div class="metadata divider">Booking Ref.<br /><span class="metadata-text">{{ $order->booking_reference }}</span></div>
                        </div>
                    </div>
                </div>   
            </div>
            <!-- Billing Section -->
            <div class="section">
                <div class="flex-container">
                    <div class="flex-items billing-info-wrapper">
                        <div class="metadata divider"><span class="metadata-title">Billed from</span></div>
                    </div>
                    <div class="flex-items billing-info-wrapper">
                        <div class="metadata divider"><span class="metadata-title">Billed to</span></div>
                    </div>
                </div>
                <div class="flex-container">
                    <div class="flex-items billing-info-wrapper">
                        <div class="billing-info">{{ $order->leadBooker->customer_name }}</div>
                        <div class="billing-info">{{ $order->leadBooker->customer->billingAddress->address_line_1 }}{!! isset($order->leadBooker->customer->billingAddress->address_line_1) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $order->leadBooker->customer->billingAddress->address_line_2 }}{!! isset($order->leadBooker->customer->billingAddress->address_line_2) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $order->leadBooker->customer->billingAddress->address_line_3 }}{!! isset($order->leadBooker->customer->billingAddress->address_line_3) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $order->leadBooker->customer->billingAddress->town }}{!! isset($order->leadBooker->customer->billingAddress->town) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $order->leadBooker->customer->billingAddress->region }}{!! isset($order->leadBooker->customer->billingAddress->region) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $order->leadBooker->customer->billingAddress->country }}{!! isset($order->leadBooker->customer->billingAddress->country) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $order->leadBooker->customer->billingAddress->postcode }}{!! isset($order->leadBooker->customer->billingAddress->postcode) ? "<br />" : "" !!}</div>
                    </div>
                    <div class="flex-items billing-info-wrapper">
                        <div class="billing-info">{{ \App\Repository\SettingsRepository::getOrDefault('company.address.line_1', 'Company Address Line 1 Not Set') }}</div>
                        <div class="billing-info">{{ \App\Repository\SettingsRepository::getOrDefault('company.address.line_2', 'Company Address Line 2 Not Set') }}</div>
                        <div class="billing-info">{{ \App\Repository\SettingsRepository::getOrDefault('company.address.city', 'Company City Not Set') }}</div>
                        <div class="billing-info">{{ \App\Repository\SettingsRepository::getOrDefault('company.address.region', 'Company Region Not Set') }}</div>
                        <div class="billing-info">{{ \App\Repository\SettingsRepository::getOrDefault('company.address.postcode', 'Company Postcode Not Set') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice</title>
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
</head>
<body>
<div class="section header">
    <div class="header-cell t-align-left">
        {{ \App\Repository\SettingsRepository::getOrDefault('company.address.line_1', 'Company Address Line 1 Not Set') }}<br/>
        {{ \App\Repository\SettingsRepository::getOrDefault('company.address.line_2', 'Company Address Line 2 Not Set') }}<br/>
        {{ \App\Repository\SettingsRepository::getOrDefault('company.address.city', 'Company City Not Set') }}<br/>
        {{ \App\Repository\SettingsRepository::getOrDefault('company.address.region', 'Company Region Not Set') }}<br/>
        {{ \App\Repository\SettingsRepository::getOrDefault('company.address.postcode', 'Company Postcode Not Set') }}<br/>
    </div>
    <div class="header-cell t-align-center">
        <span class="header-title t-align-center">
            <h1>Your Invoice</h1>
        </span>
        <div class="invoice-details t-align-left">
            Invoice Number: {{ $invoice->number }}<br/>
            Invoice Date: {{ StringFormatter::formatDateTime($invoice->generated) }}<br/>
            Booking Ref: {{ $order->booking_reference }}<br/>
        </div>
    </div>
    <div class="header-cell t-align-right">
        <div style="display: block; width: 100%">
            <img src="{{ asset(\App\Repository\SettingsRepository::getOrDefault('company.logo', 'images/octlogo.png')) }}" class="header-logo" alt="{{ \App\Repository\SettingsRepository::get('company.name') }}">
        </div>
        <div class="header-company-details">
            Website: <a href="{{ URL::to('/') }}">{{ URL::to('/') }}</a><br/>
            Email: {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.email', 'Email not set') }}<br/>
            Telephone: {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.phone', 'Phone number not set') }}<br/>
        </div>
    </div>
</div>
<div class="section">
    <div class="header-cell">
        {{ $order->leadBooker->customer_name }}<br /><br />
        {{-- Only include a newline if the address part is included --}}
        {{ $order->leadBooker->customer->billingAddress->address_line_1 }}{!! isset($order->leadBooker->customer->billingAddress->address_line_1) ? "<br />" : "" !!}
        {{ $order->leadBooker->customer->billingAddress->address_line_2 }}{!! isset($order->leadBooker->customer->billingAddress->address_line_2) ? "<br />" : "" !!}
        {{ $order->leadBooker->customer->billingAddress->address_line_3 }}{!! isset($order->leadBooker->customer->billingAddress->address_line_3) ? "<br />" : "" !!}
        {{ $order->leadBooker->customer->billingAddress->town }}{!! isset($order->leadBooker->customer->billingAddress->town) ? "<br />" : "" !!}
        {{ $order->leadBooker->customer->billingAddress->region }}{!! isset($order->leadBooker->customer->billingAddress->region) ? "<br />" : "" !!}
        {{ $order->leadBooker->customer->billingAddress->country }}{!! isset($order->leadBooker->customer->billingAddress->country) ? "<br />" : "" !!}
        {{ $order->leadBooker->customer->billingAddress->postcode }}{!! isset($order->leadBooker->customer->billingAddress->postcode) ? "<br />" : "" !!}
    </div>
    <div class="header-cell t-align-center">
        <h3>Tour: {{ $order->tour->name }}</h3>
    </div>
    <div class="header-cell"></div>
</div>
<div class="section">
    <table>
        <thead>
        <tr>
            <th scope="col" class="date">Quantity</th>
            <th scope="col" class="description">Description</th>
            <th scope="col" class="amount">Amount</th>
        </tr>
        </thead>
        @foreach($invoice->customers as $name => $data)
            @if (empty($data['billables'])) @continue @endif
            <tr>
                <td colspan="3" class="t-align-center">
                    <strong>{{ $name }}</strong>
                </td>
            </tr>
            @foreach($data['billables'] as $billable)
                @include('partials.pdf.invoices.row',
                        ['quantity' => "",
                        'description' => $billable['description'],
                        'cost' => \StringFormatter::formatCurrency($billable['cost']),
                        'class' => $billable['cost'] > 0  ? "amount-negative" : "amount-positive"])
            @endforeach
            <tr>
                <td></td>
                <td colspan="2" class="t-align-right"><strong>Total: {{ \StringFormatter::formatCurrency($data['total_cost']) }}</strong></td>
            </tr>
        @endforeach
        @foreach($invoice->groups as $name => $data)
            @if (empty($data['billables'])) @continue @endif
            <tr>
                <td colspan="3" class="t-align-center">
                    <strong>(Rooming Group) {{ $data['name'] }}</strong>
                </td>
            </tr>
            @foreach($data['billables'] as $billable)
                @include('partials.pdf.invoices.row',
                        ['quantity' => "",
                        'description' => $billable['description'],
                        'cost' => \StringFormatter::formatCurrency($billable['cost']),
                        'class' => $billable['cost'] > 0  ? "amount-negative" : "amount-positive"])
            @endforeach
            <tr>
                <td></td>
                <td colspan="2" class="t-align-right"><strong>Total: {{ \StringFormatter::formatCurrency($data['total_cost']) }}</strong></td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="t-align-center">
                <strong>Order Adjustments</strong>
            </td>
        </tr>
        @foreach($invoice->adjustments['billables'] as $billable)
            @include('partials.pdf.invoices.row',
                    ['quantity' => "",
                    'description' => $billable['description'],
                    'cost' => StringFormatter::formatCurrency( $billable['cost']),
                    'class' => $billable['cost'] > 0  ? "amount-negative" : "amount-positive"])
        @endforeach
        <tr>
            <td></td>
            <td colspan="2" class="t-align-right"><strong>Total: {{ StringFormatter::formatCurrency($invoice->adjustments['total_cost']) }}</strong></td>
        </tr>
    </table>
</div>
<div class="section t-align-right">
    <h2>Total Amount Owed: {{ StringFormatter::formatCurrency($invoice->total_cost) }}</h2>
</div>
<div class="section t-align-center">
    <h2>Payments</h2>
</div>
<div class="section">
    <table>
        <thead>
        <tr>
            <th scope="col" class="date">Date</th>
            <th scope="col" class="description">Method</th>
            <th scope="col" class="amount">Amount</th>
        </tr>
        </thead>
        @foreach($invoice->payments['billables'] as $billable)
            @include('partials.pdf.invoices.row',
                    ['quantity' => StringFormatter::formatDateTime($billable['date']),
                    'description' => $billable['description'],
                    'cost' => StringFormatter::formatCurrency($billable['cost']),
                    'class' => "amount",])
        @endforeach
    </table>
</div>
<div class="section t-align-right">
    <h2>Total Paid: {{ StringFormatter::formatCurrency($invoice->payments['total_cost']) }}</h2>
</div>
<div class="section t-align-right">
    <h2>Remaining Amount: {{ StringFormatter::formatCurrency($invoice->total_cost - $invoice->payments['total_cost']) }}</h2>
</div>
<div class="section t-align-center">
    <h2>Installments</h2>
</div>
<div class="section">
    <table>
        <thead>
        <tr>
            <th scope="col" class="date">Date</th>
            <th scope="col" class="description">Amount</th>
            <th scope="col" class="amount">Paid</th>
        </tr>
        </thead>
        @foreach($invoice->installments as $installment)
            @include('partials.pdf.invoices.row',
                    ['quantity' => StringFormatter::formatDateTime($installment['due']),
                    'description' => StringFormatter::formatCurrency($installment['amount']),
                    'cost' => StringFormatter::formatBoolean($installment['paid']),
                    'class' => "amount",])
        @endforeach
    </table>
</div>
<div class="section t-align-center">
    {!! $invoice->footer !!}
</div>
</body>
</html> -->
