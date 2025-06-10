@php
/**
 * @var \App\Repository\Storage\Itinerary\Itinerary $itinerary
 * @var string $type
 */
    use Illuminate\Support\Facades\File;
    $type = setting('itinerary.heading') ?? 'Itinerary';
    $event_name = $itinerary->event;
    $headlogo = svg_to_b64($itinerary->brand->logo);
    $headlogo = svg_to_b64('images/pdf_assets/images/KeithProwse-Travel-Logo.png') ;
    $eveimg = $itinerary->image;
    $reference = $itinerary->reference;
    $all_customers = [];
    $travellers = collect($itinerary->travellers);
    $booker = collect([$itinerary->booker]);
    $all_customers = $booker->merge($travellers);
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
        :root { --main-background-color:#F9F4EE; --text-head-color: rgba(243, 91, 21, 1); --text-color: #000; --table-header-text: #FFFFFF; --head-text-background:rgba(243, 91, 21, 1); --table-border-color:#EAEAEA; }
        @page:first {margin-top: 0px;}
        @page { margin-top: 50px; margin-left: 0px; margin-right: 0px; margin-bottom: 0px; }
        
        .pdf-header {
            background-color: var(--main-background-color);
            padding:20px 10px;
        }
        .pdf-individual-block {
            width: 796px;
        }
        .header-logo {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .event-name h3{ padding-left:20px; color: var(--text-color); font-family: "PlayfairDisplay-Medium"; font-size: 24px; font-weight: 500; line-height: 16px; margin-bottom: 0px; text-align: left; }
     
        .travel_itinerary_block{margin-top: -30px;}
        /* .header-logo{width: 180px; height: 30px;position: absolute; top: -130px;}
        .header-logo img{width: 100%; height: 100%;} */
        .travel_title{ color: #fff; font-family: "PlayfairDisplay-Medium"; font-size: 40px; font-weight: 600; line-height: 38px; margin-top: -80px; text-align: center; } 
        .travel_itinerary_title h3{ color: var(--text-color); font-family: "PlayfairDisplay-Medium"; font-size: 24px; font-weight: 500; line-height: 18px; margin-bottom: 0px; text-align: left; }
        .travel_itinerary_title h4{ padding-top:10px; font-family: "PP Neue Montreal"; font-size: 15px; font-weight: 400; line-height: 12px; color: #F35B15; text-underline-position: from-font; text-decoration-skip-ink: none; text-align: left; }
        .travel_itinerary_title h6,.event_txt{ font-family: "PP Neue Montreal"; font-size: 15px; font-weight: 400; line-height: 21px; color: #000; }
        .travel_itinerary_title h5{font-weight: normal;font-family: "PP Neue Montreal";}
        h2{ font-family: 'Lato', sans-serif; font-size: 22px; font-weight: 700; line-height: 28px; padding: 3px 32px; color: var(--table-header-text); background-color: var(--head-text-background); margin: 0; text-transform: capitalize; text-align: center; }
        .heading-module {margin-top: 30px !important;}
        .heading-module h3{ margin: 10px 0px 0px; font-family: "PPNeueMontreal-Medium"; font-size: 20px; font-weight: 500; line-height: 24px; color: var(--text-color); text-transform: capitalize; }
        h3 span.mark { width: 5px; height: 30px; display: inline-block; margin-right: 22px; background-color: var(--head-text-background); } 
        h3 span.text { position: relative; top: -10px; }
        .single-module {padding-left: 32px;}
        .pdf-individual-block .single-module .heading-module { margin-left: -32px; }
        h4 span.mark { width: 88px; height: 1px; display: block; margin: 0; margin-top: 5px; background-color: var(--head-text-background); }
        .single-module h4{ font-family: "PPNeueMontreal-Regular"; font-size: 18px; font-weight: 500; line-height: 20px; color: var(--text-color); margin: 0px 0px 24px 0px; }
        .single-module table td { font-family: "PPNeueMontreal-Regular"; font-size: 14px; font-weight: 400; line-height: 20px; color: var(--text-color); margin: 0px 0px 0px 0px; padding: 0px; vertical-align: top; }
        .event_info_div table td, .onsite-details{font-family: "PPNeueMontreal-Regular";font-size: 14px;font-weight: 400;line-height: 20px;color: var(--text-color);margin: 0px 0px 0px 0px;vertical-align: top;}
        .w-125{width :135px;}
        .item-detail.desc-pos-top{padding-right: 30px;}
        .pdf-individual-block .details-module tr td{padding-bottom: 7px !important;}
        .single-module table tr { margin: 0px 0px 5px 0px;}
        .item-detail { max-width: 100%;}
        .event_info_div{padding: 10px 35px;}
        .event_descrp{padding-bottom: 15px;}
        .banner_header{ background-color: rgba(0, 0, 0, 0.6);display: inline-block;width: 796px; height: 147px;margin-top: -10px; }
        .text-wrap { word-wrap: break-word; word-break: break-word; white-space: normal; width:600px; }
        .travellers table { width: 100%;  border-collapse: collapse; }
        .travellers th, td {text-align: left;}
        .travellers th { background-color: #f4f4f4; }
        .traveller-name-space {width: 245px;padding-top: 5px;padding-bottom: 10px; font-family: "PPNeueMontreal-Regular";font-size: 14px;}
        .event-field-space {padding-top: 35px;padding-bottom: 50px;}
        .event_terms{ font-family: "PPNeueMontreal-Regular";font-size: 15px;font-weight: 400;line-height: 21px;padding-top:15px;}
        .text-full-wrap { word-wrap: break-word; word-break: break-word; white-space: normal; width:720px; line-height: 30px;}
        .text-full-wrap a {color: #3293ed; text-decoration: underline; }
        .event-profile {width: 100%; table-layout: fixed; padding-top:0px;}
        .event-profile td {padding: 8px 8px 8px 8px;}
        .event-profile td:first-child {text-align: left;padding-left: 0px;}
        .event-profile td:not(:first-child) {text-align: center;}
        .bg-line-color h5::before{content:"";margin-top: 20px; position: absolute; display: block; height: 4px; width: 80px; background-color: var(--head-text-background);}
        .event_terms table {margin-left: -45px;}
        .event_terms table td:first-child {width: 120px;}
        .component-body {width:"100%";}
        .component-body table th, .flight-block table th{ font-family: "PPNeueMontreal-Medium"; font-size: 14px; font-weight: 500; line-height: 20px; padding: 8px 0px; border: 1px solid gray;}
        .component-body table td, .flight-block table td { padding: 6.5px; text-align: center; border: 1px solid gray; }
        .non-booking-ref-block {line-height:20px;}
        .pn10 {padding:-10px;}
        .component-body table { page-break-inside: avoid; }
        .component-break { page-break-inside: avoid; }
        .word-wrap { word-wrap: break-word; word-break: break-word; white-space: normal; }
        .text-full-wrap table td {word-wrap: break-word; word-break: break-word; white-space: normal;}
        .onsite-details {font-size: 16px; font-weight: 600;}
        .tbl-bg-style{ background-color: #F9F4EE; padding: 0px 0px 0px 0px; position:relative;}
        .event_contact_info_div table tbody td {font-family: "PPNeueMontreal-Regular";font-size: 14px;font-weight: 400;line-height: 0px;color: var(--text-color);margin: 0px 0px 0px 0px;vertical-align: top;}
        .line-height {line-height: 15px;}
        .no-padding {padding: 0 !important;}


        .customer-details-block h6 {
            font-family: "PPNeueMontreal-Medium";
            font-size:14px;
            font-weight:500;
            line-height:16px;
            margin:0px;
            margin-bottom:8px;
            color: var(--text-head-color);
            text-transform:uppercase;
        } 
        .customer-details-block .customer-details-text-block,.customer-details-block .customer-details-image-block  {
            float:left;  
        }
        .customer-details-block .customer-details-image-block {
            width: 376px;
            height: 252px;
        }
        .customer-details-block .customer-details-text-block {    
            width: 388px;
            padding-left: 32px;
        }
        .customer-details-text-block  h3 span {
            background-color: var(--text-head-color);
            display: block;
            height: 3px;
            margin-top: 4px;
            width: 44px;
            margin-bottom: 19px;
        }
        .customer-details-image-block {display:inline-block;}
        .customer-details-image-block img {width:100%;height:100%;object-fit:contain;}
        .top-heading-section {
            width: 400px;
            position: relative;
            margin-top: 10px;
            display: block;
            margin-bottom: 10px;
            height: 44px;
        }
        .top-heading-section h1 {
            float: left;
            width: 255px;
        }
        .top-heading-section h5 {
            width: 194px;
            margin-bottom: 0px !important;
            margin-top: 10px;
            margin-left: auto;
            position: absolute;
            right: 0;
            top: 0px;
            font-family: "PPNeueMontreal-Medium";
            font-size: 12px;
            font-weight: 500;
            line-height: 14.4px;
            margin-bottom: 6px;
            color: var(--text-color);
        }
        .top-heading-section h5 span {
            margin-top: 5px;
            display: block;
            height: 3px;
            width: 44px;
            background-color: var(--head-text-background);
        }

        h1 {
            color: var(--text-color);
            font-family: "PlayfairDisplay-Medium";
            font-size: 30px;
            font-weight: 500;
            line-height: 36px;
            margin-bottom: 0px;
        }
        .customer-agent-details .customer-details {width:100%;}
        .customer-agent-details .agent-details {
            width:100%;    
            margin-top: 16px;
            margin-bottom: 16px;
        }
        .customer-agent-details p, .customer-agent-details div {
            font-family: "PPNeueMontreal-Medium";
            font-size: 14px;
            font-weight: 500;
            line-height: 18px;
            margin:0px;
            margin-bottom: 0px ! Important;
            color: var(--text-color);
        }
        .customer-agent-details div.phone_number_block {
            padding-left: 40px;
            font-family: "PPNeueMontreal-Regular";
            font-weight: 400;
            line-height: 21px;
        }
        
        .information-block {
            background-color: var(--main-background-color);
            clear: both;
            width: 100%;
            display: inline-block;
            padding-left:10px;
        }
        .information-block table {
            padding: 0px 0px 0px 10px;
        }
        .information-block table td {
            font-family: "PPNeueMontreal-Regular";
            font-size: 14px;
            font-weight: 400;
            line-height: 18px;
            color: var(--text-color);
            margin: 0;
            width:100px;
        }
        .information-block table td.tbl-td-no-text-wrap {width:300px;}
        .information-block table td strong {
            font-family: "PPNeueMontreal-Medium";
            font-weight: 500;
        }
        .customer-agent-details p span, .information-block .column .single p.description {
            font-family: "PPNeueMontreal-Regular";
            font-weight: 400;
        }
    </style>
    <title>{{ $itinerary->package }} | {{ $itinerary->reference }} | {{ $type }}</title>
</head>

<body class="body" style="margin: 0px;">
    <section class="pdf-individual-block">
        <div class="row">
            <div class="pdf-header">
                <table width="100%" style="width: 100%;">
                    <tr>
                        <td style="text-align: left; vertical-align: middle;">
                            <h3 style="padding-left: 20px; color: var(--text-color); font-family: 'PlayfairDisplay-Medium'; font-size: 24px; font-weight: 500; margin: 0;">
                                {{ $event_name }}
                            </h3>
                        </td>
                        <td style="text-align: right; vertical-align: middle;">
                            <img src="{{ $headlogo }}" alt="logo-ch" style="max-height: 60px;">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="customer-details-block">
                <div class="customer-details-text-block">            
                    <div class="top-heading-section">
                        <h1>{{ $type ?? "Quote" }}</h1>
                        <h5 style="margin-bottom:12px;">REFERENCE: {{ $reference }} <span></span></h5>         
                    </div>
                    <div class="customer-agent-details">
                        <div class="customer-details">
                            @if(!empty($itinerary->event->onsite_name) || !empty($itinerary->event->onsite_email) || !empty($itinerary->event->onsite_phone))
                                <h6>Onsite Details</h6>
                                @if(!empty($itinerary->event->onsite_name))
                                    <p>Name: <span>{{ $itinerary->event->onsite_name }}</span></p>
                                @endif
                                @if(!empty($itinerary->event->onsite_email))
                                    <p>Email: <span>{{ $itinerary->event->onsite_email }}</span></p>
                                @endif
                                @if(!empty($itinerary->event->onsite_phone))
                                    <p>Phone: <span>
                                        @php
                                            $phoneString = $itinerary->event->onsite_phone ?? '';
                                            $phones = preg_split('/\s*\|\s*|\s{2,}/', $phoneString, -1, PREG_SPLIT_NO_EMPTY);
                                        @endphp
                                        @foreach($phones as $phone)
                                        <div class="phone_number_block"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">{{ trim($phone) }}</a></div>
                                        @endforeach
                                    </span></p>
                                @endif
                            @endif                        
                        </div>
                    </div>  
                </div>
                <div class="customer-details-image-block">
                    <img src="{!! $eveimg !!}" alt="image-block">
                </div>       
            </div>
            <div class="information-block">
                @if($all_customers->isNotEmpty())
                <table style="width: 100%;">
                    <tbody>
                        <tr><td style="padding: 10px 0px 0px 10px; font-weight: normal !important; color: var(--text-head-color);"><p>Guest Names</p></td></tr>
                        <tr><td>
                            <!-- List of travellers -->
                            <table class="travellers" style="width: 100%;">
                                @php
                                    $limited_travellers = $all_customers->take(20);
                                    $chunks = $limited_travellers->chunk(4);
                                @endphp
                                @foreach($chunks as $chunk)
                                    <tr>
                                        @foreach($chunk as $traveller)
                                            @php
                                                $traveller_name = $traveller->customer->first_name . " " . $traveller->customer->last_name;
                                                if (strpos($traveller_name, 'Unknown') !== false) {
                                                    $traveller_name = 'TBC';
                                                }
                                            @endphp
                                            <td class="traveller-name-space">
                                                {{ $traveller_name }}
                                            </td>
                                        @endforeach

                                        @for($i = count($chunk); $i <= 4; $i++)
                                            <td></td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </table>
                            <!-- end of List of travellers -->
                        </td></tr>
                    </tbody>
                </table>
                @endif
            </div>
            <div class="heading-2">
                <h2>Itinerary & inclusions</h2>
            </div>
        </div>

        @if(!empty($itinerary->items['Flights']))
            @php $firstLoop = true; @endphp

            @foreach($itinerary->items['Flights'] as $flight)
                @if(isset($flight->details['Quantity']) && $flight->details['Quantity'] > 0)
                <div class="single-module mb-n15 component-break">
                    @if($firstLoop)
                        <div class="heading-module">
                            <h3>
                                <span class="mark"></span>
                                <span class="text">Flights</span>
                            </h3>
                        </div>
                        @php $firstLoop = false; @endphp
                    @endif
                    @if($flight->details['Flight Number'] !== $flight->details['Booking Reference'])
                        <div class="details-module">
                            <table>
                                <tbody>
                                    <tr><td colspan="2" class="pn10"></td></tr>
                                    <tr>
                                        <td class="w-125"><strong>Quantity:</strong></td>
                                        <td>{{ $flight->details['Quantity'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-125"><strong>Booking Reference:</strong></td>
                                        <td>{{ $flight->details['Booking Reference'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="non-booking-ref-block">&nbsp;</p>
                    @endif
                    <div class="component-body">
                        <table class="tbl-quote-section" style="width: 90%;">
                            <tr>
                                <th>Airline</th>
                                <th>Flight No.</th>
                                <th>Class</th>
                                <th>Date</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Departure</th>
                                <th>Arrival</th>
                            </tr>
                            <tr>
                                <td class="word-wrap" style="width:80px;">{{ $flight->name }}</td>
                                <td style="width:100px;">{{ $flight->details['Flight Number'] }}</td>
                                <td style="width:60px;">{{ $flight->details['Class'] }}</td>
                                <td style="width:70px;">{{ $flight->details['Departure Date'] }}</td>
                                <td class="word-wrap" style="width:100px;">{{ $flight->details['Departure Airport'] }}</td>
                                <td class="word-wrap" style="width:100px;">{{ $flight->details['Arrival Airport'] }}</td>
                                <td style="width:55px;">{{ $flight->details['Departure Time'] }}</td>
                                <td style="width:55px;">{{ $flight->details['Arrival Time'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
            @endforeach
        @endif

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
                                    $disable_items = [ 'Transport', 'Travel Class'];
                                @endphp
                                @foreach($transport->details as $key => $value)
                                @php
                                    $class_desc_pos = $key == 'Description' ? 'text-wrap' : '';
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
                                    @php  $class_desc_pos = $key == 'Description' ? 'text-wrap' : '';  @endphp
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
                                    <td class="w-125"><strong>Event:</strong></td>
                                    <td>{{ $event_name }}</td>
                                    </tr>
                                    @if(array_key_exists('Ticket', $item->details) && !empty($item->details['Ticket']))
                                        <tr>
                                            <td class="w-125"><strong>Ticket:</strong></td>
                                            <td>{{ $item->details['Ticket'] }}</td>
                                        </tr>
                                    @endif
                                    @if(!empty($item->details['Dates']))
                                        <tr>
                                            <td class="w-125"><strong>Dates:</strong></td>
                                            <td> <?php
                                                    $dates = explode('to', $item->details['Dates']); 
                                                    echo trim($dates[0]); 
                                                    ?>
                                            </td>
                                        </tr>
                                    @endif

                                    @if(!empty($item->details['Venue']))
                                        <tr>
                                            <td class="w-125"><strong>Venue:</strong></td>
                                            <td>{{ $item->details['Venue'] }}</td>
                                        </tr>
                                    @endif


                                    @if(!empty($item->details['Quantity']) && $item->details['Quantity'] > 0)
                                        <tr>
                                            <td class="w-125"><strong>Quantity:</strong></td>
                                            <td>{{ $item->details['Quantity'] }}</td>
                                        </tr>
                                    @endif

                                    @if(!empty($item->details['Description']))
                                        <tr>
                                            <td class="w-125"><strong>Description:</strong></td>
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
                                    <td class="w-125"><strong>Inclusion:</strong></td>
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
                                        <td class="w-125"><strong>{{ $key }}:</strong></td>
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
            <!-- <p class="event_txt">If you require any assistance during your trip, please don't hesitate to reach out to us. Our friendly team is always happy to help ensure your journey is smooth and stress free.</p> -->
            {{-- @if(!empty($itinerary->event->onsite_name) || !empty($itinerary->event->onsite_email) || !empty($itinerary->event->onsite_phone))
                <table class="event-profile">
                    <tbody>
                        <tr>
                            @if(!empty($itinerary->event->onsite_name))
                                <td class="event-field-space" style="width: 33.33%;">
                                <strong>Name:&nbsp;&nbsp;</strong><span>{{ $itinerary->event->onsite_name }}</span>
                                </td>
                            @endif
                            @if(!empty($itinerary->event->onsite_email))
                                <td class="event-field-space" style="width: 36.33%;">
                                <strong>Email:&nbsp;&nbsp;</strong><span>{{ $itinerary->event->onsite_email }}</span>
                                </td>
                            @endif
                            @if(!empty($itinerary->event->onsite_phone))
                                <td class="event-field-space" style="text-align: right; width: 30.33%;">
                                <strong>Phone:&nbsp;&nbsp;</strong><span>{{ $itinerary->event->onsite_phone }}</span>
                                </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            @endif --}}
            @if(!empty($itinerary->event?->final_terms) || !empty($itinerary->terms))
                <div class="event_terms text-full-wrap"><p >{!! $itinerary->event?->final_terms ?? $itinerary->terms !!}</p></div>
            @endif             
        </div>
    </section>
</body>
</html>
