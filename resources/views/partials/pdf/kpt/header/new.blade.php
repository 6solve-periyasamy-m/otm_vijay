@php /** @var \App\Repository\Storage\Itinerary\Itinerary $itinerary */ 
    $header_logo = svg_to_b64($itinerary->brand->logo) ;
@endphp
@if(!empty($itinerary->image))
    <img src="{{ $itinerary->image }}" alt="{{ $itinerary->package }}" style="width: 796px;height: 147px;margin-top: -10px;">
@else
    <div class="banner_header"></div>
@endif

<h1 class="travel_title"> {{ $type }} </h1>
<div class="pdf-header">
    <div class="header-logo">
        <img src="{{ $header_logo }}" alt="{{ $itinerary->brand->name }}">
    </div>
</div>