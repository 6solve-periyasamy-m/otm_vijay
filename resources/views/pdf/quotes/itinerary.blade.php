@php
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\DB;
    use App\Repository\Storage\Itinerary\ItineraryScheduleType;
    /**
     * @var \App\Repository\Storage\Itinerary\Itinerary $itinerary
     * @var string $type
     */
    $type = $type ?? "Travel Itinerary";

    //pdf variables
    $headlogo = svg_to_b64('images/pdf_assets/images/KeithProwse-Travel-Logo.png') ;             
    $dacre = \Carbon\Carbon::parse($itinerary->booker->customer->created_at)->format('d M Y');                               
    $reference = $itinerary->reference;
    //$commission = $itinerary->organization->commission;
    //echo "Commission: " . $commission;
    $cusname = $itinerary->booker->customer->first_name .' '. $itinerary->booker->customer->last_name;
    $cusmail = $itinerary->booker->customer->email_address;

    $eveimg =$itinerary->image;
    $evename = $itinerary->event;
    $evatra = count($itinerary->travellers) + $itinerary->booker->travelling;

    //$travellersCount = is_array($itinerary->travellers) ? count($itinerary->travellers) : 0;

    //$travellingCount = $itinerary->booker && isset($itinerary->booker->travelling) ? $itinerary->booker->travelling : 0;

    //$evatra = $travellersCount + $travellingCount;

    if (!function_exists('generateFontFaceCSS')) {
      /**
       * Generate @font-face CSS for fonts if they exist.
       *
       * @param array $fonts Array of fonts with name and path.
       * @return string
       */
      function generateFontFaceCSS(array $fonts)
      {
          $css = '';
          foreach ($fonts as $font) {
              $fontPath = public_path($font['path']);
  
              if (File::exists($fontPath)) {
                  $fontBase64 = base64_encode(file_get_contents($fontPath));
                  $css .= <<<CSS
  @font-face {
      font-family: "{$font['name']}";
      src: url(data:font/ttf;base64,{$fontBase64}) format('truetype');
  }
  CSS;
              }
          }
  
          return $css;
      }
  }
  $fonts = [
    ['name' => 'PlayfairDisplay-Medium', 'path' => 'images/pdf_assets/fonts/PlayfairDisplay-Medium.ttf'],
    ['name' => 'PlayfairDisplay-Bold', 'path' => 'images/pdf_assets/fonts/PlayfairDisplay-Bold.ttf'],
    ['name' => 'PPNeueMontreal-Medium', 'path' => 'images/pdf_assets/fonts/PPNeueMontreal-Medium.ttf'],
    ['name' => 'PPNeueMontreal-Regular', 'path' => 'images/pdf_assets/fonts/PPNeueMontreal-Regular.ttf'],
];
//var_dump($itinerary);
    
@endphp
<?php 
  //var_dump(generateFontFaceCSS($fonts));
  //var_dump($itinerary->finances);
  //var_dump(setting('customization.documentation.colors'));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />
    <title>{{ $itinerary->package }} | {{ $itinerary->reference }} | {{ $type }}</title>

    <style type="text/css">
        <?php include(public_path() . '/css/kpt.css') ?>
        {!! setting('customization.documentation.colors') !!}
    </style>

<style>
  :root { 
    --main-background-color:#F9F4EE;
  --text-head-color: rgba(243, 91, 21, 1);
  --text-color: #000;
  --table-header-text: #FFFFFF;
  --head-text-background:rgba(243, 91, 21, 1);
  --table-border-color:#EAEAEA;
  }
  .pdf-header {
    background-color: var(--main-background-color);
    padding:30px 32px;
  }
  .pdf-individual-block {
    width: 796px;
    /* margin: auto; */
    /* height: 1000px; */
  }
  .pdf-individual-block {/*margin-left:-48px;*/}
  .pdf-individual-block:first-child {margin-top:-8px;}
  .pdf-individual-block .paragraph {
    max-width:716px!important;
  }
  /* .pdf-individual-block:first-child .heading-module h3 {
    margin: 0px 10px 0px 0px!important;
  }
  .pdf-individual-block:first-child .single-module {
    margin-top: 24px!important;
    margin-bottom: 24px!important;
  } */
  
    @font-face {
    font-family: "PlayfairDisplay-Medium";
    src: url('images/pdf_assets/fonts/PlayfairDisplay-Medium.ttf');
    }
    @font-face {
    font-family: "PlayfairDisplay-Bold";
    src: url('images/pdf_assets/fonts/PlayfairDisplay-Bold.ttf');
    }
    @font-face {
    font-family: "PPNeueMontreal-Medium";
    src: url('images/pdf_assets/fonts/PPNeueMontreal-Medium.ttf');
    }
    @font-face {
    font-family: "PPNeueMontreal-Regular";
    src: url('images/pdf_assets/fonts/PPNeueMontreal-Regular.ttf');
    }
  h1 { color: var(--text-color); font-family: "PlayfairDisplay-Medium"; font-size: 30px; font-weight: 500; line-height: 36px; margin-bottom: 0px; }
  .customer-details-block h6 { font-family: "PPNeueMontreal-Medium"; font-size:14px; font-weight:500; line-height:16px; margin:0px; margin-bottom:8px; color: var(--text-head-color); text-transform:uppercase; }
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
  h5 {
    font-family: "PPNeueMontreal-Medium";
    font-size: 12px;
    font-weight: 500;
    line-height: 14.4px;
    margin-bottom: 6px;
    color: var(--text-color);
 }
 /* .customer-agent-details .customer-details,.customer-agent-details .agent-details {float:left;} */
 .customer-agent-details .customer-details {width:100%;}
 .customer-agent-details .agent-details {
  width:100%;    
  margin-top: 16px;
  margin-bottom: 16px;
  }
 .customer-agent-details p {
  font-family: "PPNeueMontreal-Medium";
  font-size: 14px;
  font-weight: 500;
  line-height: 18px;
  margin:0px;
  margin-bottom: 0px ! Important;
  color: var(--text-color);
 }
.customer-details-text-block  h3 span {
  background-color: var(--text-head-color);
  display: block;
  height: 3px;
  margin-top: 4px;
  width: 44px;
  margin-bottom: 19px;
}
.information-block {
  background-color: var(--main-background-color);
  clear: both;
  width: 100%;
  display: inline-block;
  padding-left:12px;
}
.information-block .column {float:left;} 
.information-block .column{width:290px;}
.information-block .column {
  font-family: "PPNeueMontreal-Medium";
  font-size: 14px;
  font-weight: 500;
  line-height: 16px; 
  color: var(--text-color);
}
.information-block .column .single p{
  float:left;
  width:130px;
  font-family: "PPNeueMontreal-Medium";
  font-size: 14px;
  font-weight: 500;
  line-height: 16px; 
  color: var(--text-color);
  margin:0;
}
.information-block .column {   
  width: 260px;
  padding: 12.5px 13px;
}
.information-block .column:first-child {margin-right:15px;}
.information-block .column .single:first-child {margin-bottom:15px;}
.customer-details-image-block {display:inline-block;}
.customer-details-image-block img {width:100%;height:100%;object-fit:contain;}
h2 {
    color: var(--table-header-text);
    font-family: "PlayfairDisplay-Bold";
    font-size: 20px;
    font-weight: 700;
    line-height: 24px;
    padding: 3px 32px;
    background-color: var(--head-text-background);
    margin: 0;
    text-transform: uppercase;
    text-align: center;
}
h3 {
    margin: 30px 10px 0px 0px;
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
.customer-agent-details p span, .information-block .column .single p.description {
    font-family: "PPNeueMontreal-Regular";
    font-weight: 400;
  }
.single-module {
  margin-top: 30px;
  padding-left: 32px;
  margin-bottom:30px;
}
.single-module .details-module {
  max-width:716px;
}
h4 {
    font-family: "PPNeueMontreal-Regular";
    font-size: 18px;
    font-weight: 500;
    line-height: 20px;
    color: var(--text-color);
    margin: 0px 0px 24px 0px;
}
h4 span.mark {
  width:88px;
  height:1px;
  display:block;
  margin: 0;
  margin-top:5px;
  background-color: var(--head-text-background);
}
.single-module table tr {
  margin: 0px 0px 5px 0px;
}
.single-module table td {
    font-family: "PPNeueMontreal-Regular";
    font-size: 14px;
    font-weight: 400;
    line-height: 20px;
    color: var(--text-color);
    margin: 0px 0px 0px 0px;
    padding:0px;
    vertical-align:top;
}
.single-module table td strong {
    font-family: "PPNeueMontreal-Medium";
    font-weight: 500;
    margin: 0px 0px 0px 0px;
    margin-right: 6px;
    display: inline-block;
    min-width: 80px;
}
.single-module table td.item-detail strong  {
    position:relative;
    top:5px;
}
.text-wrap {
  word-wrap: break-word;
  word-break: break-word;
  white-space: normal;
}
.single-module table td.item-detail.desc-pos-top strong{
  top:5px;
}
.customer-agent-details {position:relative;}
/* .agent-details {
    margin-left: auto;
    position: absolute;
    right: 0;
    top: 0;
} */
.information-block table {
  padding: 15px 20px;
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
.information-block table tr td:nth-child(2) {padding-right: 30px;}

h5 span {
    margin-top: 5px;
    display: block;
    height: 3px;
    width: 44px;
    background-color:var(--head-text-background);
}
.custom-details-module {padding-left:32px;}
.custom-details-module  h6{
    font-family: "PPNeueMontreal-Medium";
    font-weight: 500;
    margin: 30px 0px 15px 0px;
    font-size: 14px;
    line-height: 18px;
}
.custom-details-module table td {
    font-family: "PPNeueMontreal-Regular";
    font-size: 14px;
    font-weight: 400;
    line-height: 18px;
    color: var(--text-color);
    margin: 0px 0px 0px 0px;
    min-width: 220px;
    padding:0;
}
.custom-details-module table td strong {
    font-family: "PPNeueMontreal-Medium";
    font-weight: 500;
}
.paragraph {
  margin: 25px 0px;
  padding-left: 32px;
}
.paragraph h5, .paragraph p, .paragraph h6, .paragraph ul li {
    font-size: 14px;
    font-weight: 400;
    line-height: 18px;
    color: var(--text-color);
}

.paragraph h5,.paragraph h6 {
    font-family: "PPNeueMontreal-Medium";
    font-weight: 500;
    margin: 0px 0px 15px 0px;
}
.paragraph h6 {
  margin: -10px 0px 10px 0px;
}
.paragraph p , .paragraph ul li {
    font-family: "PPNeueMontreal-Regular";
    font-weight: 400;
    margin: 0;
}
.paragraph ul {
    padding-left: 15px;
    margin: 8px 0px 0px 0px;
}
.single-module .heading-module{
  margin-bottom: 15px;
  margin-left: -32px;
}
.single-module h3 {
  margin-left:0px!important;
}
#static-pages h6 {margin-bottom:24px!important;}
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
}
.customer-agent-details {clear:both;}
.payment-detail table {
  width: 90%;
  max-width: 716px;
}
.payment-detail table th {
    font-family: "PPNeueMontreal-Medium";
    font-size: 18px;
    font-weight: 500;
    line-height: 20px;
    background-color: #F9F4EE;
    padding: 8px 0px;
    color: var(--head-text-background);
    border: 1px solid var(--table-border-color);
}
.payment-detail table td {
    padding: 6.5px;
    text-align: center;
    border: 1px solid var(--table-border-color);
}
.payment-detail {margin: 0px 0px;}
@page {
    margin-top: 25px;
    margin-bottom: 50px;
}
@page:first {
    margin-top: 0px;
}
figure.table {
     margin:-5px 0px -40px 0px;
}
/*figure.table tr td:nth-child(2) {display:none;} */
.mb-n15{
  margin-bottom:-15px;
}
.w-125{
  width: 125px;
}
.quote-payment-schedule tbody tr:last-child td {font-weight: bold;}
.quote-section tbody tr td:first-child {width: 125px;}
.component-body {width:"100%";}
.component-body table th, .flight-block table th{ font-family: "PPNeueMontreal-Medium"; font-size: 14px; font-weight: 500; line-height: 20px; padding: 8px 0px; border: 1px solid gray;}
.component-body table td, .flight-block table td { padding: 6.5px; text-align: center; border: 1px solid gray; }
.section-flight-info{font-family: "PPNeueMontreal-Regular";font-size: 14px; font-weight: 400;line-height: 18px; color: var(--text-color); margin: 0;}
.flight-block {padding-bottom: 50px;}
.pt-20 { padding-bottom: 45px; width:100%;}
.quote-section-tbl tbody tr td:first-child{width: 125px; }
.pb20 table {padding-bottom: 35px;}
.event-block table tr:last-of-type td:last-child{padding-bottom: 5px !important;}
.extra-space-bottom { padding-bottom:20px;}
.tc_information table, .tc_information p, .tc_information ul {  font-family: "PPNeueMontreal-Regular"; width: 90%; font-size: 14px; font-weight: 400; line-height: 20px;}
.tc_information p > strong {  display: inline-block;  padding-top: 0.9rem;}
.tc_information figure {margin-top: 20px;}
.tc_information table { width: 100%;}
.tc_information figure + p > strong {  display: inline-block;  margin-top: 3rem;}
</style>
</head>

<body class="body" style="margin: 0;">

<main>
<section class="pdf-individual-block">
  <div class="row">
      <div class="pdf-header">
         <div class="header-logo">
            <img src="{{ $headlogo }}" alt="logo-ch">
          </div>
      </div>
      <div class="customer-details-block">
        <div class="customer-details-text-block">            
          <div class="top-heading-section">
            <h1>{{ $type ?? "Quote" }}</h1>
            <h5 style="margin-bottom:12px;">REFERENCE: {{ $reference }} <span></span></h5>         
          </div>
            <div class="customer-agent-details">
                <div class="customer-details">
                <!-- the organization is shown as customer, with agent shown as name/email if set -->
                @if (!is_null($itinerary->organization))
                    <h6>CUSTOMER DETAILS</h6>
                    <p>Customer: <span>{{ $itinerary->organization->name }}</span></p>
                    <p>Name: <span>{{ $itinerary->agent ? $itinerary->agent->first_name . ' ' . $itinerary->agent->last_name : $itinerary->organization->name }}</span></p>
                    <p>Email: <span>{{ $itinerary->agent?->email ?? $itinerary->organization->contact_email }}</span></p>
                @elseif (!is_null($itinerary->organization) || !is_null($itinerary->agent))
                    <h6>CUSTOMER DETAILS</h6>
                    <p>Customer: <span>{{ $itinerary->agent->first_name . ' ' . $itinerary->agent->last_name }}</span></p>
                    <p>Email: <span>{{ $itinerary->agent->email ?? $itinerary->organization->contact_email }}</span></p>
                @else
                    <h6>CUSTOMER DETAILS</h6>
                    <p>Name: <span>{{ $cusname }}</span></p>
                    <p>Email: <span>{{ $cusmail }}</span></p>
                @endif
                </div>
                <div class="agent-details">
                    <h6>AGENT DETAILS</h6>
                    <p>Name: <span>{{$itinerary->consultant?->name}}</span></p>
                    <p>Email: <span>{{$itinerary->consultant?->email}}</span><p>
                    <p>{{ $type === 'Quote' ? "Quote" : "Order"}} Date: <span>{{ $itinerary->created->format('d M Y') }}</span><p>
                </div>
            </div>  
        </div>
        <div class="customer-details-image-block">
            <img src="{!! $eveimg !!}" alt="image-block">
        </div>       
    </div>

    
    <div class="information-block">
    <table>
        <tr>
            <td><strong>Event:</strong></td>
            <td class="tbl-td-no-text-wrap">{!! !empty($evename) ? $evename : '' !!}</td>
            <td><strong>No. of Guests:</strong></td>
            <td class="tbl-td-no-text-wrap">{{ !empty($evatra) ? $evatra : '0' }} Adult(s)</td> 
        </tr>
        <tr>
            <td><strong>Travel Dates:</strong></td>
            <td>
                {{ !empty($itinerary->start) ? date('d M Y', strtotime($itinerary->start)) : '' }} - 
                {{ !empty($itinerary->end) ? date('d M Y', strtotime($itinerary->end)) : '' }}
            </td>
            <td><strong>Lead Guest:</strong></td>
            <td class="tbl-td-no-text-wrap">{{ !empty($cusname) ? $cusname : '' }}</td>
        </tr>
    </table>
</div>

<div class="heading-2">
  <h2>Package inclusions</h2> 
</div>

  @php
    $sections = collect($itinerary->items['Sections'] ?? [])->filter(fn($item) => data_get($item->details, 'Type') === 'Flights');
    $flights = collect($itinerary->items['Flights'] ?? []);
    $section_flights = $sections->merge($flights)->sortBy(function ($item) {
      return $item->sortKey;
    });
  @endphp

  @if(!empty($section_flights))
      @php
        $firstLoop = true;
      @endphp
    @foreach($section_flights as $flights)
      @if(isset($flights->details['Quantity']) && $flights->details['Quantity'] > 0)
        <div class="single-module mb-n15 <?php echo $firstLoop?'':'add-on-cls'?>">
          @if($firstLoop)
              <div class="heading-module">
                  <h3   style="margin-top:10px;">
                      <span class="mark"></span>
                      <span class="text">Flights</span>
                  </h3>
              </div>
              @php
                  $firstLoop = false;
              @endphp
          @endif
            <div class="details-module">
              @if ($flights->type === 'Section')
                @foreach($flights->details as $key => $value)
                  <div class="section-body">
                  @if ($key === 'Body')
                    {!! $value !!}
                  @endif
                  </div>
                @endforeach
              @else
                <div class="component-body">
                  <table class="tbl-quote-section" style="width: 100%;">
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
                        <td class="text-wrap" style="width:80px;">{{ $flights->name }}</td>
                        <td class="text-wrap" style="width:100px;">{{ $flights->details['Flight Number'] }}</td>
                        <td class="text-wrap" style="width:60px;">{{ $flights->details['Class'] }}</td>
                        <td style="width:70px;">{{ $flights->details['Departure Date'] }}</td>
                        <td class="text-wrap" style="width:100px;">{{ $flights->details['Departure Airport'] }}</td>
                        <td class="text-wrap" style="width:100px;">{{ $flights->details['Arrival Airport'] }}</td>
                        <td style="width:55px;">{{ $flights->details['Departure Time'] }}</td>
                        <td style="width:55px;">{{ $flights->details['Arrival Time'] }}</td>
                      </tr>
                  </table>
                </div>
              @endif
            </div>
        </div>
      @endif
    @endforeach
  @endif

  @php
    $sections = collect($itinerary->items['Sections'] ?? [])->filter(fn($item) => data_get($item->details, 'Type') === 'Transport');
    $transfers = collect($itinerary->items['Transfers'] ?? []);
    $section_transfers = $sections->merge($transfers)->sortBy(function ($item) {
        return $item->sortKey;
    });
  @endphp

  @if(!empty($section_transfers))
      @php
        $firstLoop = true;
      @endphp
    @foreach($section_transfers as $transport)
      @if(isset($transport->details['Quantity']) && $transport->details['Quantity'] > 0)
        <div class="single-module mb-n15 <?php echo $firstLoop?'':'add-on-cls'?>">
          @if($firstLoop)
              <div class="heading-module">
                  <h3   style="margin-top:10px;">
                      <span class="mark"></span>
                      <span class="text">Transport</span>
                  </h3>
              </div>
              @php
                  $firstLoop = false;
              @endphp
          @endif
            <div class="details-module">
              @if ($transport->type === 'Section')
                @foreach($transport->details as $key => $value)
                  <div class="section-body">
                  @if ($key === 'Body')
                    {!! $value !!}
                  @endif
                  @if ($key === 'Quantity')
                    <table style="padding-top: 46px;">
                      <tbody>
                        <tr>
                          <td class="w-125 pt-10"><strong>Quantity:</strong></td>
                          <td>{{ $value }}</td>
                        </tr>
                      </tbody>
                    </table>
                  @endif
                  </div>
                @endforeach
              @else
                <table>
                    <tbody>
                      <tr>
                        <td class="item-header w-125">
                          <strong> Service: </strong>
                        </td>
                        <td class="item-detail">
                          {{ $transport->name }}
                        </td>
                      </tr>
                        @php
                          $disable_items = ['Description', 'Transport', 'Travel Class', 'Time'];
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
              @endif
            </div>
        </div>
      @endif
    @endforeach
  @endif


  @php
    $sections = collect($itinerary->items['Sections'] ?? [])->filter(fn($item) => data_get($item->details, 'Type') === 'Accommodation');
    $accommodation = collect($itinerary->items['Accommodation'] ?? []);
    $section_accommodation = $sections->merge($accommodation)->sortBy(function ($item) {
        return $item->sortKey;
    });
  @endphp

  @if(!empty($section_accommodation))
    @php
        $firstLoop = true;
    @endphp
    @foreach($section_accommodation as $accommodation)
      <div class="single-module mb-n15 <?php echo $firstLoop?'':'add-on-cls'?>">
        @if($firstLoop)
            <div class="heading-module">
                <h3 style="margin-top:10px;">
                    <span class="mark"></span>
                    <span class="text">Accommodation</span>
                </h3>
            </div>
            @php
                $firstLoop = false;
            @endphp
        @endif
          <h4>
              <span class="text">{{ $accommodation->name }}</span>
              <span class="mark"></span>
          </h4>
          <div class="details-module">
            @if ($accommodation->type === 'Section')
              @foreach($accommodation->details as $key => $value)
                <div class="section-body pb20">
                @if ($key === 'Body')
                  {!! $value !!}
                @endif
                </div>
              @endforeach
            @else
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
                      @php
                        $class_desc_pos = $key == 'Description' ? 'desc-pos-top text-wrap' : '';
                      @endphp
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
            @endif
          </div>
      </div>
    @endforeach
  @endif
  </div>


  @php
    $sections = collect($itinerary->items['Sections'] ?? [])->filter(fn($item) => data_get($item->details, 'Type') === 'Event');
    $events = collect($itinerary->items['Event'] ?? [])->filter(fn($item) => $item->type === 'Event');
    $section_events = $sections->merge($events)->sortBy(function ($item) {
        return $item->sortKey;
    });
  @endphp
  @if(!empty($section_events))
    @php
        $firstLoop = true;
    @endphp
    <div class="row">
        @foreach($section_events as $item)
          <div class="single-module <?php echo $firstLoop?'':'add-on-cls'?>" style="margin-bottom:0px;">
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
              <div class="details-module event-block">
                @if ($item->type === 'Section')
                  @foreach($item->details as $key => $value)
                    <div class="section-body">
                    @if ($key === 'Body')
                      <div class="extra-space-bottom">
                        {!! $value !!}
                      </div>
                    @endif
                    </div>
                  @endforeach
                @else
                  <table>
                    <tbody>
                        <tr>
                          <td class="w-125"><strong>Event:</strong></td>
                          <td>{{ $evename }}</td>
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
                @endif
              </div>
          </div>
        @endforeach
    </div>
  @endif

  @php
    $sections = collect($itinerary->items['Sections'] ?? [])->filter(fn($item) => data_get($item->details, 'Type') === 'Additional Inclusions');
    $inclusion = collect($itinerary->items['Inclusion'] ?? [])->filter(fn($item) => $item->type === 'Inclusions');
    $section_inclusion = $sections->merge($inclusion)->sortBy(function ($item) {
        return $item->sortKey;
    });
  @endphp


@if(!empty($section_inclusion))
  @php
    $firstLoop = true;
  @endphp
   <div class="row">
      @foreach($section_inclusion as $item)
      <div class="single-module <?php echo $firstLoop?'':'add-on-cls'?>">
      @if($firstLoop)
        <div class="heading-module">
            <h3>
              <span class="mark"></span>
              <span class="text">Additional Inclusions</span>
            </h3>
        </div>
        @php
            $firstLoop = false;
          @endphp
        @endif
         <div class="details-module">
          @if ($item->type === 'Section')
              @foreach($item->details as $key => $value)
                <div class="section-body">
                @if ($key === 'Body')
                  {!! $value !!}
                @endif
                </div>
              @endforeach
            @else
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
                        @php
                          $class_desc_pos = $key == 'Description' ? 'text-wrap' : '';
                        @endphp
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
            @endif
         </div>
      </div>
      @endforeach
   </div>
@endif

@php
    $sections = collect($itinerary->items['Sections'] ?? [])->filter(fn($item) => data_get($item->details, 'Type') === 'Merchandise');
    $merchandise = collect($itinerary->items['Inclusion'] ?? [])->filter(fn($item) => $item->type === 'Merchandise');
    $section_merchandise = $sections->merge($merchandise)->sortBy(function ($item) {
        return $item->sortKey;
    });
  @endphp

  @if(!empty($section_merchandise))
      @php
        $firstLoop = true;
      @endphp
    @foreach($section_merchandise as $merchandise)
      @if(isset($merchandise->details['Quantity']) && $merchandise->details['Quantity'] > 0)
        <div class="single-module mb-n15 <?php echo $firstLoop?'':'add-on-cls'?>">
          @if($firstLoop)
              <div class="heading-module">
                  <h3   style="margin-top:10px;">
                      <span class="mark"></span>
                      <span class="text">Merchandise</span>
                  </h3>
              </div>
              @php
                  $firstLoop = false;
              @endphp
          @endif
            <div class="details-module">
              @if ($merchandise->type === 'Section')
                @foreach($merchandise->details as $key => $value)
                  <div class="section-body">
                  @if ($key === 'Body')
                    {!! $value !!}
                  @endif
                  @if ($key === 'Quantity')
                    <table style="padding-top: 46px;">
                      <tbody>
                        <tr>
                          <td class="w-125 pt-10"><strong>Quantity:</strong></td>
                          <td>{{ $value }}</td>
                        </tr>
                      </tbody>
                    </table>
                  @endif
                  </div>
                @endforeach
              @else
                <table>
                    <tbody>
                      <tr>
                        <td class="item-header w-125">
                          <strong> Name: </strong>
                        </td>
                        <td class="item-detail">
                          {{ $merchandise->name }}
                        </td>
                      </tr>
                        @php
                          $disable_items = ['Description'];
                        @endphp
                        @foreach($merchandise->details as $key => $value)
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
              @endif
            </div>
        </div>
      @endif
    @endforeach
  @endif


@if(!empty($itinerary->finances))
    @php
      $currency = ($itinerary->finances->currency?->name) ? $itinerary->finances->currency : null;
    @endphp
<!-- <section class="pdf-individual-block"> -->
   <div class="row">
   
   
   <div class="single-module heading-2">
   <h2 style="margin-left:-32px;margin-top:30px;">Payment summary</h2>
   
   <div class="heading-module">
    <h3>
      <span class="mark"></span>
      <span class="text">Order total</span>
      </h3>
        </div>
    <div class="details-module" style="margin-top:-5px;">
        <table>
           <tbody>
                  <tr>            
                   <td style="font-weight:400;min-width:128px;">Booking Total:</td>
                   <td>{{ fr_currency($itinerary->finances->total + $itinerary->finances->commission, $currency, false, 0) }}</td>
                  </tr>
                  <tr>
                      <td style="font-weight:400;min-width:128px;">GST (included):</td>
                      <td>{{ $itinerary->finances->tax > 0 ? fr_currency($itinerary->finances->tax, $currency) : 'No Taxes Due' }}</td>
                  </tr>
                  @if($itinerary->finances->commission > 0 )
                  <tr>
                      <td style="font-weight:400;min-width:128px;">Commission:</td>
                      <td>{{ fr_currency($itinerary->finances->commission, $currency) }}</td>
                  </tr>
                  @endif      
                  <tr>
                      <td style="font-weight:400;min-width:128px;"><strong style="margin-top:15px">FINAL PRICE:
                      <span style="width: 100%;height: 1px;display: block;margin: 0;margin-top: 2px;background-color: var(--head-text-background);"></span>
                      </strong></td>
                      <td><strong style="margin-top:15px">{{ fr_currency($itinerary->finances->total, $currency, false, 0) }}</strong></td>
                  </tr>
                                    
           </tbody>
        </table>
    </div>
<div style="page-break-inside: avoid">
  <div class="heading-module" style="page-break-inside: avoid">
    <h3>
      <span class="mark"></span>
      <span class="text">Payment schedule</span>
    </h3>
  </div>
  <div class="payment-detail">
    @if($type === 'Reservation' )
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>INSTALMENT</th>
            <th>RECEIVED</th>
            <th>OUTSTANDING</th>
            <th>DATE DUE</th>
          </tr>
        </thead>
        <tbody>
          @php
            $balance_received = 0;
            $balance_received_total = 0;
          @endphp
          @foreach ($itinerary->finances->schedule as $key => $installment)
            @if($installment->type === ItineraryScheduleType::BOOKING_FEE)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ fr_currency($installment->amount, $currency) }}</td>
                <td>
                    {{ fr_currency(min($installment->amount, $installment->received), $currency) }}
                    @php $balance_received = $balance_received + min($installment->amount, $installment->received) @endphp
                </td>
                <td>
                  @if($installment->amount <= $installment->received)
                    Paid
                  @else
                      {{ fr_currency($installment->amount - min($installment->amount, $installment->received), $currency) }}
                  @endif
                </td>
                <td>With Order</td>
              </tr>
            @endif
            @if ($installment->type === ItineraryScheduleType::DEPOSIT)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ fr_currency($installment->amount, $currency) }}</td>
                <td>
                  @php $amount = $installment->amount - min(($installment->received - ($installment->balance ?? 0)), $installment->amount); @endphp
                  @if($amount <= 0)
                      {{ fr_currency($installment->amount, $currency) }}
                      @php $balance_received = $balance_received + $installment->amount @endphp
                  @else
                      {{ fr_currency($installment->received, $currency) }}
                      @php $balance_received = $balance_received + $installment->received @endphp
                  @endif
                </td>
                <td>
                  @if($amount <= 0)
                    Paid
                  @else
                      {{ fr_currency($amount, $currency) }}
                  @endif
                </td>
                <td>With Order</td>
            </tr>
            @endif
            @if ($installment->type === ItineraryScheduleType::INSTALLMENT)
              @php $amount = $installment->amount - $installment->received; @endphp
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ fr_currency($installment->amount, $currency) }}</td>
                <td>
                  @if($amount <= 0)
                      {{ fr_currency($installment->amount, $currency) }}
                      @php $balance_received = $balance_received + $installment->amount @endphp
                  @else
                      {{ fr_currency($installment->received, $currency) }}
                      @php $balance_received = $balance_received + $installment->received @endphp
                  @endif
                </td>
                <td>
                  @if($amount <= 0)
                      Paid
                  @else
                      {{ fr_currency($amount, $currency) }}
                  @endif
                </td>
                <td>
                  @if(!is_null(optional($installment->due)))
                      {{ optional($installment->due)->format('d M Y') }}
                  @endif
                </td>
              </tr>
            @endif
            @if ($installment->type === ItineraryScheduleType::REMAINING)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ fr_currency($installment->amount, $currency) }}</td>
                <td>
                  @php $balance_received_total = $installment->received - $balance_received; @endphp
                  {{ fr_currency($balance_received_total, $currency) }}
                </td>
                <td>
                  @php $amount = min($installment->balance, $installment->amount); @endphp
                  @if($amount <= 0)
                      Paid
                  @else
                      {{ fr_currency($amount, $currency) }}
                  @endif
                </td>
                <td>
                  @if(!is_null(optional($installment->due)))
                      {{ optional($installment->due)->format('d M Y') }}
                  @endif
                </td>
              </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    @else
      <table class="quote-payment-schedule">
        <thead>
          <tr>
            <th>INSTALMENT</th>
            <th>AMOUNT DUE</th>
            <th>DATE DUE</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($itinerary->finances->schedule as $installment)
          @continue(empty($installment->amount))
          <tr>
              <td>
                {{ ($installment->type === ItineraryScheduleType::INSTALLMENT) ? "Instalment" : ucfirst(strtolower($installment->type->name)) }}
              </td>
              <td>
                @if ($installment->type === ItineraryScheduleType::TOTAL)
                  {{ fr_currency($installment->amount, $currency) }}
                @else
                  {{ fr_currency($installment->amount, $itinerary->finances->currency) }}
                @endif
              </td>
              <td>
                @if ($installment->type === ItineraryScheduleType::DEPOSIT)
                  Now
                @else
                  @if($installment->paid)
                    Paid
                  @elseif(!is_null(optional($installment->due)))
                      {{ optional($installment->due)->format('d M Y') }}
                  @endif
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</div>
  </div>
   
   
   <div class="custom-details-module heading-2">  
   <h2 style="margin-left:-32px;">Payment Details</h2>    
       <h6>Keith Prowse Travel PTY LTD</h6>      
  <table>
    <tr>
        <td style="vertical-align: top;">
            <!-- <strong>BANK TRANSFER</strong><br> -->
            {!! $itinerary->payment_details !!}
        </td>
        <!-- <td style="vertical-align: top;">
            <strong>PAYMENT GATE</strong><br>
            Payment Gate: KPTVL
        </td> -->
    </tr>
  </table>   
   </div>
   </div>
<!-- </section> -->
@endif
</section>
<section id="static-pages" class="pdf-individual-block">
   <!-- <div class="row"> -->
     
   <!-- <h4 style="margin-bottom:0px;padding-left:32px;">   
    <span class="text">Notes</span>
    <span class="mark"></span>
   </h4> -->
   <div class="heading-module">
      <h3 style="margin-top:30px;">
          <span class="mark"></span>
          <span class="text">Notes</span>
      </h3>
    </div>
  <div class="custom-details-module" dat-bac="KPAD106309">      
     <h6 style="margin-top:10px;">{!! $itinerary->notes !!}</h6>      
  </div>

  <h2>Terms & conditions</h2>
  <div class="tc_information" style="margin-left:32px; margin-right:32px;">
    <h6 style="margin-top:0px;">{!! $itinerary->terms !!}</h6>
  </div>    
 </section>
</main>


</body>
</html>

