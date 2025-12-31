@php
    $location = collect([$tour->city, $tour->country?->name])->filter()->implode(',  ');
    //dd($tour);
@endphp
<div class="row">
    <div class="left-col">
        <div class="contain">
            <div class="tour-details">
                <h2 class="sub-heading-2">{{ $tour->package_name }}</h2>
                <h3 class="sub-heading-3">{{ $tour->event?->name }}</h3>
                <div class="location-dollar-value">
                    @if($location)
                        <p class="location">{{ $location }}</p>
                        <span></span>
                    @endif
                    {{-- <p class="dollar">From {{ $this->formatCurrency($tour->base_price_per_person) }} / person twin share</p> --}}
                    <p class="dollar">From {{ f_currency_booking($tour->base_price_per_person) }} / person twin share</p>
                </div>
            </div>
            <p class="psg-det">PASSENGER DETAILS</p>
            <div class="top-form-contain">
                <div class="form-field">
                    <input type="email" wire:model.defer="lead.email_address" placeholder="Email*" required>
                    @error('lead.email_address') <label class="error-label">{{ $message }}</label> @enderror
                </div>
            </div>
            <!-- <- Additional Travellers -->
            <div class="second-block tra-det">
                <!-- <p>TRAVELLER/S</p> -->
                <div class="inner-block">
                    <div class="left-col">
                        <h6 class="subheading">Number of Travellers</h6>
                        <p class="age_limit">Ages 13 or above</p>
                    </div>
                    <div class="right">
                        <div class="inn">
                            <span class="Min" wire:click="removeTraveller()"><img
                                    src="{{ asset('css/booking/icon/minus.svg') }}" alt="minus" /></span>
                            <span class="No"><span>|</span> <span class="text">{{ $this->getTravellerCount() }}</span>
                                <span>|</span></span>
                            <span class="Max" wire:click="addTraveller()"><img
                                    src="{{ asset('css/booking/icon/Plus.svg') }}" alt="minus" /></span>
                        </div>
                    </div>
                </div>
                {{--
                <div class="contact-block">
                    <p class="description">If you are a concession card holder, or booking with children under 12, <a href="https://www.kpt.com.au/contact-us/" target="_blank">get in touch</a> for a tailor-made package</p>
                </div> </br>
                <!-- <div class="contact-block">
                    <p class="getintouch">Travelling with children?<a href="https://www.kpt.com.au/contact-us/" target="_blank">Get in
                            touch</a>
                        for a custom package.</p>
                </div> -->
                 --}}
            </div>
            <style>
                .ma-block .row .right-col .contain .third-col ul li p.price.tot-price {
                    font-weight: 700;
                }

                .desktop_seemore {
                    font-family: "PP Neue Montreal Medium";
                    font-weight: 500;
                    font-size: 14px;
                    line-height: 14px;
                    color: #F35B15;
                    text-align: center;
                    cursor: pointer;
                }

                .date_outer_div {
                    border: 1px solid #F35B15;
                    border-radius: 50px;
                    padding: 7.5px 0;
                }

                .check_text_dflex {
                    display: flex;
                    align-items: center;
                    justify-content: space-around;
                    max-width: 237px;
                    margin: 0 auto;
                }

                .checkintext,
                .checkouttext {
                    font-family: "PP Neue Montreal Medium";
                    font-weight: 400;
                    font-size: 12px;
                    line-height: 14.4px;
                    color: #F35B15;
                }

                .checkindate,
                .checkoutdate,
                .dateslash {
                    font-family: "PP Neue Montreal Medium";
                    font-weight: 500;
                    font-size: 16px;
                    line-height: 19.2px;
                    color: #000000;
                }

                .check_text_div {
                    margin-bottom: 4px;
                }

                .dateslash {
                    color: #721111;
                }

                @media only screen and (max-width:767px) {
                    .mob-trip-summary-block .mobile_seemore {
                        display: none;
                    }
                }

                @media only screen and (min-width:767px) {
                    .Inner-container .mob-static-tip-sum h4 {
                        margin-bottom: 25px;
                    }

                    .mob-trip-summary-block .block-container .Inner-container {
                        position: relative;
                    }

                    .mob-trip-summary-block .block-container .Inner-container .mob-static-tip-sum .price {
                        position: absolute;
                        right: 0;
                        top: 0;
                        font-family: "PP Neue Montreal Medium";
                        font-weight: 700;
                        font-size: 16px;
                        line-height: 24px;
                    }

                    .block-container .Inner-container .total-block,
                    .block-container .Inner-container .mobile_seemore {
                        display: none;
                    }

                    .mob-trip-summary-block .total-block ul li p {
                        font-family: "PP Neue Montreal Medium";
                        font-weight: 700;
                        font-size: 16px;
                        line-height: 24px;
                        color: #000000;
                    }

                    .mob-trip-summary-block .mob-static-see-more p {
                        font-family: "PP Neue Montreal Medium";
                        font-weight: 500;
                        font-size: 12px;
                        line-height: 12px;
                        color: var(--primary-color);
                        letter-spacing: 2.24px;
                        text-align: center;
                    }

                    .Inner-container .mob-static-tip-sum h4 {
                        font-family: "PP Neue Montreal Medium";
                        font-weight: 500;
                        font-size: 24px;
                        line-height: 24px;

                    }

                    .mob-trip-summary-block {
                        max-width: 924px;
                        margin: 0 auto;
                        border: 2px solid #F35B15;
                        border-radius: 8px;
                        padding: 16px;
                        /* position: sticky; */
                        bottom: unset;
                        background: #ffffff;
                        /* padding: 16px 8px; */
                        z-index: 999;
                        width: 100%;
                        position: fixed;
                        left: 50%;
                        transform: translateX(-50%);
                    }

                    .total-block ul li p.txt {
                        display: none;
                    }

                    .mob-trip-summary-block {
                        display: block !important;
                    }

                    .submit-btn-cls,
                    .mob-no-of-passengers-list,
                    .price-details-block,
                    .static-mobile-description {
                        display: none;
                    }
                }

                .ma-block .row {
                    padding-bottom: 180px;
                }

                .second-block.date-details .date_outer_div{
                        border: 1.5px solid var(--primary-color);
                        display: flex;
                        max-width: 369px;
                        border-radius: 999px;
                        padding: 8px 24px;
                        justify-content: space-between;
                        align-items: center;
                        color: var(--primary-color);
                        font-size: 20px;
                }
                .second-block.date-details .date_outer_div .first, .second-block.date-details .date_outer_div .second {
                    display: flex;
                    align-items: center;
                    width: 134px;
                    justify-content: space-between;
                }
                .second-block.date-details .date_outer_div .first .image-module, .second-block.date-details .date_outer_div .second .image-module {
                    position: relative;
                    width: 20px;
                    height: 20px;
                }
                .second-block.date-details .date_outer_div .image-module input {
                    width: 20px;
                    height: 20px;
                    position: absolute;
                    left: 0;
                    opacity: 0;
                }
                .second-block.date-details .date_outer_div .text-block p:first-child {
                    font-size: 12px;
                    line-height: 18px;
                    color: var(--primary-color);
                    margin: 0;
                    text-align: center;
                }
                .second-block.date-details .date_outer_div .text-block p:last-child {
                    font-size: 16px;
                    line-height: 20px;
                    margin: 0;
                    color: #808080;
                }
            </style>

            <div class="second-block date-details">
                <p class="date_tle">DATE</p>
                <p class="booking-dates">If you would like to extend your stay, please contact our Sales team at <a href="mailto:travel@keithprowsetravel.com">travel@keithprowsetravel.com</a></p>
                <div class="date_outer_div">
                    <div class="first">
                        <div class="image-module">
                            <img src="{{ asset('/css/booking/icon/calendar.svg') }}" alt="icon">
                            <input type="text" id="dateRange-hidden" placeholder="Select Date Range">
                        </div>
                        <div class="text-block">
                            <p>Check-in</p>
                            <p>{{ \Carbon\Carbon::parse($tour->date_from)->format('d M y') }}</p>
                        </div>
                    </div>
                    <div>
                        -
                    </div>
                    <div class="second">
                        <div class="text-block">
                            <p>Check-out</p>
                            <p>{{ \Carbon\Carbon::parse($tour->date_to)->format('d M y') }}</p>
                        </div>
                        <div class="image-module">
                            <img src="{{ asset('/css/booking/icon/calendar.svg') }}" alt="icon"> 
                        </div>
                    </div>

                    <!-- <div class="check_text_dflex check_text_div">
                        <div class="checkintext">Check-in</div>
                        <div class="checkouttext">Check-out</div>
                    </div>
                    <div class="check_text_dflex">
                        <div class="checkindate">{{ \Carbon\Carbon::parse($tour->date_from)->format('D, d M y') }}</div>
                        <div class="dateslash">-</div>
                        <div class="checkoutdate">{{ \Carbon\Carbon::parse($tour->date_to)->format('D, d M y') }}</div>
                    </div> -->

                </div>
            </div>

            <style>
                 .hotel-more-info-popup {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: #000000BF;
                    width: 100%;
                    height: 100%;
                    padding: 0rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 1111 !important;
                }
                .hotel-more-info-popup .hotel-more-info-contain {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100%;
                    width: 100%;
                    position: relative;
                }
                .hotel-more-info-popup .hotel-more-info-contain .hotel-more-info-block {
                    max-width: 740px;
                    width: 86%;
                    padding: 58px 32px;
                    background: #ffffff;
                    position: relative;
                    box-sizing: border-box;
                    border-radius: 16px;
                }
                .hotel-more-info-popup .hotel-more-info-contain .hotel-more-info-block .info-body {
                    max-height: 600px !important;
                    overflow-y: auto;
                    overflow-x: hidden;
                    padding-top: 20px;
                }
                .hotel-more-info-popup .hotel-more-info-contain .hotel-more-info-block .info-body .hotel-close-button {
                    position: absolute;
                    top: 25px;
                    right: 25px;
                    cursor: pointer;
                }
                .hotel-more-info-popup .info-body h4 {
                    font-family: "Begum-Medium";
                    text-transform: uppercase;
                    font-weight: 500;
                    font-size: 32px;
                    line-height: 40px;
                    color: #f35b15;
                    margin: 0px 0px 20px 0px;
                }
                .ma-block .row .left-col .accommodation-detail .hotel-more-info-popup .info-body p.hotel-info {
                    font-family: "PP Neue Montreal Medium";
                    font-weight: 500;
                    color: #000;
                    font-size: 16px !important;
                    line-height: 24px !important;
                    margin-bottom: 10px !important;
                } 

                .hotel-more-info-contain h4 {
    line-height: 34px;
}

.ma-block .row .left-col .hotel-more-info-popup h4 {
    font-family: Begum-Medium;
    text-transform: uppercase;
    font-weight: 500;
    font-size: 32px;
    line-height: 44px;
    color: var(--primary-color);
    margin: 0 0 20px 0;
}

.ma-block .row .left-col .hotel-more-info-popup .info-body p, .ma-block .row .left-col .hotel-more-info-popup .info-body ul li {
    font-family: "PP Neue Montreal Medium";
    font-weight: 500;
    color: #000;
    font-size: 16px;
    line-height: 24px;
    letter-spacing: 0px;
}

.hotel-more-info-popup .hotel-more-info-contain .amenities-container, .hotel-more-info-popup .hotel-more-info-contain {
    margin-top: 20px;
}
.default-close-zoom img{cursor: pointer;}
.hotel-zoom-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}
.default-close-zoom{
    position: absolute;
    top: 20px;
    right: 10px;
    font-size: 30px;
    color: var(--primary-color) !important;
    cursor: pointer;
    font-size: 60px !important;
    width: 50px !important;
}
.default-zoom-slider {width: 80%;}
.default-zoom-slider .slick-list { max-height: 96vh;}
.hotel-zoom-overlay .default-zoom-slider .slick-slide > div > div{height: 600px;}
.hotel-zoom-overlay .default-zoom-slider img{width: 100%;height: 100%;object-fit: cover;}
.hotel-image-popup-block > div > div > div > div > div{height: 320px;}
.hotel-zoom-overlay .default-zoom-slider .slick-next:before,.hotel-zoom-overlay .default-zoom-slider .slick-prev:before{font-size:50px;}
.hotel-zoom-overlay .default-zoom-slider .slick-prev{left: -55px;}
@media only screen and (max-width: 1024px) {
    .hotel-zoom-overlay .default-zoom-slider .slick-next:before,.hotel-zoom-overlay .default-zoom-slider .slick-prev:before{font-size:30px;}
    .hotel-zoom-overlay .default-zoom-slider .slick-prev{left: -35px;}
}@media only screen and (max-width: 767px) {.amenity_row{flex-flow: column;}}
.ma-block .row .left-col .contain .accommodation-details-hover{ position: absolute; top: 41px; left: 0; max-width: 425px; z-index: 999;}
.hotel-image-popup-block .slick-slide{position: relative;}

</style>
            <!-- <- Rooming -->
            @if($tour->accommodationInventoryTours()->count() > 0)
            <div class="second-block rme-det">
                <div class="accommodation-detail">
                    <p class="acm_title">ACCOMMODATION</p>
                    @php //dd($tour->repository->getHotels()); @endphp
                    @foreach($tour->repository->getHotels() as $hotelData)
                        @php $hotel = $hotelData['hotel']; @endphp
                        <div class="locate" x-data="{ open: false }">
                            @php $imagePath = asset($hotel->image_url ?? $hotel->gallery()->first()?->file_path ?? ''); @endphp
                            @if(!empty($imagePath))
                                <div class="image">
                                    <img src="{{ asset($imagePath) }}" alt="{{ $hotel->name }}" title="{{ $hotel->name }}" class="default-hotel-trigger-popup">
                                </div>
                            @endif
                            <div class="text-block">
                                <h5 class="hotel-info">{{ $hotel->name }}</h5>
                                <p class="hotel-info">{{ $hotel->accommodationtype?->name }}</p>
                                <p class="hotel-info">{{ $hotelData['type'] ?? '' }} </p>
                                <p class="hotel-info">{{ $hotelData['board'] ?? '' }} </p>
                                <p class="hotel-more-info"><a href="#" @click.prevent="open = true">More information</a></p>
                            </div>

                            <div class="hotel-more-info-popup" x-show="open" x-cloak x-transition @click.self="open = false">
                                <div class="hotel-more-info-contain">
                                    <div class="hotel-more-info-block">
                                        <div class="info-body">
                                            <div class="hotel-close-button" @click="open = false">
                                                    <img src="{{ asset('css/booking/icon/Close-Button.svg') }}" alt="Close">
                                                </div>
                                            <h4 class="hotel-info">{{ $hotel->name }}</h4>
                                            <p class="hotel-info">{{ $hotel->address }}</p>
                                            <div wire:ignore>
                                                @if(!empty($hotel->gallery) && count($hotel->gallery))
                                                    <div class="hotel-image-popup-block">
                                                        @foreach($hotel->gallery as $photo)
                                                            <div><img class="hotel-zoomable-image" src="{{ asset($photo->file_path) }}" alt="{{ $hotel->name }}" loading="lazy">
                                                            <div class="zoom__img_icon"><img src="{{ asset('css/booking/icon/zoom-in-fixed-svgrepo-com.svg') }}" alt="zoom icon"></div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="hotel-zoom-overlay">
                                                <div class="default-zoom-slider">
                                                    @foreach($hotel->gallery as $photo)
                                                        <div><img src="{{ asset($photo->file_path) }}" alt="{{ $hotel->name }}" loading="lazy"></div>
                                                    @endforeach
                                                </div>
                                                <span class="default-close-zoom"><img src="{{ asset('css/booking/icon/Close-Button.svg') }}" alt="zoom icon"></span>
                                            </div>
                                            <div wire:ignore class="default-amenities-container">
                                                @if(!empty($hotel->amenities) && count($hotel->amenities))
                                                    <div class="amenity_row">
                                                        @foreach($hotel->amenities as $item)
                                                            <div class="col-md-3 col-sm-4 col-6 mb-3">
                                                                <div class="amenity-icon d-flex align-items-center border rounded overflow-hidden p-3">
                                                                    @if($item->image_url && !empty($item->image_url))
                                                                        <img src="{{ asset($item->image_url) }}" class="img-fluid rounded me-3" style="max-width: 24px; max-height: 24px;" alt="{{ $item->name }}">
                                                                    @endif
                                                                    <h5 class="mb-0">{{ $item->name }}</h5>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        </div>
                                                @endif
                                            </div>
                                            <div class="hotel-description"><div>{!! $hotel->description !!}</div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="pt20">Choose your preferred bedding configuration for each room</p>
                <div class="inner-block">
                    <div class="left-col">
                        <h6>Number of rooms</h6>
                    </div>
                    <div class="right">
                        <div class="inn">
                            <span class="Min" wire:click="removeRoom"><img
                                    src="{{ asset('css/booking/icon/minus.svg') }}" alt="minus"></span>
                            <span class="No"><span>|</span> <span class="text">{{ count($this->rooms) }}</span>
                                <span>|</span></span>
                            <span class="Max" wire:click="addRoom"><img src="{{ asset('css/booking/icon/Plus.svg') }}"
                                    alt="minus"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="third-block">
                @for($x = 0, $xMax = count($rooms); $x < $xMax; $x++)
                    <div class="first-bl" wire:key="{{Str::random()}}">                        
                        <div class="inn">
                            <h6>Room {{ $x + 1 }}</h6>
                            <div class="information-hover" data-action="hover" data-target="accommodation-{{$hotel->id}}">
                                <img src="{{ asset('css/booking/icon/Icon.svg') }}" alt="tip-img">
                            </div>
                            <div class="accommodation-details-hover accommodation-{{$hotel->id}}">
                                <div class="contain">
                                    <div class="first-block">
                                        <h6>Hotel details</h6>
                                        <div class="full">
                                            <div class="left-col">
                                                <img class="package-image" src="{{ asset($hotel->image_url) }}" alt="featured-img">
                                            </div>
                                            <div class="right-col">
                                                <p>{!! $hotel->description !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-field" wire:key="{{Str::random()}}">
                            <select wire:model="rooms.{{$x}}.room" name="bedding_configuration">
                                @foreach($this->tour->repository->getRooms($selectedHotel) as $id => $name)
                                    <option value="{{$id}}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error("rooms.$x.room") <label class="error-label">{{ $message }}</label> @enderror
                        </div>
                    </div>
                @endfor
            </div>
            @endif
            <div class="submit-btn-cls">
                <div class="inner">
                    <input type="submit" class="submit-btn" wire:click="proceed" value="Proceed">
                </div>
            </div>
            @error('common')
                <div class="submit-btn-cls add-on">
                    <div class="inner">
                        <span style="color: red">{{ $message }}</span>
                    </div>
                </div>
            @enderror
        </div>
    </div>

    <div class="right-col">
        <!-- <h3 class="ytd_bold_class">Your trip details</h3> -->
        <h3 class="event-name event_color_class">{{ $tour->event?->name }}</h3>
        <div class="contain">
            <x-customer.booking.simple.package-details :booking="$booking" :tour="$tour">
                <div class="submit-btn-cls">
                    <div class="inner">
                        <input type="submit" class="submit-btn" wire:click="proceed" value="Proceed">
                    </div>
                </div>
            </x-customer.booking.simple.package-details>
        </div>
    </div>
</div>

<script>

    jQuery(document).on('click', '.hotel-more-info-popup .hotel-close-button,.hotel-more-info-popup .cancel', function () {
        jQuery(this).closest('.text-block').find('.hotel-more-info-popup').css('visibility', 'hidden')
    })


    jQuery('.mob-trip-summary-block .submit-btn-cls .submit-btn').click(function () {
        jQuery('.third-col .submit-btn-cls .submit-btn').trigger('click')
    })
    jQuery('.second-block.tra-det .Min,.second-block.tra-det .Max').click(function () {
        setTimeout(() => {
            jQuery('.mob-static-tip-sum .price').text(jQuery('.third-col .tot-price').text())
            jQuery('.mob-trip-summary-block li:eq(0) .price').text(jQuery('.third-col .pkg-price').text());
            jQuery('.mob-trip-summary-block li:eq(1) .price').text(jQuery('.third-col .sng-price').text());
            jQuery('.mob-trip-summary-block li:eq(2) .price').text(jQuery('.third-col .tax-price').text());
            jQuery('.mob-trip-summary-block .total-block .price').text(jQuery('.third-col .tot-price').text());

            jQuery('.mob-no-of-passengers-list p:first').text(jQuery('.second-block.tra-det .right .No .text').text() + ' Passengers')
        }, 2000);
    })
    document.addEventListener('livewire:load', function () {
        runSelectClassUpdate();
    });
    
    document.addEventListener('livewire:update', function () {
        runSelectClassUpdate();
    });
    
    function runSelectClassUpdate() {
        jQuery('select[name="bedding_configuration"]').each(function(){
            var comtext = jQuery(this).find('option:selected').text().trim().toLowerCase();
            if(comtext.includes('twin')) {
                jQuery(this).closest('.form-field').addClass('twn-cls');
            } else if(comtext.includes('single')) {
                jQuery(this).closest('.form-field').addClass('sng-cls');
            } else {
                jQuery(this).closest('.form-field').addClass('dbl-cls');
            }
        });
    }

    /*function runSelectClassUpdate() {
        jQuery('select[name="bedding_configuration"]').each(function(){
            var selectedValue = jQuery(this).val(); 
            if (selectedValue == 2) {
                jQuery(this).closest('.form-field').addClass('dbl-cls');
            } else if (selectedValue == 1) {
                jQuery(this).closest('.form-field').addClass('sng-cls');
            } else if (selectedValue == 'twin') {
                jQuery(this).closest('.form-field').addClass('twn-cls');
            }else{
                jQuery(this).closest('.form-field').addClass('dbl-cls');
            }
        });
    }*/



</script>
