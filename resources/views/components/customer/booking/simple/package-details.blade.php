@php
/**
 * @var \App\Models\Booking\Booking $booking
 * @var \App\Models\Tour\Tour $tour
 */
@endphp
<div class="top-sec">
    <div class="head-txt"><h4>Package details</h4></div>
    {{--<div class="upgrade-cls" data-action="popup" data-target="upgrades-popup">UPGRADE</div>--}}
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
        <p class="see-more">
            <a href="#" class="seemore-href" data-action="popup" data-target="see-more-popup">See more</a>
        </p>
    </div>
</div>
<div class="third-col">
    <ul>
        <li>
            <p class="txt">Package Price</p>
            <p class="price">{{ f_currency($booking->repository->getBasePrice()) }}</p>
        </li>
        @if($tour->booking_fee !== 0)
            <li>
                <p class="txt">Booking Fee</p>
                <p class="price">{{ f_currency($tour->booking_fee) }}</p>
            </li>
        @endif
        @php $upgradePrice = $booking->repository->getUpgradeCosts(); @endphp
        @if($upgradePrice > 0 || $upgradePrice < 0)
            <li>
                <p class="txt">Upgrades Price</p>
                <p class="price">{{ f_currency($upgradePrice) }}</p>
            </li>
        @endif
        @php $singleOccupancy = $booking->repository->getSingleOccupancyAmount(); @endphp
        <li>
            <p class="txt">Single occupancy surcharge</p>
            <p class="price">{{ f_currency($singleOccupancy) }}</p>
        </li>
        @if($booking->repository->getTaxes() !== null)
            <li>
                <p class="txt">{{ $tour->taxBracket()->name }} (Included)</p>
                <p class="price">{{ f_currency($booking->repository->getTaxes()) }}</p>
            </li>
        @endif
        <li>
            <p class="total">Total</p>
            <p class="price">{{ f_currency($booking->repository->getTotalCost()) }}</p>
        </li>
    </ul>
    {{ $slot }}
    @error('common')
    <div class="submit-btn-cls add-on">
        <div class="inner">
            <span style="color: red">{{ $message }}</span>
        </div>
    </div>
    @enderror

    <div style="padding-top: 1rem;">
        <div id="airwallex-container" class="airwallex-content"></div>
    </div>
</div>
@push('popups')
<!-- <- See More Popup -->
<div class="see-more-popup" data-role="closeable">
    <div class="popup-inner-two">
        <div class="convco-two">
            <div class="whole-block-two">
                <div class="full-top-blcls-two">
                    <h3> Package details </h3>
                    <div class="upp-block-two">
                        <div class="snd-sec">
                            <div class="left-col">
                                <img src="{{ asset($tour->event->image_url) }}" class="package-image" alt="featured-img">
                            </div>
                            <div class="right-col">
                                <h6>{{ $tour->name }}</h6>
                                <!--<p class="location">Sydney, Australia</p>-->
                                <p class="date">{{ $tour->date_from?->format('M d, Y') }} - {{ $tour->date_to?->format('M d, Y') }}</p>
                                @foreach($tour->repository->getInclusions() as $inclusion)
                                    <p class="inclusion">{{ $inclusion }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin: 2rem;">
                    {{ $tour->description }}
                </div>
            </div>
            <div class="close-btn" data-action="close">
                {{ Icon::solid('xmark') }}
            </div>
        </div>
    </div>
</div>
<!-- <- Upgrades Popup -->
<div class="upgrades-popup" data-role="closeable">
    <div class="popup-inner">
        <div class="convco">
            <div class="whole-block">
                <div class="full-top-blcls">
                    <h3>Optional add-ons & upgrades</h3>
                    <div class="upp-block">
                        <h6>Ticket Upgrade</h6>
                        <div class="inner-contain">
                            <div class="fst-lv">
                                <img src="https://qa.octopustravelmatrix.com/images/accommodation/accommodation_5.jpg" alt="featured-image">
                            </div>
                            <div class="snd-lv">
                                <p>4 Nights, 5 Star Accommodation</p>
                                <p class="location">Sydney Australia</p>
                                <p class="value">Nov 20, 2024 - Nov 30, 2024</p>
                            </div>
                            <div class="third-col">
                                <div class="doll"><p>A$2,300</p></div>
                                <div class="intial add">Added</div>
                            </div>
                        </div>
                        <div class="inner-contain">
                            <div class="fst-lv">
                                <img src="https://qa.octopustravelmatrix.com/images/accommodation/accommodation_5.jpg" alt="featured-image">
                            </div>
                            <div class="snd-lv">
                                <p>4 Nights, 5 Star Accommodation</p>
                                <p class="location">Sydney Australia</p>
                                <p class="value">Nov 20, 2024 - Nov 30, 2024</p>
                            </div>
                            <div class="third-col">
                                <div class="doll"><p>A$2,300</p></div>
                                <div class="intial">Add</div>
                            </div>
                        </div>
                    </div>

                    <div class="upp-block">
                        <h6>Stay extra nights</h6>
                        <div class="inner-contain">
                            <div class="fst-lv">
                                <img src="https://qa.octopustravelmatrix.com/images/accommodation/accommodation_5.jpg" alt="featured-image">
                            </div>
                            <div class="snd-lv">
                                <p>4 Nights, 5 Star Accommodation</p>
                                <p class="location">Sydney Australia</p>
                                <p class="value">Nov 20, 2024 - Nov 30, 2024</p>
                            </div>
                            <div class="third-col">
                                <div class="doll"><p>A$2,300</p></div>
                                <div class="intial add">Added</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bottom-block">
                    <div class="total-col"><p>Total : A$2,300</p></div>
                    <div class="upgrade-btn-cls-v">Upgrade</div>

                </div>
            </div>
            <div class="close-btn" data-action="close">
                {{ Icon::solid('xmark') }}
            </div>
        </div>
    </div>
</div>
@endpush
