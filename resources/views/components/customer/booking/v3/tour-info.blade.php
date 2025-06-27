{{-- resources/views/components/customer/booking/v3/tour-info.blade.php --}}
@php
    $location = collect([$tour->city, $tour->country?->name])->filter()->implode(', ');
@endphp
<div class="tour-details">
    <h2 class="sub-heading-2">{{ $tour->name }}</h2>
    <h3 class="sub-heading-3">{{ $tour->event?->name }}</h3>
    <div class="location-dollar-value">
        @if($location)
            <p class="location">{{ $location }}</p>
            <span></span>
        @endif                    
        <p class="dollar">From {{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getBasePrice(), $selectedCurrency) , $selectedCurrency) }} / person twin share</p>
    </div>
</div>