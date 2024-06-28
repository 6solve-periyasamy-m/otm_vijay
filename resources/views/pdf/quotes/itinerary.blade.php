@php
    /**
     * @var \App\Repository\Storage\Itinerary\Itinerary $itinerary
     * @var string $type
     */
    $type = $type ?? "Travel Itinerary"
@endphp

        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

    <style type="text/css">
        <?php include(public_path() . '/css/kpt.css') ?>
        {!! setting('customization.documentation.colors') !!}
    </style>
    <title>{{ $itinerary->package }} | {{ $itinerary->reference }} | {{ $type }}</title>
</head>

<body class="body">
    @include('partials.pdf.kpt.logos', ['type' => $type,])
    <!-- Header -->
    @include('partials.pdf.kpt.header.new')

    @if(!empty($itinerary->description))
        <div class="avoid-break">
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text">
                            EVENT INFORMATION
                        </th>
                    </tr>
                </thead>
            </table>
            <div class="text-section">
                <p>{!! $itinerary->description !!}</p>
            </div>
        </div>
    @endif

    <!-- Itinerary Divider -->
    <table class="divider">
        <thead>
            <tr>
                <th class="text">
                    PACKAGE INCLUSIONS
                </th>
            </tr>
        </thead>
    </table>

    <!-- Components -->
    @foreach($itinerary->items as $header => $items)
            <table class="date-header">
                <tr>
                    <td colspan="2" class="text-left">
                        <div class="date-content">
                            {{ $header }}
                        </div>
                    </td>
                </tr>
            </table>
            @php /** @var \App\Repository\Storage\Itinerary\ItineraryItem $item */ @endphp
            @foreach($items as $item)
                <table class="item-table avoid-break">
                    @if($item->name !== null)
                        <table class="sub-date-header">
                            <tr>
                                <td colspan="2" class="text-left">
                                    <div class="sub-date-content">
                                        {{ $item->name }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    @endif
                    @foreach($item->details as $key => $value)
                        @continue(empty($value))
                        <tr>
                            <td class="item-header" style="width: 125px">
                                {{ $key }}:
                            </td>
                            <td class="item-detail">
                                {!! $value !!}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="cell-header fs-12 text-dark">&nbsp;</td>
                        <td class="empty-cell">&nbsp;</td>
                    </tr>
                </table>
            @endforeach
    @endforeach

    @if($itinerary->finances !== null)
        <div id="finances" class="avoid-break">
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text">
                            PAYMENT SUMMARY
                        </th>
                    </tr>
                </thead>
            </table>
            <!-- Payments Table -->
            @if(count($itinerary->finances->payments) > 0)
                <table class="date-header">
                    <tr>
                        <td colspan="2" class="text-left">
                            <div class="date-content">
                                PAYMENTS MADE
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="schedule-table">
                    <tr>
                        <td class="schedule-td">
                            <table class="schedule-table">
                                <thead>
                                    <tr>
                                        <th>
                                            MADE ON
                                        </th>
                                        <th>
                                            AMOUNT PAID
                                        </th>
                                        <th>
                                            PAYMENT TYPE
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itinerary->finances->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->made->format('d F Y') }}</td>
                                            <td>{{ f_currency($payment->amount) }}</td>
                                            <td>{{ $payment->type }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            @endif
            <!-- Cost Details -->
            <div class="avoid-break">
                <table class="date-header">
                    <tr>
                        <td colspan="2" class="text-left">
                            <div class="date-content">
                                ORDER TOTAL
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="payment-details">
                    @if($itinerary->finances->cost !== $itinerary->finances->total || $itinerary->finances->tax !== null)
                        <tr>
                            <td class="payment-details-title">
                                BOOKING TOTAL
                            </td>
                            <td class="payment-details-content">
                                {{ f_currency($itinerary->finances->total) }}
                            </td>
                        </tr>
                        @if($itinerary->finances->tax !== null)
                            <tr>
                                <td class="payment-details-title">
                                    GST (included)
                                </td>
                                <td class="payment-details-content">
                                    {{ $itinerary->finances->tax > 0 ? f_currency($itinerary->finances->tax) : 'No Taxes Due' }}
                                </td>
                            </tr>
                        @endif
                        @if($itinerary->finances->commission !== null)
                            <tr>
                                <td class="payment-details-title">
                                    COMMISSION
                                </td>
                                <td class="payment-details-content">
                                    {{ f_currency($itinerary->finances->commission) }} ({{ $order->commission }}%)
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="2" class="payment-details-blank">&nbsp;</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="payment-details-title">
                            FINAL COST
                        </td>
                        <td class="payment-details-content">
                            {{ f_currency($itinerary->finances->cost) }}
                        </td>
                    </tr>
                    @if($itinerary->finances->paid() > 0)
                        <tr>
                            <td class="payment-details-title">
                                TOTAL PAID
                            </td>
                            <td class="payment-details-content">
                                {{ f_currency($itinerary->finances->paid()) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="payment-details-title">
                                REMAINING
                            </td>
                            <td class="payment-details-content">
                                {{ f_currency($itinerary->finances->cost - $itinerary->finances->paid()) }}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            <!-- Schedule Table -->
            @if(count($itinerary->finances->schedule) > 0)
                <table class="date-header">
                    <tr>
                        <td colspan="2" class="text-left">
                            <div class="date-content">
                                PAYMENT SCHEDULE
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="schedule-table">
                    <tr>
                        <td class="schedule-td">
                            <table class="schedule-table">
                                <thead>
                                    <tr>
                                        <th>
                                            INSTALMENTS
                                        </th>
                                        <th>
                                            AMOUNT DUE
                                        </th>
                                        <th>
                                            DATE DUE
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itinerary->finances->schedule as $installment)
                                        <tr>
                                            <td>{{ $installment->type->label() }}</td>
                                            <td>{{ f_currency($installment->amount) }} ({{$installment->percentage}}%)</td>
                                            <td>
                                                @if($installment->due === null)
                                                    With Order
                                                @else
                                                    {{ $installment->due->format('d F Y') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            @endif

            @if(!empty($itinerary->finances->paymentDetails))
                <div class="avoid-break">
                    <table class="divider">
                        <thead>
                            <tr>
                                <th class="text">
                                    PAYMENT DETAILS
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div  class="text-section">
                        <p>{!! $itinerary->finances->paymentDetails !!}</p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if(!empty($itinerary->notes))
        <div>
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text">
                            NOTES
                        </th>
                    </tr>
                </thead>
            </table>
            <div class="text-section">
                <p>{!! $itinerary->notes !!}</p>
            </div>
        </div>
    @endif

    @if(!empty($itinerary->terms))
        <div>
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text">
                            TERMS & CONDITIONS
                        </th>
                    </tr>
                </thead>
            </table>
            <div  class="text-section">
                <p>{!! $itinerary->terms !!}</p>
            </div>
        </div>
    @endif

    @if(!empty($itinerary->footer))
        <div>
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text">
                            ADDITIONAL DETAILS
                        </th>
                    </tr>
                </thead>
            </table>
            <div  class="text-section">
                <p>{!! $itinerary->footer !!}</p>
            </div>
        </div>
    @endif
</body>
</html>
