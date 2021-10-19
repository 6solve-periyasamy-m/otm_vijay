@extends('layout.main')

@section('title', 'View Order')
{{-- TODO: Tidy up CSS --}}
@section('content')
{{-- Header Details --}}
<div id="header-details">
    <table class="table" style="border-bottom: 1px solid black; font-size: 32px">
        <tr>
            <td style="border: 1px solid black; font-size: 24px">{{ $order->booking_reference }}</td>
            <td style="border: 1px solid black; font-size: 24px">{{ $order->tour->title }}</td>
            <td style="border: 1px solid black; font-size: 24px">{{ $order->tour->date_from . " to " . $order->tour->date_to }}</td>
            <td style="border: 1px solid black; background-color: {{ $totalPaid >= $totalOrderValue ? "#33ff99" : "#ffff99" }}; font-size: 24px">{{ $totalPaid >= $totalOrderValue ? "Paid in Full" : "Balance Outstanding" }}</td>
        </tr>
    </table>
</div>
{{-- Order Overview and Customers Section --}}
<div id="section-1" style="margin-bottom: 5px; border: 1px solid black;">
    {{-- Customers Table --}}
    <div id="customers" style="width: 50%; display: inline-block; vertical-align: bottom;">
        <div id="customers-header" style="max-width: 20%; font-size: 24px; border-right: 1px solid black;">
            Customers
        </div>
        <div id="customers-details" style="width: 100%">
            <table style="margin-bottom: 2px;">
                @foreach($customers as $ordersCustomer)
                <tr>
                    <td style="border: 1px solid black; border-left: 0; width: 30%;">
                        <a href="{{ route('orderCustomerDetails', ['id' => $ordersCustomer->customer->id]) }}" class="link-info"><u>
                            {{ $ordersCustomer->customer->first_name .  " " . $ordersCustomer->customer->last_name }}
                        </u></a>
                    </td>
                    <td style="border: 1px solid black; width: 30%;">Born: {{ $ordersCustomer->customer->date_of_birth }}</td>
                    <td style="border: 1px solid black; width: 30%;">Passport Number: {{ $ordersCustomer->customer->passport_number }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    {{-- Order Overview Table --}}
    <div id="overview-details" style="width:49%; min-width: 49%; display:inline-block; vertical-align: bottom; horiz-align: right">
        <div id="values-header"><br/></div>
        <div id="values-table">
        <table style="float: right; margin-bottom: 2px;">
            <tr>
                <td style="border: 1px solid black; width: 70%;"><b>Order Value:&nbsp;</b></td>
                <td style="border: 1px solid black; width: 30%;">{{ $totalOrderValue }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; width: 70%;"><b>Balance Paid:&nbsp;</b></td>
                <td style="border: 1px solid black; width: 30%;">{{ $totalPaid }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; width: 70%;"><b>Balance Outstanding:&nbsp;</b></td>
                <td style="border: 1px solid black; width: 30%;">{{ $totalOrderValue - $totalPaid }}</td>
            </tr>
        </table>
        </div>
    </div>
</div>

<div id="billing-section" style="border: 1px solid black">
    <div id="billing-header" style="max-width: 20%; font-size: 24px; border: 1px solid black; border-top: 0; border-left: 0; margin-bottom: 2px;">
        Billing & Payments
    </div>
    {{-- Payments Table--}}
    <div id="payments-section">
        <div id="customers-details" style="min-width: 60%; max-width: 60%; display: inline-block; vertical-align: bottom;">
            <div id="customers-header" style="max-width: 35%; font-size: 24px; border: 1px solid black; border-bottom: 0;">
                Payments
            </div>
            <table style="min-width: 100%; margin-bottom: 1px">
                <thead>
                    <tr>
                        <th scope="col" style="border: 1px solid black; width: 25%;">Type</th>
                        <th scope="col" style="border: 1px solid black; width: 25%;">Value</th>
                        <th scope="col" style="border: 1px solid black; width: 25%;">Due Date</th>
                        <th scope="col" style="border: 1px solid black; width: 25%;">Paid Date</th>
                    </tr>
                </thead>
                @foreach($payments as $payment)
                    <tr>
                        <td style="border: 1px solid black; width: 20%;">{{ $payment->payment_type }}</td>
                        <td style="border: 1px solid black; width: 20%;">{{ $payment->amount }}</td>
                        <td style="border: 1px solid black; width: 20%;">{{ $payment->paid_on }}</td> {{-- TODO: Get actual due date --}}
                        <td style="border: 1px solid black; width: 20%;">{{ $payment->paid_on }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div id="overview-details" style="width:39%; min-width: 39%; display:inline-block; vertical-align: bottom; horiz-align: right">
            <div id="values-header"><br/></div>
            {{-- Buttons Table--}}
            <div id="values-table">
                <table style="float: right; margin-bottom: 2px;">
                    <tr>
                        <td style="width: 70%;">
                            <button class="btn btn-primary float-right" onclick="alert('This is non-functional')">View Invoice</button>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70%;">
                            <button class="btn btn-primary float-right" onclick="alert('This is non-functional')">Email Invoice</button>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70%;">
                            <button class="btn btn-primary float-right" onclick="alert('This is non-functional')">View Previous Invoices</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    {{-- Costs Table --}}
    <div id="costs-section">
        <div id="customers-details" style="min-width: 60%; max-width: 60%; display: inline-block; vertical-align: bottom;">
            <div id="customers-header" style="max-width: 35%; font-size: 24px; border: 1px solid black; border-bottom: 0;">
                Costs
            </div>
            <table style="min-width: 100%; margin-bottom: 1px">
                <thead>
                <tr>
                    <th scope="col" style="border: 1px solid black; width: 25%;">Type</th>
                    <th scope="col" style="border: 1px solid black; width: 25%;">Value</th>
                </tr>
                </thead>
                <tr>
                    <td style="border: 1px solid black; width: 20%;">Base</td>
                    <td style="border: 1px solid black; width: 20%;">{{ $order->tour->base_price_per_person * sizeof($customers) }}</td>
                </tr>
                @foreach($addons as $addon)
                    <tr>
                        <td style="border: 1px solid black; width: 20%;">Add-on</td>
                        <td style="border: 1px solid black; width: 20%;">{{ $addon->tour_sales_price }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    {{-- Manual Adjustments Table --}}
    <div id="costs-section">
        <div id="customers-details" style="min-width: 60%; max-width: 60%; display: inline-block; vertical-align: bottom;">
            <div id="customers-header" style="max-width: 50%; font-size: 24px; border: 1px solid black; border-bottom: 0;">
                Order Adjustments <a href="{{ route('manual-adjustments.create', ['order' => $order, ]) }}" class="d-inline btn btn-success">Add Adjustment</a>
            </div>
            <table style="min-width: 100%; margin-bottom: 1px">
                <thead>
                <tr>
                    <th scope="col" style="border: 1px solid black; width: 25%;">Amount</th>
                    <th scope="col" style="border: 1px solid black; width: 50%;">Reason</th>
                </tr>
                </thead>
                @foreach($order->adjustments as $adjustment)
                    <tr>
                        <td style="border: 1px solid black; width: 20%;">{{ $adjustment->amount }}</td>
                        <td style="border: 1px solid black; width: 20%;">{{ $adjustment->reason }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    {{-- Customer Adjustments Table --}}
    <div id="costs-section">
        <div id="customers-details" style="min-width: 60%; max-width: 60%; display: inline-block; vertical-align: bottom;">
            <div id="customers-header" style="max-width: 35%; font-size: 24px; border: 1px solid black; border-bottom: 0;">
                Customer Adjustments
            </div>
            <table style="min-width: 100%; margin-bottom: 1px">
                <thead>
                <tr>
                    <th scope="col" style="border: 1px solid black; width: 25%;">Customer</th>
                    <th scope="col" style="border: 1px solid black; width: 25%;">Amount</th>
                    <th scope="col" style="border: 1px solid black; width: 50%;">Reason</th>
                </tr>
                </thead>
                @foreach($customers as $ordersCustomer)
                    @foreach($ordersCustomer->adjustments as $adjustment)
                    <tr>
                        <td style="border: 1px solid black; width: 20%;">{{ $ordersCustomer->customer->first_name .  " " . $ordersCustomer->customer->last_name }}</td>
                        <td style="border: 1px solid black; width: 20%;">{{ $adjustment->amount }}</td>
                        <td style="border: 1px solid black; width: 20%;">{{ $adjustment->reason }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </table>
        </div>
    </div>
</div>

{{-- Closing Container--}}
@endsection
