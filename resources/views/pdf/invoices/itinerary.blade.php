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

    <style type="text/css"><?php include(public_path() . '/css/kpt.css') ?></style>
    <title>{{ $itinerary->package }} | {{ $itinerary->reference }} | {{ $type }}</title>
</head>

<body class="body">
    @include('partials.pdf.kpt.logos', ['type' => $type,])
    <!-- Header -->
    @include('partials.pdf.kpt.header.old')

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
                    TRIP ITINERARY AND INCLUSION
                </th>
            </tr>
        </thead>
    </table>

    <!-- Components -->
    @php $day = 0; @endphp
    @foreach($itinerary->items as $date => $items)
        @php
            $date = \Carbon\Carbon::createFromTimestamp($date);
            $day += isset($previous) ? diff_in_nights($date, $previous) : 1;
            $previous = $date;
        @endphp

        <table class="date-header">
            <tr>
                <td colspan="2" class="text-left">
                    <div class="date-content">
                        DAY {{ $day }} - {{ $date->format('l jS F Y') }}
                    </div>
                </td>
            </tr>
        </table>
        @php /** @var \App\Repository\Storage\Itinerary\ItineraryItem $item */ @endphp
        @foreach($items as $item)
            <table class="item-table">
                <tr>
                    <td class="item-title">
                        {{ strtoupper($item->type) }}
                    </td>
                </tr>
                @foreach($item->details as $key => $value)
                    <tr>
                        <td class="item-header">
                            {{ strtoupper($key) }}:
                        </td>
                        <td class="item-detail">
                            {!! $value !!}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="item-header">
                        QUANTITY:
                    </td>
                    <td class="item-detail">
                        {{ $item->quantity }}
                    </td>
                </tr>
                <tr>
                    <td class="item-header">
                        DATES:
                    </td>
                    <td class="item-detail">
                        {{ f_datetime($item->start) }} to {{ f_datetime($item->end) }}
                    </td>
                </tr>
                <tr>
                    <td class="cell-header fs-12 text-dark">&nbsp;</td>
                    <td class="empty-cell">&nbsp;</td>
                </tr>
            </table>
        @endforeach
    @endforeach

    @if($itinerary->finances !== null)
        <table class="divider" style="page-break-before: always;">
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
    @endif

    @if(!empty($itinerary->notes))
        <div class="avoid-break">
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

    @if(!empty($itinerary->footer))
        <div class="avoid-break">
            <table class="divider">
                <thead>
                    <tr>
                        <th class="text">
                            FINAL DETAILS
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
