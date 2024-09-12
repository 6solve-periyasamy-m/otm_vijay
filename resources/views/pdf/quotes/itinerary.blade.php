@php
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\DB;
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
  //var_dump($itinerary);
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
  h1 {
    color: var(--text-color);
    font-family: "PlayfairDisplay-Medium";
    font-size: 40px;
    font-weight: 500;
    line-height: 42px;
    margin-bottom: 0px;
  }
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
    width: 372px;
    height: 247px;
 }
 .customer-details-block .customer-details-text-block {width: 392px;padding-left:32px;}
  h5 {
    font-family: "PPNeueMontreal-Medium";
    font-size: 16px;
    font-weight: 500;
    line-height: 18.4px;
    margin-bottom: 6px;
    color: var(--text-color);
 }
 /* .customer-agent-details .customer-details,.customer-agent-details .agent-details {float:left;} */
 .customer-agent-details .customer-details {width:100%;}
 .customer-agent-details .agent-details {
  width:100%;    
  margin-top: 20px;
  margin-bottom: 20px;
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
    text-transform: capitalize;
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
    line-height: 24px;
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
    width:190px;
}
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
}
.custom-details-module table td strong {
    font-family: "PPNeueMontreal-Medium";
    font-weight: 500;
}
.paragraph {
  margin: 30px 0px;
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
    width: 392px;
    position: relative;
    margin-top: 10px;
    display: block;
    margin-bottom: 16px;
    height: 44px;
}
.top-heading-section h1 {
    float: left;
    width: 255px;
}
.top-heading-section h5 {
    width: 255px;
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
}
@page:first {
    margin-top: 0px;
}
/* .single-module, .custom-details-module, .paragraph {
        page-break-inside: avoid;
    }  */
      /* #static-pages {page-break-inside: avoid;} */
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
                @if (!is_null($itinerary->organization) && !is_null($itinerary->organization->commission))
                    <h6>Organization DETAILS</h6>
                    <p>Name: <span>{{ $itinerary->organization->name }}</span></p>
                    <p>Email: <span>{{ $itinerary->organization->contact_email }}</span></p>
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
                    <p>Date created: <span>{{ $dacre }}</span><p>
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
            <td>{!! !empty($evename) ? $evename : '' !!}</td>
            <td><strong>No. of guests:</strong></td>
            <td>{{ !empty($evatra) ? $evatra : '0' }} Adult(s)</td> 
        </tr>
        <tr>
            <td><strong>Travel dates:</strong></td>
            <td>
                {{ !empty($itinerary->start) ? date('d M Y', strtotime($itinerary->start)) : '' }} - 
                {{ !empty($itinerary->end) ? date('d M Y', strtotime($itinerary->end)) : '' }}
            </td>
            <td><strong>Lead guest:</strong></td>
            <td>{{ !empty($cusname) ? $cusname : '' }}</td>
        </tr>
    </table>
</div>

<div class="heading-2">
	  <h2>Package inclusions</h2> 
  </div>
@if(!empty($itinerary->items['Accommodation']))
  @php
      $firstLoop = true;
  @endphp
    
  @foreach($itinerary->items['Accommodation'] as $accommodation)

    <div class="single-module"  style="margin-bottom:-15px;">
      @if($firstLoop)
          <div class="heading-module">
              <h3   style="margin-top:10px;">
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
            <table>
                <tbody>
                    @foreach($accommodation->details as $key => $value)
                        @continue(empty($value))
                        <tr>
                            <td class="item-header" style="width: 125px">
                                <strong>{{ $key }}:</strong>
                            </td>
                            <td class="item-detail">
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
  </div>
  <!-- </section> -->

  @if(!empty($itinerary->items['Event'])) 
    @php
        $firstLoop = true;
    @endphp
    <!-- <section class="pdf-individual-block"> -->
        <div class="row">
            @foreach($itinerary->items['Event'] as $item)
                <div class="single-module" style="margin-bottom:0px;">
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
                    <h4>   
                        <span class="text">{!! $item->name ?? $evename !!}</span>
                        <span class="mark"></span>
                    </h4> 
                    <div class="details-module">
                        <table>
                            <tbody>
                            @if(!empty($item->details['Ticket']))
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
                                        <td>{!! $item->details['Description'] !!}</td>
                                    </tr>
                                @endif                 
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    <!-- </section> -->
@endif

@if(!empty($itinerary->items['Inclusion']))
  @php
    $firstLoop = true;
  @endphp
<!-- <section class="pdf-individual-block"> -->
   <div class="row">
   
      @foreach($itinerary->items['Inclusion'] as $item)
      <div class="single-module">
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
         <!-- <h4>
            <span class="text">{{ $item->name }}</span>
            <span class="mark"></span>
         </h4> -->
         <div class="details-module">
            <table>
               <tbody>
                  <tr>
                     <td><strong>Inclusion:</strong></td>
                     <td>{{ $item->details['Ticket'] }}</td>
                  </tr>
                  <tr>
                     <td><strong>Dates:</strong></td>
                     <td>
                        <?php
                        $dates = explode('to', $item->details['Dates']); 
                        echo trim($dates[0]); 
                        ?>
                    </td>
                  </tr>
                  <tr>
                     <td><strong>Venue:</strong></td>
                     <td>{{ $item->details['Venue'] }}</td>
                  </tr>
                 
                  <tr>
                     <td><strong>Quantity:</strong></td>
                     <td>{{ $item->details['Quantity'] }}</td>
                  </tr>
                  <tr>
                     <td><strong>Description:</strong></td>
                     <td>{!! $item->details['Description'] !!}</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
      @endforeach
   </div>
<!-- </section> -->
@endif

@if(!empty($itinerary->finances))
<!-- <section class="pdf-individual-block"> -->
   <div class="row">
   
   
   <div class="single-module">
   <h2 style="margin-left:-32px;">Payment summary</h2> 
   
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
                   <td>{{ f_currency($itinerary->finances->total) }}</td>
                  </tr>
                  <tr>
                      <td style="font-weight:400;min-width:128px;">GST (included):</td>
                      <td>{{ $itinerary->finances->tax > 0 ? f_currency($itinerary->finances->tax) : 'No Taxes Due' }}</td>
                  </tr>
                  @if($itinerary->finances->commission > 0 )
                  <tr>
                      <td style="font-weight:400;min-width:128px;">Commission:</td>
                      <td>{{ f_currency($itinerary->finances->commission) }} ({{$itinerary->finances->commissionPercent}}%)</td>
                  </tr>
                  @endif
                  <tr>
                      <td style="font-weight:400;min-width:128px;">Final Cost:</td>
                      <td>{{ f_currency($itinerary->finances->cost) }}</td>
                  </tr>
                                    
           </tbody>
        </table>
    </div>
  <div class="heading-module">
    <h3>
      <span class="mark"></span>
      <span class="text">Payment schedule</span>
    </h3>
  </div>
  <div class="payment-detail">
  <table>
    <thead>
      <tr>
        <th>INSTALLMENTS</th>
        <th>AMOUNT DUE</th>
        <th>DATE DUE</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($itinerary->finances->schedule as $installment)
        <tr>
          <td>{{ ucfirst(strtolower($installment->type->name)) }}</td>
          <td>{{ f_currency($installment->amount) }} ({{ number_format($installment->percentage, 2) }}%)</td>
          <td>{{ optional($installment->due)->format('d M Y') ?? 'Now' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
  </div>
   
   
   <div class="custom-details-module">  
   <h2 style="margin-left:-32px;">Payment Details</h2>    
       <h6>Keith Prowse Travel PTY LTD</h6>      
  <table>
    <tr>
        <td style="vertical-align: top;">
            <strong>BANK TRANSFER</strong><br>
            {!! $itinerary->finances->paymentDetails !!}
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
  <div class="custom-details-module">     
     <h6 style="margin-top:10px;">KPAD106309</h6>      
  </div>
  <h2>Terms & conditions</h2>
  
    <div class="paragraph">
        <h5>BOOKING TERMS & CONDITIONS</h5>
        <p>These Booking Conditions set out the terms on which you contract with us for the arrangement and delivery of travel arrangements. 
        By making a booking with us, you acknowledge that you have read, understood and agree to be bound by these Booking Conditions. 
        We reserve the right to change these Booking Conditions at any time prior to you making a booking request. 
        “You” and “Your” means all persons named in a booking (including anyone who is added or substituted at a later date). 
        “We”, “us”, “our” and “Keith Prowse Travel” means Keith Prowse Travel Pty Limited (ACN 003 276 775).
       </p>
    </div>
    <div class="paragraph">
        <h5>BOOKINGS</h5>
        <p>A booking request is accepted when we issue a written booking invoice/reservation and you have paid your deposit. It is at this point that a contract between us and 
          you come into existence subject to these Booking Conditions. We reserve the right to decline any booking at our discretion. No employee of ours other than a 
          director has the authority to vary or omit any of these Booking Conditions or to promise any discount or refund.
       </p>
    </div>
    <div class="paragraph">
        <h5>SERVICES</h5>
        <p>We commence providing services to you as soon as we accept your booking. This includes (often significant) work undertaken prior to travel to arrange and coordinate the delivery of 
        your travel arrangements. You also receive the benefit of work we undertake in anticipation of bookings. The services we provide to you are limited to (a) 
        the arrangement and coordination of your travel arrangements; and (b) the delivery of any travel arrangements that we directly control.
              </p>
    </div>
    <div class="paragraph">
        <h5>PRICES & EXCLUSIONS</h5>
        <p>Unless otherwise stated prices are in Australian Dollars ($AUD) and are current at the time of publication. The most up to date pricing is
    available on our website. The price includes those services as per the published itinerary. If prices reduce or are discounted after you have placed your booking request, 
    you will not be entitled to the reduced or discounted rate. International and domestic airfares and airport/hotel transfers are not included unless specifically stated. 
    Costs associated with passports, visas, vaccinations, insurance, meals (other than those stipulated), emergency evacuation costs, gratuities, quarantine expenses and all 
    items of a personal nature are not included.
        </p>
    </div>
    <div class="paragraph">
        <h5>PRICE VARIATIONS</h5>
        <p>We reserve the right to vary the cost of your travel arrangements prior to commencement for circumstances beyond our control such as the imposition of fuel surcharges or new or amended Government charges.
We also reserve the right to vary the cost of your travel arrangements due to currency fluctuations. However, we will not vary the cost for
currency fluctuations once full payment has been received by us and we will absorb the first 2% of any negative currency fluctuation.
        </p>
    </div>
    <div class="paragraph">
        <h5>PRICE VARIATIONS</h5>
        <h6>Deposit</h6>
        <p>A 50% deposit per person or $500 (whichever is the greater), is required within 7 days of us issuing your booking invoice/reservation unless otherwise stated – for example some 
arrangements may require full payment upon the issuing of your booking invoice/reservation to secure services. Please note that we may not hold any services for you until we 
receive payment of your deposit, meaning that services may become unavailable or prices may increase, in which case you will be responsible for paying the increased price, 
and we will not be responsible if services become unavailable.
        </p>
    </div>
    

  <!-- </div> -->
<!-- </section> -->

<!-- <section class="pdf-individual-block"> -->
   <!-- <div class="row"> -->
    <div class="paragraph">
          <h5>Instalment & Balance Payments</h5>
          <p>Instalment and balance payments must be received in full by us by the date(s) specified on your invoice/reservation. If you fail to make
          payment by the due date, we will remind you to make payment. In addition to the payment, you will also be responsible for any costs imposed on us by suppliers resulting from late payment. 
          If we do not receive payment within 7 days after the reminder, you will be deemed to have cancelled your booking, and cancellation charges as set out in these Booking Conditions will apply 
          as if you had given written notice of cancellation.
        </p>
    </div>
    <div class="paragraph">
          <h5>CANCELLATIONS BY YOU</h5>
          <p>You may cancel your booking by giving written notice to us. Cancellation fees and charges will be levied as follows:</p>
          <ul>
           <li>any amounts we have paid or have contractually committed to pay to third parties to deliver your travel arrangements which we cannot reasonably recover or which the third party continues to oblige us to pay (for example payments made or due to airlines or ground operators);</li>
           <li>where we or our related companies directly control any of the services included in your travel arrangements (for example, accommodation, vessels, transportation, guides), a reasonable amount attributable to such services which we reasonably determine we cannot resell or recoup;</li>
           <li>a fee equal to 20% of the booking value to compensate us for work performed and associated overheads up until the time of cancellation (including work performed in connection with your travel arrangements prior to your booking) and our loss of expected profit; and</li>
           <li>a fee of $400 to compensate us for processing the cancellation and any associated refund.</li>
          </ul>
    </div>
    <div class="paragraph">
          <h5></h5>
          <p>Cancellation fees and charges will not exceed payments received by us at the time of cancellation. If after the application of these fees and charges there is a surplus of payments you have made to us, we will refund this to you within a reasonable time.
            Any payments we have made to third parties will only be refunded to you once we have deducted the above cancellation fees and charges and once we have actually recovered the amounts from the third parties. As we will be subject to third party terms and conditions, we make no guarantee that we will be able to make recoveries or that third parties will agree that payments attributable to your booking arrangements are no longer required.
            For group departures, a transfer of a confirmed booking to another departure date is deemed to be cancellation of the original booking.
            For escorted group departures, a transfer of a confirmed booking to another departure date is deemed to be cancellation of the original
            booking.
        </p>
    </div>
    <div class="paragraph">
        <h5>ILLNESS DISRUPTING TRAVEL & QUARANTINE</h5>
        <p>If due to any illness, suspected illness or failure to satisfy any required tests (such as a temperature test in relation to Covid-19) you are
prevented from commencing or continuing travel arrangements booked through us, then:
        </p>
        <ul>
            <li>if you have already commenced your travel arrangements, we will provide you with reasonable assistance to arrange alternative travel arrangements. This will be at your cost.</li>
            <li>if you have not commenced your travel arrangements then we regret we will not be in a position to provide such assistance.</li>
        </ul>      
    </div>
    <div class="paragraph">
          <h5></h5>
          <p>We will not be liable to refund the cost of travel arrangements (or any part of them) because we would have already paid (or committed to
pay) suppliers and we would have already performed significant work servicing your booking.
We will not be responsible to you for any loss or expenses incurred in connection with your booking (for example, airfares and visa expenses) if you are prevented from commencing or continuing travel arrangements in these circumstances.
If you are required to quarantine in destination or upon your return to Australia, then you agree that you will be responsible for all costs
associated with quarantine requirements.
We strongly encourage you to purchase travel insurance that adequately responds to cancellations and curtailments associated with illness and other unforeseen events as soon as you have paid your deposit.
        </p>
    </div>
    <div class="paragraph">
          <h5>OTHER CANCELLATIONS</h5>
          <p>In these Booking Conditions, the term Force Majeure means an event or events beyond our control and which we could not have reasonably prevented, and includes but is not limited to: (a) natural disasters (including not limited to flooding, fire, earthquake, landslide, volcanic eruption), adverse weather conditions (including hurricane or cyclone), high or low water levels; (b) war, armed conflict, industrial dispute, civil strife, terrorist activity or the threat of such acts; (c) epidemic, pandemic; (d) any new or change in law, order, decree, rule or regulation of any government authority (the events in (d) being “Government Restrictions”)).
          Force Majeure - Prior to travel
           </p>
    </div>
   <!-- </div> -->
<!-- </section> -->
<!-- <section class="pdf-individual-block"> -->
   <!-- <div class="row">  -->
   <div class="paragraph">
          <h5>If:</h5>
          <ul>
           <li>in our reasonable opinion we (either directly or through our employees, contractors, suppliers or agents) determine that your travel arrangements cannot safely, 
           lawfully or reasonably proceed due to a Force Majeure event; or</li>
           <li>you give us notice no more than 14 days prior to commencement of your booked travel arrangements that 
           you cannot reasonably make use of them due to Government Restrictions (for example due to border closures)</li>
          </ul>
    </div>
    <div class="paragraph">
          <h5>then we may:</h5>
          <ul>
           <li>reschedule your travel arrangements, but only if you are agreeable to the rescheduled arrangements; or</li>
           <li>cancel your travel arrangements, in which case our contract with you will terminate.</li>
          </ul>
          
    </div>
    <div class="paragraph">
          <p>If we cancel your travel arrangements, neither of us will have any claim for damages against the other for the cancelled arrangements.</p>
          <h5>However, we will either:</h5>
          <ul>
           <li>issue you with a credit equal to payments received by us for the cancelled travel arrangements, redeemable within 12 months of issue against any travel services offered by us; or</li>
           <li>refund payments attributable to the cancelled travel arrangements less: (a) unrecoverable third party costs and other expenses incurred or payable by us for the cancelled travel arrangements; (b) overhead charges incurred by us relative to the price of the cancelled travel arrangements; and (c) fair compensation for work undertaken by us in relation to the cancelled travel arrangements until the time of cancellation and in connection with the processing of any refund.</li>
          </ul>
          
    </div>
    <div class="paragraph">
          <h5></h5>
          <p>Please note that our ability to issue you with a credit may be dependent on our suppliers issuing corresponding credits to us. We cannot guarantee that our suppliers will issue corresponding credits and so in such circumstances we may opt to pay you a limited cash refund as outlined.
          </p>
    </div>
    <div class="paragraph">
          <h5>Force Majeure - During travel</h5>
          <p>If due to Force Majeure we cancel travel arrangements after your trip has commenced, we will provide you with a refund of recovered third party costs plus any third party costs we don’t incur for cancelled travel arrangements only.</p>
    </div>
    <div class="paragraph">
          <h5>Force Majeure – General</h5>
          <p> 
          Where a limited cash refund is to be paid by us, we will use reasonable endeavours to recover payments from third parties attributable to your booking, but we make no guarantee that we will be able to recover these payments either partially or at all. If after we have paid you a limited cash refund (or after we determine that no cash refund is currently payable) we recover payments from third parties attributable to your booking, then we will pass on this payment to you.
          </p>
          <p> 
          We will not be responsible for any other loss or costs you incur in connection with your booking (for example, airfares, insurance and visa expenses) if your trip or particular travel arrangements are cancelled due to an event of Force Majeure.
          </p>
          <p> 
          If we provide you with any alternative services or assistance where travel arrangements are cancelled or rescheduled due to Force Majeure which you accept, then you agree the amount to be refunded to you will be reduced by the value of these services and assistance. You acknowledge that the terms in this section are reasonably necessary to protect our legitimate business interests. We strongly encourage you to purchase travel insurance that adequately responds to cancellation and rescheduling risks associated with Force Majeure events as soon as you have paid a deposit.
          </p>
    </div>
    <div class="paragraph">
          <h5>Other cancellations</h5>
          <p> 
          If we cancel your travel arrangements for reasons other than Force Majeure or a failure to satisfy minimum numbers, you will be offered (at your election) a refund of all funds paid, or the offer of travel arrangements of substantially equal quality if appropriate. To the fullest extent permitted by law, we will not be responsible to you for any other expenses or loss you incur resulting from our cancellation.
          </p>
    </div>
    <div class="paragraph">
          <h5>AMENDMENTS BY YOU</h5>
          <p> 
          We will endeavour to accommodate amendments and additional requests. You acknowledge that these may not be possible to fulfil, and for group departures a transfer of a booking to a different departure is deemed a cancellation. An amendment fee of $150 will be levied to cover communication and administration costs for any changes to bookings. You will also be required to pay any additional costs we incur associated with the amendments.
          </p>
    </div>
    <!-- </div> -->
<!-- </section> -->
<!-- <section class="pdf-individual-block"> -->
   <!-- <div class="row">  -->
   <div class="paragraph">
          <h5>AMENDMENTS BY US</h5>
          <h6>Prior to travel</h6>
          <p> 
          Due to the dynamic nature of the travel industry, we may occasionally need to make amendments or modifications to the itinerary and its
          inclusions and you acknowledge our right to do this. Most changes will not be significant. If we become aware of any significant changes to your itinerary or its inclusions that materially detract from the overall value of the trip (where we determine it can still proceed), then we will notify you within a reasonable time and refund you an amount attributable to any reduction in value determined by us acting reasonably.
          </p>
    </div>
    <div class="paragraph">
          <h5>During travel</h5>
          <p> 
          You acknowledge that the itinerary, modes of transport, accommodation and/or the trip’s inclusions may need to change during your trip due to local circumstances beyond our reasonable control, including road conditions, poor weather, changes in transport schedules, and/or vehicle breakdowns.
          </p>
    </div>
    <div class="paragraph">
          <h5>General</h5>
          <p> 
          To the fullest extent permitted by law:
          </p>
          <ul>
            <li>we will not be responsible for any omissions or modifications to the itinerary or the inclusions due to Force Majeure or other circumstances beyond our control happening after we have accepted your booking. This includes any loss of enjoyment or distress caused by omissions or modifications;</li>
            <li>if you are entitled to any compensation for any modifications or omissions, then you agree it will be reduced by the value of any alternative services we provide which you accept; and we will not be responsible to you for any other expenses or loss you incur resulting from any amendment or change to the itinerary or its inclusions.</li>
          </ul>
    </div>
    <div class="paragraph">
          <h5>EVENTS & TICKETS</h5>
          <h6>Events</h6>
          <p> 
          Your travel arrangements may be associated with a particular event, for example a sporting event (Event). We do not operate any Event. You acknowledge that we have no control over the Event and will not be liable for any representations, actions, omissions of the Event organisers. You acknowledge that the cancellation or postponement of the Event, or the withdrawal or disqualification of any team or individual (Event Change) will not in any circumstance be considered a cancellation of travel arrangements by Keith Prowse Travel. We will however use reasonable endeavours to obtain a refund of the cost we incurred to obtain any ticket(s) to the Event for you. We will promptly make a corresponding refund to you once received. If you choose to cancel your booking due to an Event Change, then cancellation fees and charges will be applied in accordance with the ‘Cancellation by You’ condition above.
          </p>
          <p>We give no warranties (and expressly disclaim any warranty) that the Event will take place at a particular time, at a particular place, in a
          particular format, to a particular standard or at all.</p>
    </div>
    <div class="paragraph">
          <h5>Tickets</h5>
          <p>Keith Prowse Travel does not issue any tickets. Tickets are issued by or for Event organisers. You acknowledge that all tickets are subject to the terms and conditions and limitations of liability imposed by the Event organiser and associated venue operators. </p>
          <p>Tickets are non-transferable and cannot be exchanged. If you lose your ticket (or if it is stolen), we will use reasonable endeavours to assist you to obtain a replacement ticket from the Event organiser or other supplier. Additional charges may be payable. We make no warranty that the ticket will be able to be replaced, and we are in no way responsible if it cannot be replaced.</p>
          <p>Venue maps are given as a guide only and are issued by venues or Event organisers. We will use reasonable endeavours to meet special requests, but we do not promise that we will be able to meet that request, or that tickets will be located in a particular area or sat together. You must not advertise any ticket for resale. If you do so, you acknowledge that the ticket may be cancelled by the Event organiser. If there are any issues with your ticket and it does not permit entry to the Event for whatever reason, then you agree that our maximum liability is to refund you the cost we incurred to secure the ticket</p>
          <p>Arrangements which include tickets must not be used for advertising or promotional purposes or trade incentives unless Keith Prowse Travel authorises in writing. Authorisation may be denied in our absolute discretion.</p>
          <p>Tickets for Events within Australia are generally sent by post or by email as an e-ticket to your nominated address approximately 1-2 weeks prior to the Event. For international Events, tickets may be delivered to your hotel or may be sent as an e-ticket to your nominated address. Please ensure you have access to your email address while travelling.</p>
    </div>
   <!-- </div> -->
<!-- </section> -->
<!-- <section class="pdf-individual-block"> -->
   <!-- <div class="row">  -->
   <div class="paragraph">
      <h5>PROMOTIONAL MATERIAL</h5>
      <p>We endeavour to ensure the accuracy of the information contained in our promotional material. However, please note that imagery used
      within promotional material is reflective of the general experience, may not reflect the actual experience and may be derived from past trips operated by us. 
      Without limitation, imagery contained within promotional material does not mean that a particular speaker or host will be present during your trip. 
      You should refer to the inclusions for the trip you are booking to determine the included travel arrangements and other services for that trip.</p>
     </div>
    <div class="paragraph">
       <h5>ACCOMMODATION</h5>
       <p>Due to the dynamic nature of the travel industry, we may need to substitute hotels, vessels and other forms of accommodation with properties or vessels of a substantially comparable or higher standard. We will endeavour to minimise substitutions. You acknowledge that these substitutions will not be considered a significant change.</p>
    </div>
    <div class="paragraph">
       <h5>FLIGHTS</h5>
       <p>A number of domestic and international flights may be included in your arrangements. All airfares are subject to the terms and conditions of the airfare purchased.
       It is your responsibility to contact the airline prior to departure as airlines have the right to reschedule or cancel flights. For significant delays, it is your responsibility to notify the third parties including but not limited to the transfer company, accommodation, tour company and Event organisers as no-shows or significant delays can result in involuntary cancellation. It is prudent to arrive to any significant Event a day ahead, especially if travelling internationally.</p>
    </div>
    <div class="paragraph">
       <h5>CLIENT NAMES – EXACTLY AS PER PASSPORT</h5>
       <p>For security reasons, airlines and our overseas suppliers require names to be given exactly as stated in your passport. If you do not advise the correct information and we have to re-issue airline tickets or other documentation, then you will be responsible for any fees charged (such as airline cancellation charges or re-issue fees) in addition to our own reasonable administration fees.</p>
    </div>
    <div class="paragraph">
       <h5>TRAVEL INSURANCE</h5>
       <p>It is a condition of your booking that you are adequately insured for the duration of your travel, including in respect of cancellations due to
       Force Majeure events. We recommend comprehensive travel insurance to cover cancellation, medical requirements, luggage and additional expenses. The choice of insurer is yours. We strongly suggest you purchase insurance at the time you pay your deposit. This is because cancellation fees and charges are payable from that time.</p>
    </div>
    <div class="paragraph">
       <h5>PASSPORTS, VISAS & VACCINATIONS</h5>
       <p>It is a requirement that you hold a valid passport with sufficient validity and any required visas for your travel. It is your responsibility to ensure that you are in possession of the necessary documentation to comply with the laws and regulations of the countries to be visited. It is your responsibility to obtain vaccinations and preventative medicines as may be required for the duration of your travel. Any information provided by us is given in good faith.</p>
    </div>
    <div class="paragraph">
       <h5>HEALTH & FITNESS</h5>
       <p>It is your responsibility to ensure that you have a suitable level of health and fitness to undertake the trip of your choice. If you have doubts
       about your ability to undertake the trip, please contact us to discuss your circumstances prior to making a booking request.</p>
    </div>
    <div class="paragraph">
       <h5>Existing Medical Conditions</h5>
       <p>If you have a medical condition which may reasonably be expected to increase your risk of needing medical attention, or which may materially affect the usual conduct of the trip, then you must advise us prior to or at the time you make your booking request.</p>
       <p>We may request you to provide an assessment of your medical condition from a qualified medical practitioner. If the assessment indicates that you are not fit to travel or will require special assistance which we cannot reasonably provide, then we may cancel your booking. Provided you notified us of your medical condition prior to or at the time you made your booking request, we will provide you with a full refund of payments received.</p>
       <p>If you fail to provide a medical assessment within a reasonable time, then this will be considered a cancellation by you and cancellation fees and charges will apply (See ‘Cancellation By You’ section above).</p>
    </div>
 

   <!-- </div> -->
<!-- </section> -->

<!-- <section class="pdf-individual-block"> -->
   <!-- <div class="row">  -->
   <div class="paragraph">
      <h5>New Medical Conditions</h5>
      <p>You must advise us of any new or changed medical conditions which may reasonably be expected to increase your risk of needing medical attention, or which may materially affect the usual conduct of the trip.</p>
      <p>We may request you to provide an assessment of your medical condition from a qualified medical practitioner. If the assessment indicates that you will require special assistance which we cannot reasonably provide or if you fail to provide a medical assessment within a reasonable time, then this will be considered a cancellation by you and cancellation fees and charges will apply (See ‘Cancellation By You’ section above).</p>
    </div>
    <div class="paragraph">
      <h5>Non-Disclosed Medical Conditions</h5>
      <p>If any non-disclosed medical conditions mean that you will require special assistance which we cannot reasonably provide, then we acting reasonably may exclude you from the trip. This will be considered a cancellation by you and cancellation fees and charges will apply (See ‘Cancellation By You’ section above).</p>
    </div>
    <div class="paragraph">
      <h5>Dietary Requirements</h5>
      <p>Special dietary requests are required to be notified to us at the time of booking. Although we will use reasonable endeavours to accommodate requests, we cannot guarantee requests will be met by suppliers. It is your responsibility to check that meals and beverages do not contain any allergens. We expressly disclaim any liability for meals or beverages that contain allergens.</p>
    </div>
    <div class="paragraph">
      <h5>AUTHORITY & CONDUCT</h5>
      <p>If you are joining an escorted tour, you undertake to conduct yourself in a manner conducive to good group dynamics. If you act in a manner that threatens or disrupts the safety or enjoyment of others on the tour, the tour leader may, acting reasonably, require that you leave the tour. You will not be entitled to any refund for unused services and you will be responsible for any additional costs you incur.</p>
      <p>If you cause any damage to property or injury to person, then you will be responsible for all and any loss or damage incurred by Keith Prowse Travel. This is irrespective of whether you are travelling on an escorted tour or independently.</p>
    </div>
    <div class="paragraph">
      <h5>MINIMUM NUMBERS</h5>
      <p>Some trips are based on a minimum number of passengers travelling. We will advise you prior to confirming your booking if this is the case. If a trip fails to satisfy minimum numbers, the trip may be cancelled or re-costed. We will give you notice no later than 45 days prior to the trip’s commencement. If the trip is re-costed, you will have the option to either accept the new cost or to cancel your booking. You must make this election within 14 days of receiving notice from us. If the trip is cancelled or if you cancel your booking in these circumstances, we will at your election refund all payments made or credit payments towards alternative arrangements.</p>
      <p>We will not be responsible for any other travel arrangements affected by, or any additional costs incurred, as a result of cancellation in these circumstances.</p>
    </div>
    <div class="paragraph">
      <h5>UNUSED SERVICES</h5>
      <p>No refunds will be made for of any travel arrangements not utilised, whether by choice or because of late arrival or early departure. This
      includes the failure of transport to operate according to schedule, for which we disclaim responsibility.</p>
    </div>
    <div class="paragraph">
      <h5>ADDITIONAL INDEPENDENT SERVICES</h5>
      <p>We are not responsible for any additional travel arrangements (for example, pre and post tour accommodation), activities or excursions that we sell as agent for the principal supplier. Where we sell travel arrangements as agent for the principal supplier, you agree that our
      responsibility to you is limited to arranging for you to contract with the principal supplier for the arrangements. You agree that you will be
      subject to the principal supplier’s booking conditions, and that any claim in connection with the supply of (or failure to supply) such travel
      arrangements must be made directly against the principal supplier.</p>
    </div>
    <div class="paragraph">
      <h5>ACCEPTANCE OF RISK</h5>
      <p>You acknowledge that travel involves personal risks which may be greater than those present in your everyday life. This could be because of the adventurous nature of your tour or the visiting of destinations which present geographical, political or cultural risks and dangers. You should consult guidance issued by the Department of Foreign Affairs and Trade (DFAT) applicable to the destinations within your itinerary. You acknowledge that your choice to travel is made having had the benefit of DFAT guidance, and you accept any additional personal risks associated with your travel. To the fullest extent permitted by law, we disclaim any liability for these risks.</p>
      <p>You acknowledge that you are travelling at a time when Covid-19 is endemic and that Covid-19 presents risks to your health and may cause death. By making a booking request, you accept all risks associated with Covid-19 infection during travel and you release us (and our directors, officers, employees and suppliers) from liability in connection with Covid-19 infection.</p>
    </div>
   <!-- </div> -->
<!-- </section> -->

<!-- <section class="pdf-individual-block"> -->
  <!-- <div class="row">  -->
    <div class="paragraph">
      <h5>RESPONSIBILITY</h5>
      <h6>Services supplied by independent suppliers</h6>
      <p>Where a third party over whom we have no direct control (Independent Supplier) is the supplier of travel arrangements sold by us, you
          acknowledge that our obligations to you are limited to taking reasonable steps to select a reputable Independent Supplier and arranging for them to provide those travel arrangements to you. Independent Suppliers over whom we have no direct control include but are not limited to airlines, railway and cruise operators, hoteliers, independent transport companies (i.e., vehicles not operated by us), attraction and venue operators and common carriers.</p>
         <p> To the fullest extent permitted by law, we will not be responsible to you for any loss, damage, personal injury or delay attributable to the
          actions or omissions of an Independent Supplier and not caused by our negligence. You will be subject to the terms and conditions of the
          Independent Supplier. Any disputes between you and the Independent Supplier are to be resolved between you and them.</p>
      <p>In the event of the insolvency of an Independent Supplier prior to them delivering travel arrangements to you, our liability is limited to(a) using reasonable endeavours to recover payments made to them for your travel arrangements, which we will refund to you subject to receipt; and (b) using reasonable endeavours to put alternative travel arrangements in place, which will be at your cost.</p>
    </div>
    <div class="paragraph">
      <h5>Services we directly supply</h5>
      <p>To the extent only that we are the principal supplier to you of travel arrangements or other services which we control, then we will provide those travel arrangements and services with reasonable skill and care.</p>
      <p>We will only be responsible for our employees in the course of their employment, and for our agents and contractors (where we have control over them) if they were carrying out the work we had asked them to do.</p>
      <p>We will not be responsible for any loss, damage, claim or expense caused by the acts or omissions of yourself, of any other third party not connected with the provision of the travel arrangements or services, or due to an event of Force Majeure.</p>
    </div>
    <div class="paragraph">
        <h5>Recreational services</h5>
        <p>If we supply any recreational services to you, then to the maximum extent permitted by law we exclude any liability for death, physical injury or mental injury or any other liability referred to in section 139A(3) of the Competition and Consumer Act 2010 (Cth) resulting from our failure to comply with a guarantee that applies under Subdivision B of Division 1 of Part 3-2 of the Australian Consumer Law.</p>
        <p>This exclusion does not apply to significant personal injury caused by our reckless conduct.</p>
        <p>Recreational services means services that consist of participation in the activities referred to in Section 139A of the Competition and
        Consumer Act 2010 (Cth), being participation in:</p>
        <p>(a) a sporting activity or similar leisure time pursuit; or</p>
        <p>(b) any other activity that:</p>
        <p>(i) involves a significant degree of physical exertion or physical risk; and</p>
        <p>(ii) is undertaken for the purposes of recreation, enjoyment or leisure.</p>
        <p>(ii) is undertaken for the purposes of recreation, enjoyment or leisure.</p>
    </div>
    <div class="paragraph">
        <h5>General liability limitation</h5>
        <p>While we endeavour to meet scheduled arrival and departure times, we cannot guarantee this. We will not be responsible for any loss or additional expenses you incur for any missed connections/services attributable to delays.</p>
        <p>You acknowledge that travel arrangements or services which comply with local laws and regulations will be deemed to have been properly performed, even if this would not be considered the case in Australia.</p>
        <p>Australian Consumer Law and corresponding legislation in State jurisdictions in certain circumstances imply mandatory guarantees into
        consumer contracts (“Consumer Guarantees”). These Booking Conditions do not exclude or limit the application of the Consumer Guarantees other than to the extent they can be excluded or limited, in which case we limit or exclude the Consumer Guarantees to the fullest extent possible. Other than the Consumer Guarantees, we disclaim all warranties and guarantees.</p>
        <p>To the fullest extent permitted by law, our maximum liability to you under these Booking Conditions, in tort (including negligence) or at law is limited to arranging for the travel arrangements to be resupplied or payment of the cost of having the travel arrangements resupplied.</p>
    </div>
    <div class="paragraph">
        <h5>COMPLAINTS</h5>
        <p>In the event of a problem with any aspect of your travel arrangements you must tell us or make our representative or our local supplier aware of such problems as soon as possible. This is so we or our suppliers have had the opportunity to put things right on the ground. If you notify us of a problem during travel and we haven’t resolved it to your satisfaction, please follow this up in writing within 30 days from the end of your travel arrangements. This is so we have the opportunity to pursue the claim with our own suppliers (if relevant). If you fail to follow this procedure, this may limit your rights to make a claim. Prior to submitting or publishing any negative public review of our services or your travel arrangements, you agree to first contact us so that we can discuss your concerns and any issues with a view to resolving them. You agree to promptly remove any reviews published in contravention of this obligation upon receiving notice from us.</p>
    </div>

  <!-- </div> -->
<!-- </section> -->

<!-- <section class="pdf-individual-block"> -->
  <!-- <div class="row">  -->
    <div class="paragraph">
       <h5>DEEMED ACCEPTANCE</h5>
       <p>If you place a booking on behalf of another party, you represent and warrant us that you are duly authorised to provide the agreement and consent of the other party to be bound by these Booking Conditions. You agree that you will be responsible for any loss or damage we incur if this is not the case. Image release We may take photographs or make recordings of you and your activities that identify you during travel. We reserve the right to use any images and/or recordings for promotional and marketing purposes. You consent to this use and acknowledge you will not be entitled to any payment or other compensation. If you do not consent to the use of your image or likeness, please advise us as least 21 days prior to the commencement of your arrangements.</p>
    </div>
    <div class="paragraph">
       <h5>GENERAL</h5>
       <p>The contract between Keith Prowse Travel Pty Limited and you is governed by the laws of the State of New South Wales. Any disputes will be dealt with by a court with the appropriate jurisdiction in New South Wales. If any provision of these Booking Conditions is found to be unenforceable, then to the extent possible it will be severed without affecting the remaining provisions. Any personal information you provide to us will be collected, stored, used, protected and shared in accordance with Australian Privacy Principles, and Privacy Policy, which is published here https://www.kpt.com.au/privacy-policy</p>
       <p>Updated: April 2024</p>
       <p>Keith Prowse Travel PTY LTD </p>
       <p>ABN 31 003 276 775</p>
       <p>Level 7, 99 Mount Street</p>
       <p>North Sydney 2060 NSW</p>
       <p>Tel: 1300 730 023</p>
       <p>Email: travel@kpt.com.au</p>
    
      </div>

    <!-- </div> -->
 </section>
</main>


</body>
</html>

