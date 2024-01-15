@php
/**
 * @var \App\Models\Order\Invoice\Invoice $invoice
 * @var \App\Models\Order\Order $order
 */
@endphp

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invoice</title>
        <style>
            <?php include(public_path().'/css/invoice.css') ?>
        </style>
        <style>
            .cancelled {
                background-image: url('{{ svg_to_b64('images/rubberstamp.svg') }}') !important;
                background-repeat: no-repeat !important;
                background-position-x: calc(50% + 3em) !important;
                background-position-y: 8em;
                background-size: 30em;
            }
        </style>
    </head>
    <body>
        <div class="background center-screen @if($invoice->cancelled) cancelled @endif">
            <!-- Header Section -->
            <div class="section">
                <div class="header">
                    <div class="flex-container titles">
                        <div class="flex-items site-info vert-align">
                            <img src="{{ img_to_b64($invoice->brand->logo) }}" class="header-logo" alt="{{ $invoice->brand->name }}" />
                        </div>
                        <div class="flex-items vert-align">
                            <h2 class="header-title tour-name">{{ $invoice->order->tour->name }}</h2>
                        </div>
                        <div class="flex-items vert-align">
                            <h1 class="header-title">Invoice</h1>
                        </div>
                    </div>
                    <div class="flex-container">
                        <div class="flex-items">
                            <span class="metadata">Website:</span> <a class="site-info-padding" href="{{ $invoice->brand->website ?? setting('company.url', URL::to('/')) }}">{{ $invoice->brand->website ?? setting('company.url', URL::to('/')) }}</a>
                            <br /><span class="metadata">Email:</span> <a class="site-info-padding" href="mailto:{{ $invoice->brand->email ?? setting('company.contact.email', 'Email not set') }}">{{ $invoice->brand->email ??  setting('company.contact.email', 'Email not set') }}</a>
                            <br /><span class="metadata">Telephone:</span> <a class="site-info-padding" href="tel:{{ $invoice->brand->telephone ?? setting('company.contact.phone', 'Phone number not set') }}">{{ $invoice->brand->telephone ?? setting('company.contact.phone', 'Phone number not set') }}</a>
                        </div>
                        <div class="flex-items metadata-wrapper">
                            <div class="metadata divider">Date<br /><span class="metadata-text">{{ f_date($invoice->generated) }}</span></div>
                            <div class="metadata divider">Invoice No.<br /><span class="metadata-text">{{ $invoice->invoice_number }}</span></div>
                            <div class="metadata divider">Booking Ref.<br /><span class="metadata-text">{{ $invoice->booking_reference }}</span></div>
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
                        <div class="billing-info">{{ $invoice->lead->full_name }}</div>
                        <div class="billing-info">{{ $invoice->lead->address_line_1 }}{!! isset($invoice->lead->address_line_1) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $invoice->lead->address_line_2 }}{!! isset($invoice->lead->address_line_2) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $invoice->lead->town }}{!! isset($invoice->lead->town) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $invoice->lead->region }}{!! isset($invoice->lead->region) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $invoice->lead->country }}{!! isset($invoice->lead->country) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $invoice->lead->postcode }}{!! isset($invoice->lead->postcode) ? "<br />" : "" !!}</div>
                    </div>
                    <div class="flex-items billing-info-wrapper">
                        <div class="billing-info">{{ $invoice->brand->address_line_1 }}</div>
                        <div class="billing-info">{{ $invoice->brand->address_line_2 }}</div>
                        <div class="billing-info">{{ $invoice->brand->town  }}</div>
                        <div class="billing-info">{{ $invoice->brand->region  }}</div>
                        <div class="billing-info">{{ $invoice->brand->country  }}</div>
                        <div class="billing-info">{{ $invoice->brand->postcode  }}</div>
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
                        @foreach($invoice->customers as $customer)
                            @if ($customer->billables()->count() === 0) @continue @endif
                            <tr>
                                <td colspan="3" class="metadata center-text">{{ $customer->full_name }}</td>
                            </tr>
                            @foreach($customer->billables as $billable)
                                <tr>
                                    <td class="description"><div class="order-table-description">{!! nl2br($billable->description) !!}</div></td>
                                    <td class="quantity">1</td>
                                    <td class="total {{ $billable->amount > 0  ? 'color red' : 'color green' }}">{{ f_currency($billable->amount) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="metadata right-text">Total: {{ f_currency($customer->total_cost) }}</td>
                            </tr>
                        @endforeach
                        @foreach($invoice->groups as $group)
                            @if ($group->billables()->count() === 0) @continue @endif
                            <tr>
                                <td colspan="3" class="metadata center-text">{{ $group->name }}</td>
                            </tr>
                            @foreach($group->billables as $billable)
                                <tr>
                                    <td class="description"><div class="order-table-description">{!! nl2br($billable->description) !!}</div></td>
                                    <td class="quantity">1</td>
                                    <td class="total {{ $billable->amount > 0  ? 'color red' : 'color green' }}">{{ f_currency($billable->amount) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="metadata right-text">Total: {{ f_currency($group->billables()->sum('amount')) }}</td>
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
                        @if ($invoice->adjustments()->count() === 0)
                            <tr>
                                <td colspan="3" class="center-text">No Order Adjustments recorded.</td>
                            </tr>
                        @else
                            @foreach($invoice->adjustments as $adjustment)
                                <tr>
                                    <td class="date">{{ f_datetime($adjustment->date) }}</td>
                                    <td class="date-description">{!! nl2br($adjustment->description) !!} </td>
                                    <td class="total {{ $adjustment->amount > 0  ? 'color red' : 'color green' }}">{{ f_currency($adjustment->amount) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="metadata right-text">Total: {{ f_currency($invoice->adjustments()->sum('amount')) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="flex-container-reverse title">
                    <div class="flex-items">
                        <h1 class="header-title" style="margin-top:5px">Total Amount Owed: {{ f_currency($invoice->cancelled ? 0 : $invoice->total_cost) }}</h1>
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
                            <td class="order-table-title description">Paid By</td>
                            <td class="order-table-title total">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($invoice->payments()->count() === 0)
                            <tr>
                                <td colspan="3" class="center-text">No Payments recorded.</td>
                            </tr>
                        @else
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td class="date">{{ f_datetime($payment->date) }}</td>
                                    <td class="date-description">{{ $payment->payee ?? "Not Recorded" }}</td>
                                    <td class="total">{{ f_currency($payment->amount) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="metadata right-text" colspan="3">Total Paid: {{ f_currency($invoice->total_paid) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="flex-container-reverse title">
                    <div class="flex-items">
                        <h1 class="header-title" style="margin-top:5px">Remaining Amount: {{ f_currency($invoice->cancelled ? 0 : ($invoice->total_cost - $invoice->total_paid)) }}</h1>
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
                        @if ($invoice->installments()->count() === 0)
                            <tr>
                                <td colspan="3" class="center-text">No Installments recorded.</td>
                            </tr>
                        @else
                            @foreach($invoice->installments as $installment)
                                <tr>
                                    <td class="date">{{ f_date($installment->due) }}</td>
                                    <td class="amount">{{ $installment->description }}</td>
                                    <td class="paid">{{ f_bool($installment->paid) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Footer Section -->
            <div class="section pagebreak-inside">
                <h2 class="section-title header-title">Notes</h2>
                <div class="notes">
                    <div style="margin-top: 0">{!! $invoice->invoice_footer !!}</div>
                </div>
            </div>
            <!-- Notes Section -->
            <div class="section pagebreak-inside">
                <h2 class="section-title header-title">Notes</h2>
                <div class="notes">
                    <div style="margin-top: 0">{!! $invoice->order_notes !!}</div>
                </div>
            </div>
        </div>



    </body>
</html>
