@php
/**
 * @var \App\Models\Booking\Booking $booking
 * @var \App\Models\Tour\Tour $tour
 */
@endphp
<div class="top-sec">
    <div class="head-txt"><h4>Package details</h4></div>
    <div class="upgrade-cls">UPGRADE</div>
</div>
<div class="snd-sec">
    @if(isset($tour->event?->image_url))
        <div class="left-col">
            <img class="package-image" src="{{ asset($tour->event?->image_url) }}" alt="featured-img">
        </div>
    @endif
    <div class="right-col">
        <h4>{{ $tour->event?->name }}</h4>
        <h6>{{ $tour->name }}</h6>
        <!--<p class="location"></p> TODO: Implement Location on Event -->
        <p class="date">{{ $tour->date_from?->format('M d, Y') }} - {{ $tour->date_to?->format('M d, Y') }}</p>
        @foreach($tour->repository->getInclusions(3) as $inclusion)
            <p class="inclusion">{{ $inclusion }}</p>
        @endforeach
        <p class="see-more"><a href="#">See more</a></p>
    </div>
</div>
<div class="third-col">
    <ul>
        <li>
            <p class="txt">Package Price</p>
            <p class="price">{{ f_currency($booking->repository->getBasePrice()) }}</p>
        </li>
        @php $upgradePrice = $booking->repository->getUpgradeCosts(); @endphp
        @if($upgradePrice > 0 || $upgradePrice < 0)
            <li>
                <p class="txt">Upgrades Price</p>
                <p class="price">{{ f_currency($upgradePrice) }}</p>
            </li>
        @endif
        @php $singleOccupancy = $booking->repository->getSingleOccupancyAmount(); @endphp
        @if($singleOccupancy > 0 || $singleOccupancy < 0)
            <li>
                <p class="txt">Single occupancy surcharge</p>
                <p class="price">{{ f_currency($singleOccupancy) }}</p>
            </li>
        @endif
        @if($booking->repository->getTaxes() !== null)
            <li>
                <p class="txt">{{ $tour->taxBracket()->name }}</p>
                <p class="price">{{ f_currency($booking->repository->getTaxes()) }}</p>
            </li>
        @endif
        <li>
            <p class="total">Total</p>
            <p class="price">{{ f_currency($booking->repository->getTotalCost()) }}</p>
        </li>
    </ul>
    {{ $slot }}
</div>
