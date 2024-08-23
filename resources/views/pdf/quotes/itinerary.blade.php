@php
    /**
     * @var \App\Repository\Storage\Itinerary\Itinerary $itinerary
     * @var string $type
     */
    $type = $type ?? "Travel Itinerary"
    
@endphp
<?php 
  $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/images/pdf_assets/images/featured-image.png';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

    <style type="text/css">
        <?php //include(public_path() . '/css/kpt.css') ?>
        {!! setting('customization.documentation.colors') !!}
    </style>

  <style>
  :root { 
  --main-background-color:#F9F4EE;
  --text-head-color: #F35B15;
  --text-color: #000;
  --table-header-text: #FFFFFF;
  --head-text-background:#F35B15;
  }
  .pdf-header {
    background-color: var(--main-background-color);
    padding:14px 20px;
  }
  .pdf-individual-block {
    width: 595px;
    margin: auto;
    height: 1000px;
  }
    @font-face {
    font-family: "PlayfairDisplay-Medium";
    src: url(/images/pdf_assets/fonts/PlayfairDisplay-Medium.ttf);
    }
    @font-face {
    font-family: "PlayfairDisplay-Bold";
    src: url(/images/pdf_assets/fonts/PlayfairDisplay-Bold.ttf);
    }
    @font-face {
    font-family: "PPNeueMontreal-Medium";
    src: url(/images/pdf_assets/fonts/PPNeueMontreal-Medium.ttf);
    }
    @font-face {
    font-family: "PPNeueMontreal-Regular";
    src: url(/images/pdf_assets/fonts/PPNeueMontreal-Regular.ttf);
    }
  h1 {
    color: var(--text-color);
    font-family: "PlayfairDisplay-Medium";
    font-size: 35px;
    font-weight: 500;
    line-height: 35px;
    margin-bottom: 16px;
    margin-top: 20px;
  }
  .customer-details-block h6 {
    font-family: "PPNeueMontreal-Medium";
    font-size:9px;
    font-weight:500;
    line-height:10px;
    margin:0px;
    margin-bottom:6px;
    color: var(--text-head-color);
  }
  
 .customer-details-block .customer-details-text-block,.customer-details-block .customer-details-image-block  {
    float:left;  
 }
 .customer-details-block .customer-details-image-block {
  width:209px;
  height:198px;
 }
 .customer-details-block .customer-details-text-block {width: 386px;}
  h5 {
  font-family: "PPNeueMontreal-Medium";
  font-size: 12px;
  font-weight: 500;
  line-height: 14.4px;
  margin-bottom: 6px;
  color: var(--text-color);
 }
 /* .customer-agent-details .customer-details,.customer-agent-details .agent-details {float:left;} */
 .customer-agent-details .customer-details, .customer-agent-details .agent-details{width:185px;}
 .customer-agent-details p {
  font-family: "PPNeueMontreal-Medium";
  font-size: 10px;
  font-weight: 500;
  line-height: 19px;
  margin:0px;
  margin-bottom: 0px ! Important;
  color: var(--text-color);
 }
.customer-details-image-block {width:209px;}
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
}
.information-block .column {float:left;} 
.information-block .column{width:290px;}
.information-block .column {
  font-family: "PPNeueMontreal-Medium";
  font-size: 10px;
  font-weight: 500;
  line-height: 10.58px; 
  color: var(--text-color);
}
.information-block .column .single p{
  float:left;
  width:130px;
  font-family: "PPNeueMontreal-Medium";
  font-size: 9px;
  font-weight: 500;
  line-height: 10.58px; 
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
.customer-details-image-block img {width:100%;height:100%;0bject-fit:cover;}
h2 {
    color: var(--table-header-text);
    font-family: "PlayfairDisplay-Bold";
    font-size: 16px;
    font-weight: 700;
    line-height: 21.33px;
    padding:6px 21px;
    background-color:var(--head-text-background);
    margin:0;
}

h3 {
    margin: 40px 10px 0px 0px;
    font-family: "PPNeueMontreal-Medium";
    font-size: 16px;
    font-weight: 500;
    line-height: 19.2px;
    color: var(--text-color);
}
h3 span.mark {
    width: 5px;
    height: 30px;
    display: inline-block;
    margin-right: 15px;
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
  padding-left: 22px;
  max-width: 586px;
  margin-bottom:24px;
}
h4 {
    font-family: "PPNeueMontreal-Regular";
    font-size: 14px;
    font-weight: 500;
    line-height: 16.8px;
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
    font-size: 9px;
    font-weight: 400;
    line-height: 18px;
    color: var(--text-color);
    margin: 0px 0px 0px 0px;
}
.single-module table td strong {
    font-family: "PPNeueMontreal-Medium";
    font-weight: 500;
    margin: 0px 0px 0px 0px;
    margin-right: 30px;
    display: inline-block;
    min-width: 55px;
}
.customer-agent-details {position:relative;}
.agent-details {
    margin-left: auto;
    position: absolute;
    right: 0;
    top: 0;
}
.information-block table {
  padding: 15px 20px;
}
.information-block table td {
    font-family: "PPNeueMontreal-Regular";
    font-size: 9px;
    font-weight: 400;
    line-height: 10.58px;
    color: var(--text-color);
    margin: 0;
    width:138px;
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
.custom-details-module {padding-left:20px;}
.custom-details-module  h6{
    font-family: "PPNeueMontreal-Medium";
    font-weight: 500;
    margin: 30px 0px 15px 20px;
    font-size: 9px;
    line-height: 18px;
}
.custom-details-module table td {
    font-family: "PPNeueMontreal-Regular";
    font-size: 9px;
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
  margin: 25px 0px;
  padding-left: 20px;
}
.paragraph h5, .paragraph p, .paragraph h6, .paragraph ul li {
    font-size: 9px;
    font-weight: 400;
    line-height: 15.3px;
    color: var(--text-color);
}

.paragraph h5,.paragraph h6 {
    font-family: "PPNeueMontreal-Medium";
    font-weight: 500;
    margin: 0px 0px 10px 0px;
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

</style>

   
</head>

<body class="body">

<main>
<section class="pdf-individual-block">
  <div class="row">
      <div class="pdf-header">
         <div class="header-logo">
          <img src="{{ svg_to_b64('images/pdf_assets/images/KeithProwse_Logo.png') }}" alt="logo-ch">
        
         </div>
      </div>
	  
      <div class="customer-details-block">
        <div class="customer-details-text-block">
             <h1>Quote</h1>
             <h5>REFERENCE: KPQ00004FF <span></span></h5>         
          <div class="customer-agent-details">
            <div class="customer-details">
               <h6>CUSTOMER DETAILS</h6>
               <p>Name: <span>Adrian Robins</span></p>
               <p>Email: <span>adrian.robins@hotmail.co.uk</span><p>
            </div>
            <div class="agent-details">
               <h6>AGENT DETAILS</h6>
               <p>Name: <span>Keith Prowse Travel</span></p>
               <p>Email: <span>travel@kpt.com.au</span><p>
               <p>Date created: <span>06 August 2024</span><p>
            </div>
          </div>  
        </div>
        <div class="customer-details-image-block">
          <img src="{{ svg_to_b64('images/pdf_assets/images/featured-image.png') }}" alt="image-block">
        
        </div>       
      </div>
	
    <div class="information-block">
    <table>
    <tr>
        <td><strong>Event:</strong></td>
        <td>British and Irish Lions Tour 2025</td>
        <td><strong>Total number of persons:</strong></td>
        <td>2 Adult(s)</td>
    </tr>
    <tr>
        <td><strong>Travel dates:</strong></td>
        <td>18 July 2025 - 02 August 2025</td>
        <td><strong>Lead guest:</strong></td>
        <td>Adrian Robins</td>
    </tr>
</table>
</div>
  <div class="heading-2">
	  <h2>Package inclusions</h2> 
  </div>
  <div class="heading-module">
    <h3>
     <span class="mark"></span>
     <span class="text">Accommodation</span>
    </h3>
  </div>

  <div class="single-module">
    <h4>   
    <span class="text">The Langham, Melbourne</span>
    <span class="mark"></span>
    </h4> 
    <div class="details-module">
        <table>
           <tbody>
              <tr>            
                <td><strong>Check In:</strong></td>
                 <td>25 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>Check Out:</strong></td>
                      <td>26 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>No of Nights:</strong></td>
                      <td>1</td>
                  </tr>
                  <tr>
                      <td><strong>Address:</strong></td>
                      <td>1 Southgate Avenue, Southbank, VIC, Australia, 3006</td>
                  </tr>
                  <tr>
                      <td><strong>Room Type:</strong></td>
                      <td>Superior Room - Double</td>
                  </tr>
                  <tr>
                      <td><strong>Board Type:</strong></td>
                      <td>Room and breakfast</td>
                  </tr>
                  <tr>
                      <td><strong>Quantity:</strong></td>
                      <td>2</td>
                  </tr>
                  <tr>
                      <td><strong>Description:</strong></td>
                      <td>The Langham, Melbourne offers five-star service and hospitality on the banks of the Yarra River
                          overlooking the Melbourne skyline. All rooms feature elegant decor, modern, stylish furnishing,
                          and attention to detail that makes for a relaxing stay in Australia's City of Arts and Culture.</td>
                  </tr>
           </tbody>
        </table>
    </div>
   </div>
  </div>
  </section>

<section class="pdf-individual-block">
   <div class="row">
   <div class="single-module">
    <h4>   
    <span class="text">The Langham, Melbourne</span>
    <span class="mark"></span>
    </h4> 
    <div class="details-module">
        <table>
           <tbody>
              <tr>            
                <td><strong>Check In:</strong></td>
                 <td>25 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>Check Out:</strong></td>
                      <td>27 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>No of Nights:</strong></td>
                      <td>1</td>
                  </tr>
                  <tr>
                      <td><strong>Address:</strong></td>
                      <td>1 Southgate Avenue, Southbank, VIC, Australia, 3006</td>
                  </tr>
                  <tr>
                      <td><strong>Room Type:</strong></td>
                      <td>Superior Room - Double</td>
                  </tr>
                  <tr>
                      <td><strong>Board Type:</strong></td>
                      <td>Room and breakfast</td>
                  </tr>
                  <tr>
                      <td><strong>Quantity:</strong></td>
                      <td>2</td>
                  </tr>
                  <tr>
                      <td><strong>Description:</strong></td>
                      <td>The Langham, Melbourne offers five-star service and hospitality on the banks of the Yarra River overlooking the Melbourne skyline. All rooms feature elegant decor, modern, stylish furnishing,
                 and attention to detail that makes for a relaxing stay in Australia's City of Arts and Culture.</td>
                  </tr>
           </tbody>
        </table>
    </div>
   </div>
   <div class="single-module">
    <h4>   
    <span class="text">The Langham, Melbourne</span>
    <span class="mark"></span>
    </h4> 
    <div class="details-module">
        <table>
           <tbody>
              <tr>            
                <td><strong>Check In:</strong></td>
                 <td>18 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>Check Out:</strong></td>
                      <td>19 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>No of Nights:</strong></td>
                      <td>1</td>
                  </tr>
                  <tr>
                      <td><strong>Address:</strong></td>
                      <td>111 Mary Street, Brisbane City, Queensland, Australia, 4000</td>
                  </tr>
                  <tr>
                      <td><strong>Room Type:</strong></td>
                      <td>Westin Room - Double</td>
                  </tr>
                  <tr>
                      <td><strong>Room and breakfast</strong></td>
                      <td>Room and breakfast</td>
                  </tr>
                  <tr>
                      <td><strong>Quantity:</strong></td>
                      <td>2</td>
                  </tr>
                  <tr>
                      <td><strong>Description:</strong></td>
                      <td>Refreshingly unique The Westin Brisbane celebrates Brisbane City’s air of laid-back sophistication.Located in the city centre near popular attractions like the Botanic Gardens,
                      Queen Street Mall, Suncorp Stadium and Queensland Performing Arts Centre (QPAC), this luxury hotel features contemporary rooms with Westin Heavenly® Beds and floor-to-ceiling windows.</td>
                  </tr>
           </tbody>
        </table>
    </div>
   </div>
   <div class="single-module">
    <h4>   
    <span class="text">The Langham, Melbourne</span>
    <span class="mark"></span>
    </h4> 
    <div class="details-module">
        <table>
           <tbody>
              <tr>            
                <td><strong>Check In:</strong></td>
                 <td>18 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>Check Out:</strong></td>
                      <td>19 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>No of Nights:</strong></td>
                      <td>1</td>
                  </tr>
                  <tr>
                      <td><strong>Address:</strong></td>
                      <td>111 Mary Street, Brisbane City, Queensland, Australia, 4000</td>
                  </tr>
                  <tr>
                      <td><strong>Room Type:</strong></td>
                      <td>Westin Room - Double</td>
                  </tr>
                  <tr>
                      <td><strong>Room and breakfast</strong></td>
                      <td>Room and breakfast</td>
                  </tr>
                  <tr>
                      <td><strong>Quantity:</strong></td>
                      <td>2</td>
                  </tr>
                  <tr>
                      <td><strong>Description:</strong></td>
                      <td>Refreshingly unique The Westin Brisbane celebrates Brisbane City’s air of laid-back sophistication.Located in the city centre near popular attractions like the Botanic Gardens,
                      Queen Street Mall, Suncorp Stadium and Queensland Performing Arts Centre (QPAC), this luxury hotel features contemporary rooms with Westin Heavenly® Beds and floor-to-ceiling windows.</td>
                  </tr>
           </tbody>
        </table>
    </div>
   </div>
   </div>
</section>

<section class="pdf-individual-block">
   <div class="row">

   <div class="single-module">
    <h4>   
    <span class="text">The Langham, Melbourne</span>
    <span class="mark"></span>
    </h4> 
    <div class="details-module">
        <table>
           <tbody>
              <tr>            
                <td><strong>Check In:</strong></td>
                 <td>18 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>Check Out:</strong></td>
                      <td>19 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>No of Nights:</strong></td>
                      <td>1</td>
                  </tr>
                  <tr>
                      <td><strong>Address:</strong></td>
                      <td>111 Mary Street, Brisbane City, Queensland, Australia, 4000</td>
                  </tr>
                  <tr>
                      <td><strong>Room Type:</strong></td>
                      <td>Westin Room - Double</td>
                  </tr>
                  <tr>
                      <td><strong>Room and breakfast</strong></td>
                      <td>Room and breakfast</td>
                  </tr>
                  <tr>
                      <td><strong>Quantity:</strong></td>
                      <td>2</td>
                  </tr>
                  <tr>
                      <td><strong>Description:</strong></td>
                      <td>Refreshingly unique The Westin Brisbane celebrates Brisbane City’s air of laid-back sophistication.Located in the city centre near popular attractions like the Botanic Gardens,
                      Queen Street Mall, Suncorp Stadium and Queensland Performing Arts Centre (QPAC), this luxury hotel features contemporary rooms with Westin Heavenly® Beds and floor-to-ceiling windows.</td>
                  </tr>
           </tbody>
        </table>
    </div>
   </div>
   <div class="heading-module">
    <h3>
     <span class="mark"></span>
     <span class="text">Event</span>
    </h3>
   </div>
   <div class="single-module">
    <h4>   
    <span class="text">The Langham, Melbourne</span>
    <span class="mark"></span>
    </h4> 
    <div class="details-module">
        <table>
           <tbody>
              <tr>            
                <td><strong>Dates:</strong></td>
                 <td>26 Jul 2025 to 26 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>Venue:</strong></td>
                      <td>Melbourne Cricket Ground</td>
                  </tr>
                  <tr>
                      <td><strong>Ticket:</strong></td>
                      <td>Test 2 - Wallabies v Lions - Category 1</td>
                  </tr>
                  <tr>
                      <td><strong>Quantity:</strong></td>
                      <td>1</td>
                  </tr>
                  <tr>
                      <td><strong>Description:</strong></td>
                      <td>Be there as the MCG comes alive with the second Test of the series, where The British & Irish Lions will clash with the Wallabies in this high-stakes Test match. The Wallabies beat the Lions
                      in front of a full-house the last time the two teams played in Melbourne, and with the Lions competing on the hallowed MCG turf for the first time ever, this showdown promises a night of
                      sporting drama. Don’t miss this epic battle!</td>
                  </tr>                 
           </tbody>
        </table>
    </div>
   </div>
   <div class="single-module">
    <h4>   
    <span class="text">The Langham, Melbourne</span>
    <span class="mark"></span>
    </h4> 
    <div class="details-module">
        <table>
           <tbody>
              <tr>            
                <td><strong>Dates:</strong></td>
                 <td>26 Jul 2025 to 26 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>Venue:</strong></td>
                      <td>Melbourne Cricket Ground</td>
                  </tr>
                  <tr>
                      <td><strong>Ticket:</strong></td>
                      <td>Test 2 - Wallabies v Lions - Category 1</td>
                  </tr>
                  <tr>
                      <td><strong>Quantity:</strong></td>
                      <td>1</td>
                  </tr>
                  <tr>
                      <td><strong>Description:</strong></td>
                      <td>Be there as the MCG comes alive with the second Test of the series, where The British & Irish Lions will clash with the Wallabies in this high-stakes Test match. The Wallabies beat the Lions
                      in front of a full-house the last time the two teams played in Melbourne, and with the Lions competing on the hallowed MCG turf for the first time ever, this showdown promises a night of
                      sporting drama. Don’t miss this epic battle!</td>
                  </tr>                 
           </tbody>
        </table>
    </div>
 </div>

   </div>
</section>

<section class="pdf-individual-block">
   <div class="row">
   <div class="heading-module">
    <h3>
     <span class="mark"></span>
     <span class="text">Inclusion</span>
    </h3>
   </div>
   <div class="single-module">
    <h4>   
    <span class="text">British and Irish Lions Tour 2025</span>
    <span class="mark"></span>
    </h4> 
    <div class="details-module">
        <table>
           <tbody>
              <tr>            
                <td><strong>Dates:</strong></td>
                 <td>19 Jul 2025 to 19 Jul 2025</td>
                  </tr>
                  <tr>
                      <td><strong>Venue:</strong></td>
                      <td>Suncorp Stadium</td>
                  </tr>
                  <tr>
                      <td><strong>Ticket:</strong></td>
                      <td>Keith Prowse Travel Pre-Match Function - Test 1</td>
                  </tr>
                  <tr>
                      <td><strong>Quantity:</strong></td>
                      <td>2</td>
                  </tr>
                  <tr>
                      <td><strong>Description:</strong></td>
                      <td>???Join Keith Prowse Travel and fellow rugby enthusiasts for food and drinks on Caxton Street.
                      Don't miss this opportunity to meet one of our rugby ambassadors!</td>
                  </tr>    
                  <tr>
                      <td><strong></strong></td>
                      <td>Inclusions
                          - 2.5 hour package pre-match
                          - Wine, Beer and Sparkling
                          - Food stations</td>
                  </tr>               
           </tbody>
        </table>
    </div>
   </div>
   <h2>Payment summary</h2> 
   <div class="single-module">
  
    <div class="details-module">
        <table>
           <tbody>
                  <tr>            
                   <td><strong>BOOKING TOTAL</strong></td>
                   <td>A$0.00</td>
                  </tr>
                  <tr>
                      <td><strong>GST (included)</strong></td>
                      <td>No Taxes Due</td>
                  </tr>
                  <tr>
                      <td><strong>FINAL COST</strong></td>
                      <td>A$0.00</td>
                  </tr>
                                    
           </tbody>
        </table>
    </div>
   </div>
   
   <h2>Payment summary</h2> 
   <div class="custom-details-module">     
       <h6>Keith Prowse Travel PTY LTD</h6>      
  <table>
    <tr>
        <td style="vertical-align: top;">
            <strong>BANK TRANSFER</strong><br>
            ABN: 31 003 276 775<br>
            BSB: 032-298<br>
            ACC: 540726<br>
            SWIFT: WPACAU2S<br>
            BANK: Westpac<br>
            BRANCH: Crows Nest
        </td>
        <td style="vertical-align: top;">
            <strong>PAYMENT GATE</strong><br>
            Payment Gate: KPTVL
        </td>
    </tr>
  </table>   
   </div>
   </div>
</section>


<section class="pdf-individual-block">
   <div class="row">
     
   <h2>Notes</h2> 
  <div class="custom-details-module">     
     <h6>KPAD106309</h6>      
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
    

  </div>
</section>

<section class="pdf-individual-block">
   <div class="row">
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
   </div>
</section>
<section class="pdf-individual-block">
   <div class="row"> 
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
          <h5>However, we will either:</h5>
          <ul>
           <li>issue you with a credit equal to payments received by us for the cancelled travel arrangements, redeemable within 12 months of issue against any travel services offered by us; or</li>
           <li>refund payments attributable to the cancelled travel arrangements less: (a) unrecoverable third party costs and other expenses incurred or payable by us for the cancelled travel arrangements; (b) overhead charges incurred by us relative to the price of the cancelled travel arrangements; and (c) fair compensation for work undertaken by us in relation to the cancelled travel arrangements until the time of cancellation and in connection with the processing of any refund.</li>
          </ul>

    </div>
   <div>
<section>

</main>


</body>
</html>

