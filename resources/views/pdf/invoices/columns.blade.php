@php
/**
 * @var \App\Models\Order\Invoice $invoice
 * @var \App\Models\Order\Order $order
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
        <style>
            .cancelled {
                background-image: url('{{ asset('images/rubberstamp.svg') }}') !important;
                background-repeat: no-repeat !important;
                background-position-x: calc(50% + 3em) !important;
                background-position-y: 8em;
                background-size: 30em;
            }
        </style>
    </head>
    <body>
        <div class="background center-screen @if($order->cancelled) cancelled @endif">
            <!-- Header Section -->
            <div class="section">
                <div class="header">
                    <div class="flex-container titles">
                        <div class="flex-items site-info vert-align">
                            <img src="{{ asset(setting('company.logo', 'images/octlogo.png')) }}" class="header-logo" alt="{{ setting('company.name') }}" />
                        </div>
                        <div class="flex-items vert-align">
                            <h2 class="header-title tour-name">{{ $order->tour->name }}</h2>
                        </div>
                        <div class="flex-items vert-align">
                            <h1 class="header-title">Invoice</h1>
                        </div>
                    </div>
                    <div class="flex-container">
                        <div class="flex-items">
                            <span class="metadata">Website:</span> <a class="site-info-padding" href="{{ setting('company.url', URL::to('/')) }}">{{ setting('company.url', URL::to('/')) }}</a>
                            <br /><span class="metadata">Email:</span> <a class="site-info-padding" href="mailto:{{ setting('company.contact.email', 'Email not set') }}">{{ setting('company.contact.email', 'Email not set') }}</a>
                            <br /><span class="metadata">Telephone:</span> <a class="site-info-padding" href="tel:{{ setting('company.contact.phone', 'Phone number not set') }}">{{ setting('company.contact.phone', 'Phone number not set') }}</a>
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
                        <div class="metadata divider"><span class="metadata-title">Billing Address</span></div>
                    </div>
                    <div class="flex-items billing-info-wrapper">
                        <div class="metadata divider"><span class="metadata-title">Supplier Address</span></div>
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
                        <div class="billing-info">{{ setting('company.address.line_1', 'Company Address Line 1 Not Set') }}</div>
                        <div class="billing-info">{{ setting('company.address.line_2', 'Company Address Line 2 Not Set') }}</div>
                        <div class="billing-info">{{ setting('company.address.city', 'Company City Not Set') }}</div>
                        <div class="billing-info">{{ setting('company.address.region', 'Company Region Not Set') }}</div>
                        <div class="billing-info">{{ setting('company.address.country', 'Company Country Not Set') }}</div>
                        <div class="billing-info">{{ setting('company.address.postcode', 'Company Postcode Not Set') }}</div>
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
                                <tr>
                                    <td class="description"><div class="order-table-description">{!! nl2br($billable['description']) !!}</div></td>
                                    <td class="quantity">1</td>
                                    <td class="total {{ $billable['cost'] > 0  ? 'color red' : 'color green' }}">{{ StringFormatter::formatCurrency($billable['cost']) }}</td>
                                </tr>
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
                                <tr>
                                    <td class="description">{!! nl2br($billable['description']) !!} </td>
                                    <td class="quantity">1</td>
                                    <td class="total {{ $billable['cost'] > 0  ? 'color red' : 'color green' }}">{{ StringFormatter::formatCurrency($billable['cost']) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="metadata right-text">Total: {{ \StringFormatter::formatCurrency($data['total_cost']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagebreak"></div>
            <div class="pageborder"></div>
            <!-- Order Adjustments Section -->
            <div class="section pagebreak-inside">
                <h2 class="section-title header-title">Order Adjustments</h2>
                <table class="order-table center">
                    <thead>
                        <tr>
                            <td class="order-table-title date">Date</td>
                            <td class="order-table-title description">Description</td>
                            <td class="order-table-title total">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($invoice->adjustments['billables']))
                            <tr>
                                <td colspan="3" class="center-text">No Order Adjustments recorded.</td>
                            </tr>
                        @else
                            @foreach($invoice->adjustments['billables'] as $billable)
                                <tr>
                                    <td class="date"></td>
                                    <td class="date-description">{!! nl2br($billable['description']) !!} </td>
                                    <td class="total {{ $billable['cost'] > 0  ? 'color red' : 'color green' }}">{{ StringFormatter::formatCurrency( $billable['cost']) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="metadata right-text">Total: {{ StringFormatter::formatCurrency($invoice->adjustments['total_cost']) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="flex-container-reverse title">
                    <div class="flex-items">
                        <h1 class="header-title" style="margin-top:5px">Total Amount Owed: {{ StringFormatter::formatCurrency($order->cancelled ? 0 : $invoice->total_cost) }}</h1>
                    </div>
                </div>
            </div>
            <!-- Payments Section -->
            <div class="section pagebreak-inside">
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
                        @if (empty($invoice->payments['billables']))
                            <tr>
                                <td colspan="3" class="center-text">No Payments recorded.</td>
                            </tr>
                        @else
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
                        @endif
                    </tbody>
                </table>
                <div class="flex-container-reverse title">
                    <div class="flex-items">
                        <h1 class="header-title" style="margin-top:5px">Remaining Amount: {{ StringFormatter::formatCurrency($order->cancelled ? 0 : ($invoice->total_cost - $invoice->payments['total_cost'])) }}</h1>
                    </div>
                </div>
            </div>
            <!-- Installments Section -->
            <div class="section pagebreak-inside">
                <h2 class="section-title header-title">Installments</h2>
                <table class="order-table center">
                    <thead>
                        <tr>
                            <td class="order-table-title date">Due Date</td>
                            <td class="order-table-title amount">Amount</td>
                            <td class="order-table-title paid">Paid</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($invoice->installments))
                                <tr>
                                    <td colspan="3" class="center-text">No Installments recorded.</td>
                                </tr>
                            @else
                            @foreach($invoice->installments as $installment)
                                <tr>
                                    <td class="date">{{ StringFormatter::formatDate($installment['due']) }}</td>
                                    <td class="amount">{!! array_key_exists('description',$installment) ? nl2br($installment['description']) : nl2br(StringFormatter::formatCurrency($installment['amount'])) !!} </td>
                                    <td class="paid">{{ StringFormatter::formatBoolean($installment['paid']) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Notes Section -->
            <div class="section pagebreak-inside">
                <h2 class="section-title header-title">Notes</h2>
                <div class="notes">
                    <div style="margin-top: 0">{!! $invoice->footer !!}</div> 
                </div>
            </div>
        </div>
    </body>
</html>
