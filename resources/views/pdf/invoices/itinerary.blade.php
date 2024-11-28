@php
/**
 * @var \App\Repository\Storage\Itinerary\Itinerary $itinerary
 * @var string $type
 */
    $type = $type ?? "Travel Itinerary";
    $event_name = $itinerary->event;
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
        .header-logo{position: absolute; top: -130px;}
        .travel_title{
            color: #fff;
            font-family: "PlayfairDisplay-Medium";
            font-size: 40px;
            font-weight: 600;
            line-height: 20px;
            margin-top: -70px;
            text-align: center;
        }
        .travel_itinerary_title h3{
            color: var(--text-color);
            font-family: "PlayfairDisplay-Medium";
            font-size: 20px;
            font-weight: 500;
            line-height: 28px;
            margin-bottom: 0px;
            text-align: left;
        }
        .travel_itinerary_title h4{
            font-family: "PP Neue Montreal";
            font-size: 15px;
            font-weight: 400;
            line-height: 12px;
            color: #F35B15;
            text-underline-position: from-font;
            text-decoration-skip-ink: none;
            text-align: left;
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
        font-family: "PPNeueMontreal-Regular"text-align: left;;
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
        .banner_header{ background-color: rgba(0, 0, 0, 0.6);display: inline-block;width: 796px; height: 147px;margin-top: -10px; }
        .text-wrap {
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            width:600px;
        }
        .travellers table {
            width: 100%;
            border-collapse: collapse;
        }
        .travellers th, td {
            padding: 5px 10px 5px 5px;
            text-align: left;
        }
        .travellers th {
            background-color: #f4f4f4;
        }
        .traveller-name-space {width: 245px;padding-top: 5px;padding-bottom: 10px;}
        .event-field-space {width: 245px;padding-top: 35px;padding-bottom: 50px;}
    </style>
    <title>{{ $itinerary->package }} | {{ $itinerary->reference }} | {{ $type }}</title>
</head>

<body class="body">
    <section class="pdf-individual-block">
        @include('partials.pdf.kpt.header.new', ['type' => $type,])
        <div class="travel_itinerary_block">
            <div class="travel_itinerary_title">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="float:left;padding-bottom: 25px;"><h3>{{ $event_name }}</h3></td>
                            <td style="text-align:right;width: 50%;direction: rtl;padding-bottom: 25px;"><h5><strong>Reference:</strong> {{ $itinerary->reference }} </h5></td>
                        </tr>
                        @if(!empty($itinerary->travellers))
                        <tr>
                            <td colspan=2 style="padding-left: 10px;padding-bottom: 12px;"><h4>Guest Names</h4></td>
                        </tr>                        
                        <tr>
                            <td colspan=2 >
                                <!-- List of travellers -->
                                <table class="travellers">
                                    @php
                                        $limited_travellers = array_slice($itinerary->travellers, 0, 20);
                                        $total_travellers = count($limited_travellers);
                                    @endphp
                                    @foreach($limited_travellers as $key => $traveller)
                                        @if ($key % 5 == 0)
                                        <tr>
                                        @endif

                                            <td class="traveller-name-space">
                                                {{ $traveller->customer->first_name ?? '' }} {{ $traveller->customer->last_name ?? '' }}
                                            </td>

                                        @if (($key + 1) % 5 == 0 || $key + 1 == $total_travellers)
                                        </tr>
                                        @endif                                      
                                    @endforeach
                                </table>
                                <!-- end of List of travellers -->
                            </td>
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
                                    <td>{{ $event_name }}</td>
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

        <div class="heading-2" style="margin-top: 60px;">
            <h2>Event information</h2> 
        </div>
        <div class="event_info_div">
            <p class="event_txt">If you require any assistance during your trip, please don't hesitate to reach out to us our friendly team is always happy to help ensure your journey is smooth and stress free.</p>

            @if(!empty($itinerary->event->onsite_name) || !empty($itinerary->event->onsite_email) || !empty($itinerary->event->onsite_phone))
                <table>
                    <tbody>
                        <tr>
                            @if(!empty($itinerary->event->onsite_name))
                                <td class="event-field-space">
                                <strong>Name:</strong><span>{{ $itinerary->event->onsite_name }}</span>
                                </td>
                            @endif
                            @if(!empty($itinerary->event->onsite_email))
                                <td class="event-field-space">
                                <strong>Email:</strong><span>{{ $itinerary->event->onsite_email }}</span>
                                </td>
                            @endif
                            @if(!empty($itinerary->event->onsite_phone))
                                <td class="event-field-space">
                                <strong>Phone:</strong><span>{{ $itinerary->event->onsite_phone }}</span>
                                </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            @endif
            @if(!empty($itinerary->event?->final_terms) || !empty($itinerary->terms))
                <table>
                    <tbody>
                        <tr>
                            <td><p class="event_txt">{!! $itinerary->event?->final_terms ?? $itinerary->terms !!}</p></td>
                        </tr>
                    </tbody>
                </table>
            @endif             
        </div>
    </section>
</body>
</html>
