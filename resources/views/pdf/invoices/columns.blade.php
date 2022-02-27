@php
/**
 * @var App\Models\Invoice $invoice
 * @var App\Models\Order $order
 */
$order = $invoice->order;
//dd($invoice);
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
                            <div class="metadata divider">Date<br /><span class="metadata-text">{{ StringFormatter::formatDate($invoice->generated) }}</span></div>
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
            <!-- Order Section -->
            <div class="section">
                <table class="order-table center">
                    <thead>
                        <tr>
                            <td class="order-table-title description">Description</td>
                            <td class="order-table-title quantity">Quantity</td>
                            <td class="order-table-title total">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->customers as $name => $data)
                            @if (empty($data['billables'])) @continue @endif
                            <tr>
                                <td colspan="3" class="metadata center-text">{{ $name }}</td>
                            </tr>
                            @foreach($data['billables'] as $billable)
                                @include('partials.pdf.invoices.row',
                                        ['quantity' => "",
                                        'description' => $billable['description'],
                                        'cost' => \StringFormatter::formatCurrency($billable['cost']),
                                        'class' => $billable['cost'] > 0  ? "color red" : "color green"])
                            @endforeach
                            <tr>
                                <td colspan="3" class="metadata right-text">Total: {{ \StringFormatter::formatCurrency($data['total_cost']) }}</td>
                            </tr>
                        @endforeach
                        @foreach($invoice->groups as $name => $data)
                            @if (empty($data['billables'])) @continue @endif
                            <tr>
                                <td colspan="3" class="metadata center-text">(Rooming Group) {{ $data['name'] }}</td>
                            </tr>
                            @foreach($data['billables'] as $billable)
                                @include('partials.pdf.invoices.row',
                                        ['quantity' => "",
                                        'description' => $billable['description'],
                                        'cost' => \StringFormatter::formatCurrency($billable['cost']),
                                        'class' => $billable['cost'] > 0  ? "color red" : "color green"])
                            @endforeach
                            <tr>
                                <td colspan="3" class="metadata right-text">Total: {{ \StringFormatter::formatCurrency($data['total_cost']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="order-table center">
                    <thead>
                        <tr>
                            <td class="order-table-title date">Date</td>
                            <td class="order-table-title description">Description</td>
                            <td class="order-table-title total">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="metadata center-text" colspan="3">Order Adjustments</td>
                        </tr>
                    @foreach($invoice->adjustments['billables'] as $billable)
                        <tr>
                            <td class="date"></td>
                            <td class="date-description">{!! nl2br($billable['description']) !!} </td>
                            <td class="total {{ $billable['cost'] > 0  ? 'color red' : 'color green' }}">{{ StringFormatter::formatCurrency( $billable['cost']) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="flex-container-reverse title">
                    <div class="flex-items">
                        <h1 class="header-title" style="margin-top:5px">Total Amount Owed: {{ StringFormatter::formatCurrency($invoice->adjustments['total_cost']) }}</h1>
                    </div>
                </div>
            </div>
            <div class="pagebreak"></div>
            <div class="pageborder"></div>
            <!-- Payments Section -->
            <div class="section">
                <h2 class="section-title header-title">Payments</h2>
                <table class="order-table center">
                    <thead>
                        <tr>
                            <td class="order-table-title date">Date</td>
                            <td class="order-table-title description">Description</td>
                            <td class="order-table-title total">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="metadata center-text" colspan="3">Method</td>
                        </tr>
                        @foreach($invoice->payments['billables'] as $billable)
                            <tr>
                                <td class="date">{{ StringFormatter::formatDateTime($billable['date']) }}</td>
                                <td class="date-description">{!! nl2br($billable['description']) !!} </td>
                                <td class="total">{{ StringFormatter::formatCurrency( $billable['cost']) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="metadata right-text" colspan="3">Total Paid: {{ StringFormatter::formatCurrency($invoice->payments['total_cost']) }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex-container-reverse title">
                    <div class="flex-items">
                        <h1 class="header-title" style="margin-top:5px">Remaining Amount: {{ StringFormatter::formatCurrency($invoice->total_cost - $invoice->payments['total_cost']) }}</h1>
                    </div>
                </div>
            </div>
            <!-- Installments Section -->
            <div class="section">
                <h2 class="section-title header-title">Installments</h2>
                <table class="order-table center">
                    <thead>
                        <tr>
                            <td class="order-table-title date">Date</td>
                            <td class="order-table-title amount">Amount</td>
                            <td class="order-table-title paid">Paid</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->installments as $installment)
                            <tr>
                                <td class="date">{{ StringFormatter::formatDate($installment['due']) }}</td>
                                <td class="amount">{!! nl2br(StringFormatter::formatCurrency($installment['amount'])) !!} </td>
                                <td class="total">{{ StringFormatter::formatBoolean($installment['paid']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Notes Section -->
            <div class="section">
                <h2 class="section-title header-title">Notes</h2>
                <div class="notes">
                    <div style="margin-top: 0">{!! $invoice->footer !!}</div> 
                </div>
            </div>
        </div>
    </body>
</html>
