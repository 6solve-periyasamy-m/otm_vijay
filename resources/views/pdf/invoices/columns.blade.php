<html>
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
            Invoice Number: 1010101010101<br/>
            Invoice Date: {{ now() }}<br/>
            Booking Ref: {{ $order->booking_reference }}<br/>
        </div>
    </div>
    <div class="header-cell t-align-right">
        <div style="display: block; width: 100%">
            <img src="{{ asset(\App\Repository\SettingsRepository::getOrDefault('company.logo', 'images/octlogo.png')) }}" class="header-logo" alt="{{ \App\Repository\SettingsRepository::get('company.name') }}">
        </div>
        <div class="header-company-details">
            Website: {{ URL::to('/') }}<br/>
            Email: {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.email', 'Email not set') }}<br/>
            Telephone: {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.phone', 'Phone number not set') }}<br/>
        </div>
    </div>
</div>
<div class="section">
    <div class="header-cell">
        {{ $order->leadBooker->customer->first_name . ' ' . $order->leadBooker->customer->last_name }}<br /><br />
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
        @foreach($orderCustomers as $oCustomer)
            <tr>
                <td colspan="3" class="t-align-center">
                    <strong>{{ $oCustomer['customer']->customer->title }} {{ $oCustomer['customer']->customer->first_name }} {{ $oCustomer['customer']->customer->middle_names }} {{ $oCustomer['customer']->customer->last_name }}</strong>
                </td>
            </tr>
            @include('partials.pdf.invoices.row',
                        ['quantity' => "",
                        'description' => "<strong>Base Cost of Package, including:</strong>\n" . $oCustomer['included'],
                        'cost' => $order->tour->base_price_per_person,
                        'class' => $order->tour->base_price_per_person > 0  ? "amount-negative" : "amount-positive"])
            @foreach($oCustomer['items'] as $item)
                @include('partials.pdf.invoices.row',
                        ['quantity' => $item['quantity'],
                        'description' => $item['description'],
                        'cost' => $item['cost'],
                        'class' => $item['cost'] > 0  ? "amount-negative" : "amount-positive"])
            @endforeach
            <tr>
                <td></td>
                <td colspan="2" class="t-align-right"><strong>Total: {{ $oCustomer['cost'] }}</strong></td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="t-align-center">
                <strong>Order Adjustments</strong>
            </td>
        </tr>
        @foreach($adjustments as $adjustment)
            @include('partials.pdf.invoices.row',
                    ['quantity' => "",
                    'description' => $adjustment['reason'] . ' (' . $adjustment['date'] . ')',
                    'cost' => $adjustment['amount'],
                    'class' => $adjustment['amount'] > 0  ? "amount-negative" : "amount-positive"])
        @endforeach
        <tr>
            <td></td>
            <td colspan="2" class="t-align-right"><strong>Total: {{ $totals['adjusted'] }}</strong></td>
        </tr>
    </table>
</div>
<div class="section t-align-right">
    <h2>Total Amount Owed: {{ $totals['orderValue'] + $totals['adjusted'] }}</h2>
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
        @foreach($payments as $payment)
            @include('partials.pdf.invoices.row',
                    ['quantity' => $payment['date'],
                    'description' => $payment['method'],
                    'cost' => $payment['amount'],
                    'class' => "amount",])
        @endforeach
    </table>
</div>
<div class="section t-align-right">
    <h2>Total Paid: {{ $totals['paid'] }}</h2>
</div>
<div class="t-align-right">
    <h2>Remaining Amount: {{ $totals['combined'] }}</h2>
</div>
</body>
</html>
