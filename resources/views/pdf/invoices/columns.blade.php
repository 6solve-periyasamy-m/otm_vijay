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
    <title>Invoice</title>
    <style>
        body { margin: 2px; border: 1px solid black; padding: 20px; border-radius: 5px; }
        td, th { border: 1px solid black; }
        table { width: 100%; }
        .header { border: 1px solid black; padding: 2px; }
        .section { margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid black; }
        .date { width: 10%; text-align: left; }
        .description { width: 80%; text-align: center; }
        .amount, .amount-positive, .amount-negative { width: 10%; text-align: right; }
        .amount-positive { color: green; }
        .amount-negative { color: darkred; }
        .header-cell { min-width: 33%; display: inline-block; margin-left: auto; margin-right: auto; }
        .t-align-left { text-align: left; }
        .t-align-center { text-align: center; }
        .t-align-right { text-align: right; }
        .header-logo { width: auto; height: 75px; float: right; }
        .header-company-details { padding-left: 50%; display: block; width: 50%; clear: right; text-align: left; }
        .header-title { display: block; width: 100%; float: top; }
        .invoice-details { display: block; width: 40%; padding: 0 30% }
    </style>
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
</html>
