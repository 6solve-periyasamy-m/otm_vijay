@php
/**
 * @var \App\Repository\Storage\Itinerary\Itinerary $itinerary
 * @var string $type
 */
    $type = $type ?? "Travel Itinerary";
    $header_logo = svg_to_b64('images/pdf_assets/images/KPTravel_Logo_RGB_White.png') ;
    $event_image = svg_to_b64('images/pdf_assets/images/header_bg_1.png') ;
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
        :root { 
            --main-background-color:#F9F4EE;
            --text-head-color: rgba(243, 91, 21, 1);
            --text-color: #000;
            --table-header-text: #FFFFFF;
            --head-text-background:rgba(243, 91, 21, 1);
            --table-border-color:#EAEAEA;
        }

        .pdf-header { padding:30px 32px;position: relative; }
        .pdf-individual-block { width: 796px;position: relative; }
        .travel_itinerary_block{padding: 0px 30px 25px 30px;margin-top: -30px;}
        .header-logo{position: absolute; top: -180px;}
        .travel_title{
            color: #fff;
            font-family: "PlayfairDisplay-Medium";
            font-size: 58px;
            font-weight: 400;
            line-height: 64px;
            margin-top: -115px;
            text-align: center;
        }
        .travel_itinerary_title h3{
            color: var(--text-color);
            font-family: "PlayfairDisplay-Medium";
            font-size: 20px;
            font-weight: 500;
            line-height: 28px;
            margin-bottom: 0px;
        }
        .travel_itinerary_title h4{
            font-family: "PP Neue Montreal";
            font-size: 15px;
            font-weight: 400;
            line-height: 12px;
            color: #F35B15;
            text-underline-position: from-font;
            text-decoration-skip-ink: none;
        }
        .travel_itinerary_title h6,.event_txt{
            font-family: "PP Neue Montreal";
            font-size: 15px;
            font-weight: 400;
            line-height: 21px;
            color: #000;
        }
        .travel_itinerary_title h5{font-weight: normal;font-family: "PP Neue Montreal";}
        .travel_itinerary_title h5::after{
            content:"";
            margin-top: 20px;
            position: absolute;
            right: 11.5%;
            display: block;
            height: 4px;
            width: 80px;
            background-color: var(--head-text-background);
        }
        h2{
            font-family: 'Lato', sans-serif;
            font-size: 22px;
            font-weight: 700;
            line-height: 28px;
            padding: 3px 32px;
            color: var(--table-header-text);
            background-color: var(--head-text-background);
            margin: 0;
            text-transform: capitalize;
            text-align: center;
        }
        .heading-module {margin-top: 30px !important;}
        .heading-module h3{
            margin: 10px 0px 0px;
            font-family: "PPNeueMontreal-Medium";
            font-size: 20px;
            font-weight: 500;
            line-height: 24px;
            color: var(--text-color);
            text-transform: capitalize;
        }
        h3 span.mark {
            width: 5px;
            height: 30px;
            display: inline-block;
            margin-right: 22px;
            background-color: var(--head-text-background);
        }
        h3 span.text {
            position: relative;
            top: -10px;
        }
        .single-module {padding-left: 32px;}
        .pdf-individual-block .single-module .heading-module {margin-left: -32px;}
        .single-module h4{
            font-family: "PPNeueMontreal-Regular";
            font-size: 18px;
            font-weight: 500;
            line-height: 20px;
            color: var(--text-color);
            margin: 0px 0px 24px 0px;
            text-transform: capitalize;
        }
        h4 span.mark {
            width: 88px;
            height: 1px;
            display: block;
            margin: 0;
            margin-top: 5px;
            background-color: var(--head-text-background);
        }
        .single-module table td {
            font-family: "PPNeueMontreal-Regular";
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            color: var(--text-color);
            margin: 0px 0px 0px 0px;
            padding: 0px;
            vertical-align: top;
        }
        .event_info_div table td{
            font-family: "PPNeueMontreal-Regular";
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            color: var(--text-color);
            margin: 0px 0px 0px 0px;
            vertical-align: top;
        }
        .w-125{width :125px;}
        .item-detail.desc-pos-top{padding-right: 30px;}
        .pdf-individual-block .details-module tr td{padding-bottom: 7px !important;}
        .single-module table tr { margin: 0px 0px 5px 0px;}
        .item-detail { max-width: 100%;}
        .event_info_div{padding: 45px 35px;}
        .event_descrp{padding-bottom: 15px;}
        .text-wrap {
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }
    </style>
    <title>{{ $itinerary->package }} | {{ $itinerary->reference }} | {{ $type }}</title>
</head>

<body class="body">
    <section class="pdf-individual-block">
        <img src="{{ $itinerary->image }}" alt="{{ $itinerary->package }}" style="width:100%;margin-top: -10px;" >  
        <h1 class="travel_title"> {{ $type }} </h1>
        <div class="pdf-header" >
            <div class="header-logo">
                <img src="{{ $header_logo }}" alt="{{ $itinerary->brand->name }}">   
            </div>     
        </div>
        <div class="travel_itinerary_block">
            <div class="travel_itinerary_title">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="float:left;padding-bottom: 25px;"><h3>The British and Irish Lions Tour 2025</h3></td>
                            <td style="text-align:right;width: 50%;direction: rtl;padding-bottom: 25px;"><h5><strong>Reference:</strong> {{ $itinerary->reference }} </h5></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 10px;padding-bottom: 12px;"><h4>Guest Names</h4></td>
                        </tr>                        
                        <tr>
                            <td style="padding-left: 10px;padding-bottom: 6px;"><h6>{{ $itinerary->booker->customer->full_name }}</h6></td>
                        </tr>
                        @if(!empty($itinerary->booker->customer->mobile_number))
                        <tr>
                            <td style="padding-left: 10px;"><h6>{{ $itinerary->booker->customer->mobile_number }}</h6></td>
                        </tr>
                        @endif
                        @if(!empty($itinerary->booker->customer->email_address))
                        <tr>
                            <td style="padding-left: 10px;"><h6>{{ $itinerary->booker->customer->email_address }}</h6></td>
                        </tr>
                        @endif
                    </tbody>
                </table>        
            </div>            
        </div>

        <div class="heading-2">
            <h2>Trip Itinerary & inclusions</h2> 
        </div>

        @if(!empty($itinerary->items['Transfers']))
            @php $firstLoop = true; @endphp
            
            @foreach($itinerary->items['Transfers'] as $transport)
                @if(isset($transport->details['Quantity']) && $transport->details['Quantity'] > 0)
                <div class="single-module mb-n15">
                    @if($firstLoop)
                        <div class="heading-module">
                            <h3>
                                <span class="mark"></span>
                                <span class="text">Transport</span>
                            </h3>
                        </div>
                        @php $firstLoop = false; @endphp
                    @endif
                    <div class="details-module">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="item-header w-125"><strong> Service: </strong></td>
                                    <td class="item-detail">{{ $transport->name }}</td>
                                </tr>
                                @php
                                    $disable_items = ['Description', 'Transport', 'Travel Class'];
                                @endphp
                                @foreach($transport->details as $key => $value)
                                @php
                                    $class_desc_pos = $key == 'Description' ? 'desc-pos-top text-wrap' : '';
                                @endphp
                                    @if (!in_array($key, $disable_items))
                                        <tr>
                                            <td class="item-header w-125">
                                                <strong>{{ $key }}:</strong>
                                            </td>
                                            <td class="item-detail <?php echo $class_desc_pos;?>">
                                            {{ $value }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            @endforeach
        @endif  

        @if(!empty($itinerary->items['Accommodation']))
            @php $firstLoop = true; @endphp
            
            @foreach($itinerary->items['Accommodation'] as $accommodation)
                <div class="single-module mb-n15">
                    @if($firstLoop)
                        <div class="heading-module">
                            <h3>
                                <span class="mark"></span>
                                <span class="text">Accommodation</span>
                            </h3>
                        </div>
                        @php  $firstLoop = false; @endphp
                    @endif
                    <h4>
                        <span class="text">{{ $accommodation->name }}</span>
                        <span class="mark"></span>
                    </h4>
                    <div class="details-module">
                        <table>
                            <tbody>
                                @php
                                if (isset($accommodation->details['Description'], $accommodation->details['Quantity'])) {
                                    $temp_desc = $accommodation->details['Description'];
                                    unset($accommodation->details['Description']);
                                    $accommodation->details['Description'] = $temp_desc;
                                }
                                @endphp
                                @foreach($accommodation->details as $key => $value)
                                    @php  $class_desc_pos = $key == 'Description' ? 'desc-pos-top text-wrap' : '';  @endphp
                                    @continue(empty($value))
                                    <tr>
                                        <td class="item-header w-125">
                                            <strong>{{ $key }}:</strong>
                                        </td>
                                        <td class="item-detail <?php echo $class_desc_pos;?>">
                                            @if(is_array($value))
                                                @if(isset($value['attributes']['address_line_1']))
                                                    {{ $value['attributes']['address_line_1'] }}
                                                @else
                                                    Address not available
                                                @endif
                                            @else
                                                {!! $value !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif


        @if(!empty($itinerary->items['Event'])) 
            @php  $firstLoop = true; @endphp
            <div class="row">
                @foreach($itinerary->items['Event'] as $item)
                    <div class="single-module mb-n15">
                        @if($firstLoop)
                            <div class="heading-module">
                                <h3>
                                    <span class="mark"></span>
                                    <span class="text">Event</span>
                                </h3>
                            </div>
                            @php
                                $firstLoop = false;
                            @endphp
                        @endif
                        <div class="details-module">
                            <table>
                                <tbody>
                                    <tr>
                                    <td><strong>Event:</strong></td>
                                    <td>{{ $evename }}</td>
                                    </tr>
                                    @if(array_key_exists('Ticket', $item->details) && !empty($item->details['Ticket']))
                                        <tr>
                                            <td><strong>Ticket:</strong></td>
                                            <td>{{ $item->details['Ticket'] }}</td>
                                        </tr>
                                    @endif
                                    @if(!empty($item->details['Dates']))
                                        <tr>
                                            <td><strong>Dates:</strong></td>
                                            <td> <?php
                                                    $dates = explode('to', $item->details['Dates']); 
                                                    echo trim($dates[0]); 
                                                    ?>
                                            </td>
                                        </tr>
                                    @endif

                                    @if(!empty($item->details['Venue']))
                                        <tr>
                                            <td><strong>Venue:</strong></td>
                                            <td>{{ $item->details['Venue'] }}</td>
                                        </tr>
                                    @endif


                                    @if(!empty($item->details['Quantity']) && $item->details['Quantity'] > 0)
                                        <tr>
                                            <td><strong>Quantity:</strong></td>
                                            <td>{{ $item->details['Quantity'] }}</td>
                                        </tr>
                                    @endif

                                    @if(!empty($item->details['Description']))
                                        <tr>
                                            <td><strong>Description:</strong></td>
                                            <td class="text-wrap">{!! $item->details['Description'] !!}</td>
                                        </tr>
                                    @endif                 
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif        


        @if(!empty($itinerary->items['Inclusion']))
            @php  $firstLoop = true; @endphp
            @foreach($itinerary->items['Inclusion'] as $item)
                <div class="single-module mb-n15">
                    @if($firstLoop)
                        <div class="heading-module">
                            <h3>
                            <span class="mark"></span>
                            <span class="text">Additional Inclusions</span>
                            </h3>
                        </div>
                        @php $firstLoop = false; @endphp
                    @endif
                    <div class="details-module">
                        <table>
                            <tbody>
                                <tr>
                                    <td><strong>Inclusion:</strong></td>
                                    @if(array_key_exists('Ticket', $item->details))
                                        <td>{{ $item->details['Ticket'] }}</td>
                                    @else
                                        <td>{{ $item->name }}</td>
                                    @endif
                                </tr>
                                @php
                                    $disable_items = ['Ticket'];
                                @endphp
                                @foreach($item->details as $key => $value)
                                    @php $class_desc_pos = $key == 'Description' ? 'text-wrap' : ''; @endphp
                                    @continue(empty($value))
                                    @if (!in_array($key, $disable_items))
                                    <tr>
                                        <td><strong>{{ $key }}:</strong></td>
                                        <td class="<?php echo $class_desc_pos;?>">
                                            @if($key === 'Dates')
                                                {{ trim(explode('to', $value)[0]) }}
                                            @else
                                                {!! $value !!}
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif

        @if(!empty($itinerary->description))
            <div class="heading-2" style="margin-top: 60px;">
                <h2>Event information</h2> 
            </div>
            <div class="event_info_div">
                <p class="event_txt">{!! $itinerary->description !!}</p>
            </div>
        @endif

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
                                            <th>MADE ON</th>
                                            <th>AMOUNT PAID</th>
                                            <th>PAYMENT TYPE</th>
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
                                        {{ f_currency($itinerary->finances->commission) }} ({{ $itinerary->finances->commissionPercent }}%)
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
                                            <th>INSTALMENTS</th>
                                            <th>AMOUNT DUE</th>
                                            <th>DATE DUE</th>
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

            </div>
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


        @if(!empty($itinerary->event?->final_terms) || !empty($itinerary->terms))
            <div class="avoid-break">
                <table class="divider">
                    <thead>
                        <tr>
                            <th class="text">
                                TERMS & CONDITIONS
                            </th>
                        </tr>
                    </thead>
                </table>
                <div class="text-section">
                    <p>{!! $itinerary->event?->final_terms ?? $itinerary->terms !!}</p>
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
    </section>
</body>
</html>
