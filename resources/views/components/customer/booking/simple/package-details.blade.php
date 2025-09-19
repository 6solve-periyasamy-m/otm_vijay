@php
/**
 * @var \App\Models\Booking\Booking $booking
 * @var \App\Models\Tour\Tour $tour
 */
@endphp
<style>
    .payable_dflex{
        display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 32px;
    }
    .payable_fulltext,
    .payable_txt,
    .payable_num{
        font-family: "PP Neue Montreal Medium";
        font-weight: 500;
        font-size:16px;
        line-height:24px;
    }
    .payable_fulltext{
        margin-top:8px;
    }

    .sub-heading-2 {
        font-family: "PP Neue Montreal Medium";
        font-weight: 500;
        font-size: 36px;
        line-height: 42px;
        color: var(--primary-color);
        letter-spacing: 2.24px;
        margin: 0 0 16px 0px;
        text-transform: uppercase;
    }
    .sub-heading-3 {
        font-family: "PP Neue Montreal Medium";
        font-weight: 500;
        font-size: 32px !important;
        line-height: 32px;
        color: var(--text-dark);
        margin: 0 0 16px 0px;
    }
    .location-dollar-value {
        display: flex;
        column-gap: 8px;
        align-items: center;
        padding-bottom: 0px;
        border-bottom: 1px solid #D1D5DB;
        margin-top:12px;
    }
    .location-dollar-value p {
        font-size: 16px;
        line-height: 24px;
        padding-left: 24px;
        position: relative;
        margin: 0;
        color: #333 !important;
    }
    .location-dollar-value > span {
        display: inline-block;
        width: 2px;
        height: 20px;
        background: #D1D5DB;
        margin-top: -40px;
    }
    .location-dollar-value p.dollar::before {
        background: url('/css/booking/icon/dollar.svg') no-repeat;
    }

    .location-dollar-value p::before {
        content: "";
        background: url('/css/booking/icon/location.svg') no-repeat;
        background-repeat: no-repeat;
        display: inline-block;
        width: 16px;
        height: 16px;
        position: absolute;
        left: 0px;
    }
    .location, .dollar{
        padding-left: 20px !important;
    }
    .psg-det { letter-spacing: 2.24px;padding-top: 40px !important;}

    .contact-block {
        background: #F9F4EE;
        padding: 24px;
        border-radius: 16px;
    }

    .contact-block p.description {
        padding-left: 26px;
        color: #333 !important;
        margin: 0 0 1px 0 !important;
    }

    .contact-block p.getintouch {
        padding-left: 26px;
        color: #333 !important;
        margin: 0 0 1px 0 !important;
    }

    .contact-block p {
        padding-bottom: 16px;
        margin: 0;
    }
    .contact-block p {
        font-size: 16px;
        line-height: 19px;
        color: #000000;
        position: relative;
    }

    .contact-block p a, .booking-dates a { 
        color: var(--primary-color);
        text-decoration: none;
        padding: 0 0 0 4px;
        cursor: pointer;
    }

    .booking-dates {
        margin: 0px 0px 24px 0px;
        font-size: 16px;
        line-height: 24px;
        color: #7a7a7a !important;
    }

    .subheading { font-size: 24px !important;}
    .sub-text-color { margin-top: 8px; color: #808080; font-size: 16px; line-height: 24px;}
    @media only screen and (max-width: 1278px) {
        .sub-heading-3 {
            line-height: 40px !important;
        }
    }
    .pkage_price,.gst_included{
        font-family: "PP Neue Montreal Medium";
        font-weight: 500;
        font-size: 16px;
        line-height: 24px;
        color: #000;
        display: flex;
        justify-content: space-between;
    }
    .pkage_price_total,.gst_included{padding: 32px 0px; border-bottom: 1px solid rgba(243, 91, 21, 0.2);}
    .package_prc_total{
        font-family: "PP Neue Montreal Bold";
        font-weight: 700;
        color: var(--text-dark);
        font-size: 18px;
        line-height: 24px;
        display: flex;
        justify-content: space-between;
    }
        
</style>
<div class="top-sec">
    <div class="head-txt"><h4>Package details</h4></div>
    <div class="upgrade-cls upgrade_hide_cta" data-action="popup" data-target="upgrades-popup">UPGRADE</div>
</div>
<div class="snd-sec">
    @if(isset($tour->event?->image_url))
        <div class="left-col package_details_img">
            <img class="package-image" src="{{ asset($tour->event?->image_url) }}" alt="featured-img">
        </div>
    @endif
    <div class="right-col package_details_right">
        <h4 class="hide-event" style="display:none!important;">{{ $tour->event?->name }}</h4>
        <div class="name_price_div">
            <h4>{{$tour->name}}</h4>
            <div class="base-price-div">
                {{ f_currency($tour->base_price_per_person) }}<span class="base-price-span"> / person </span>
            </div>
        </div>
        <!-- <h6>{{ $tour->name }}</h6> -->
        <!--<p class="location"></p> TODO: Implement Location on Event -->
        <p class="date">{{ $tour->date_from?->format('d M Y') }} - {{ $tour->date_to?->format('d M Y') }}</p>
        @foreach($tour->repository->getInclusions(4) as $inclusion)
            <p class="inclusion">{{ $inclusion }}</p>
        @endforeach
        <p class="see-more">
            <a href="#" class="seemore-href" data-action="popup" data-target="see-more-popup">MORE INFORMATION</a>
        </p>
    </div>
</div>

<div class="additional-inclusions">
    <div class="select-currency">
        <div class="single">
            <p></p>          
        </div>
        <div class="single">
            <p>Package price</p>
            <p>{{ f_currency($booking->repository->getBasePrice()) }}</p>
        </div>
        @php $singleOccupancy = $booking->repository->getSingleOccupancyAmount(); @endphp
        @if($singleOccupancy > 0 || $singleOccupancy < 0)
        <div class="single">
            <p>Single Occupancy</p>
            <p>{{ f_currency($singleOccupancy) }}</p>
        </div>
        @endif
    </div>

    <div class="total">
        <div class="single">
            <p>Total</p>
            <p>{{ f_currency($booking->repository->getTotalCost()) }}</p>
        </div>
        <div class="single">
            <p>Base Package Price</p>
            <p>{{ f_currency($booking->repository->getBasePrice()) }}</p>
        </div>
        @php $singleOccupancy = $booking->repository->getSingleOccupancyAmount(); @endphp
        @if($singleOccupancy > 0 || $singleOccupancy < 0)
        <div class="single">
            <p>Single Occupancy - {{ $booking->repository->getSingleOccupancyCount() }}</p>
            <p>{{ f_currency($singleOccupancy) }}</p>
        </div>
        @endif
        @if($booking->repository->getTaxes() !== null)
            <div class="single">
                <p>{{ $tour->taxBracket()->name }} (Included)</p>
                <p>{{ f_currency($booking->repository->getTaxes()) }}</p>
            </div>
        @endif
        @php $upgradePrice = $booking->repository->getUpgradeCosts(); @endphp
        @if($upgradePrice > 0 || $upgradePrice < 0)
            <div class="single">
                <p>Upgrades Price</p>
                <p>{{ f_currency($upgradePrice) }}</p>
            </div>
        @endif
    </div>
</div> 

<div class="third-col">
    <div class="payable_dflex">
    <div class="payable_txt">Payable now </div>
    <div class="payable_num">{{ f_currency($booking->repository->getDueTodayAmount()) }}</div>
    </div>
      <div class="payable_fulltext sub-text-color">Balance {{ f_currency($booking->repository->getTotalCost() - $booking->repository->getDueTodayAmount() ) }} payable by {{ $tour->final_payment->format('d M Y') }}</div>
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
                <div class="close-button">
                    <img src="/css/booking/icon/Close-Button.svg" alt="close-btn">
                </div>
                <div class="package-details-heading-block">
                <h3> Package details </h3>
                <!-- <div class="close-button">
                    <img src="/css/booking/icon/Close-Button.svg" alt="close-btn">
                </div> -->
                </div>
                <div class="full-top-blcls-two package_popup">
                    <div class="upp-block-two">
                        <div class="snd-sec">
                            <div class="left-col">
                                <img src="{{ asset($tour->event->image_url) }}" class="package-image" alt="featured-img">
                            </div>
                            <div class="right-col">
                                <h5>{{ $tour->name }}</h5>
                                <!--<p class="location">Sydney, Australia</p>-->
                                <p class="date">{{ $tour->date_from?->format('d M Y') }} - {{ $tour->date_to?->format('d M Y') }}</p>
                                <h6>Description</h6>
                                <p>{!! $tour->description !!}</p>                               
                                <h6>Inclusions</h6>
                                <!-- <p class="inclusion">Mens and womens final ticket</p>                                                         
                                <p class="inclusion">3 nights, 5 star accommodation</p>
                                <p class="inclusion">Exclusive tennis legend event</p>
                                <p class="inclusion">Lorem Ipsum is simply dummy </p>
                                <p class="inclusion">Lorem Ipsum is simply dummy text </p> -->
                                @foreach($tour->repository->getInclusions() as $inclusion)
                                    <p class="inclusion">{{ $inclusion }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div style="margin: 2rem;">
                    {{ $tour->description }}
                </div> -->
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
                    <h3>Trip Summary</h3>
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
