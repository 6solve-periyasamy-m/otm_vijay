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
                    <p class="dollar">From {{ f_currency($tour->base_price_per_person) }} / person twin share</p>
                </div>
            </div>
            <p class="psg-det">PASSENGER DETAILS</p>
            <div class="top-form-contain">
                <div class="form-field">
                    <input type="email" wire:model="lead.email_address" placeholder="Email*" required>
                    @error('lead.email_address') <label class="error-label">{{ $message }}</label> @enderror
                </div>
            </div>
            <!-- <- Additional Travellers -->
            <div class="second-block tra-det">
                <p>TRAVELLER/S</p>
                <div class="inner-block">
                    <div class="left-col">
                        <h6 class="subheading">Adults</h6>
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

                @media only screen and (min-width:768px) {
                    .bottom-foot {
                        position: relative;
                        bottom: 164px;
                    }
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
                    cursor: pointer;
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
                <p>DATE</p>
                <p class="booking-dates">If you would like to extend your stay, please contact our Sales team at <a href="mailto:travel@keithprowsetravel.com">travel@keithprowsetravel.com</a></p>
                <div class="date_outer_div">
                    <div class="first">
                        <div class="image-module">
                            <img src="{{ asset('/css/booking/icon/calendar.svg') }}" alt="icon" style="display: none;">
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
                            <img src="{{ asset('/css/booking/icon/calendar.svg') }}" alt="icon"  style="display: none;"> 
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

            <!-- <- Rooming -->
            <div class="second-block rme-det">

                <p>ACCOMMODATION</p>
                @foreach($tour->repository->getHotels() as $hotelData)
                    @php $hotel = $hotelData['hotel']; @endphp
                    <div wire:ignore class="hotel-details">
                        <h6 class="hover-h-cls">{{ $hotel->name }} @if($hotelData['type'] !== null) - {{ $hotelData['type'] }} @endif</h6>
                        <div class="information-hover" data-action="hover" data-target="accommodation-{{$hotel->id}}">
                            <img src="{{ asset('css/booking/icon/Icon.svg') }}" alt="tip-img">
                        </div>
                        <div class="accommodation-details-hover accommodation-{{$hotel->id}}">
                            <div class="contain">
                                <div class="first-block">
                                    <h6>Hotel details</h6>
                                    <div class="full">
                                        <div class="left-col">
                                            <img class="package-image" src="{{ asset($hotel->image_url) }}"
                                                alt="featured-img">
                                        </div>
                                        <div class="right-col">
                                            <p>{!! $hotel->description !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   <div class="hotel-board">{{ $hotelData['board'] }}</div>
                @endforeach
                {{-- Hidden For Future Use --}}
                {{--
                <div class="form-field">
                    <select wire:model="selectedHotel" name="rooming_configuration">
                        @foreach($this->tour->repository->getHotels() as $id => $name)
                            <option value="{{$id}}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                --}}
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


                <!-- <div class="contain">
                    <p>
                        Can't find what you're looking for?
                        <a href="https://www.kpt.com.au/contact-us/" target="_blank">Get in touch</a>
                        for a custom package.
                    </p>
                </div> -->
            </div>
            <div class="third-block">
                @for($x = 0, $xMax = count($rooms); $x < $xMax; $x++)
                    <div class="first-bl" wire:key="{{Str::random()}}">
                        <p>Choose your preferred bedding configuration for each room</p>
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
                        <!-- <div class="form-field">
                            <select class="travellers-select" wire:model.live="rooms.{{$x}}.travellers" name="pax_number">
                                <option value="1">1 Traveller</option>
                                <option value="2">2 Travellers</option>
                            </select>
                            @error("rooms.$x.travellers") <label class="error-label">{{ $message }}</label> @enderror
                        </div> -->
                    </div>
                @endfor
            </div>
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
        <h3 class="ytd_bold_class">Your trip details</h3>
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

<!-- <div class="mob-trip-summary-block">
  <div class="block-container">
      <div class="Inner-container">
        <div class="mob-static-tip-sum">
         <h4>Trip Summary</h4>
         <div class="mob-static-see-more"><p>SEE MORE<p></div>     
        </div>
        <div class="static-mobile-description">
              <h3>British & Irish Lions Tour 2025 Single Game Package - Brisbane</h3>
              <p class="date">18 July, 2025 - 20 July, 2025</p>
              <p class="points">Capri by Fraser Brisbane - 2 nights</p>
              <p class="points">Category 3 Tickets — Test 1 - Wallabies v Lions</p>
              <p class="points">Capri by Fraser Brisbane - 2 nights</p>
              <p class="points">Category 3 Tickets — Test 1 - Wallabies v Lions</p>
        </div>
        <div class="mob-no.of-passengers-list">
           <p>2 Passengers</p>
        </div>
        <div class="price-details-block">
            <ul>
                <li>
                <p class="txt">Package Price</p>
                <p class="price">A$2,000</p>
                </li>
                <li>
                <p class="txt">Single Supplement</p>
                <p class="price">A$0</p>
                </li>
                <li>
                <p class="txt">GST</p>
                <p class="price">A$200</p>
                </li>
            </ul>
        </div>
        <div class="total-block">
            <ul>
                <li>
                <p class="txt">Total</p>
                <p class="price">A$2000</p>
                </li>
            </ul>
        </div>
        <div class="submit-btn-cls">
            <div class="inner">
                <input class="submit-btn" wire:click="checkout" type="submit" value="Checkout">
            </div>
        </div>
      </div>
  </div>
</div> -->

{{--<div class="mob-trip-summary-block_outerdiv" wire:key="{{Str::random()}}">
    <div class="mob-trip-summary-block">
        <div class="block-container">
            <div class="Inner-container">
                <div class="mob-static-tip-sum">
                    <h4>Trip Summary</h4>
                    <p class="price" wire:key="{{Str::random()}}">{{ f_currency($booking->repository->getTotalCost()) }}</p>
                    <div class="mob-static-see-more mobile_seemore">
                        <p>SEE MORE
                        <p>
                    </div>
                </div>
                <div class="static-mobile-description click_popup_div">
                    <h3 class="event_name">{{ $tour->name }}</h3>
                    <p class="date">{{ $tour->date_from?->format('d M Y') }} - {{ $tour->date_to?->format('d M Y') }}</p>
                    @foreach($tour->repository->getInclusions(4) as $inclusion)
                        <p class="points">{{ $inclusion }}</p>
                    @endforeach
                </div>
                <div class="mob-no-of-passengers-list">
                    <p>{{ $this->getTravellerCount() }} Passengers</p>
                </div>
                <div class="price-details-block">
                    <ul>
                        <li>
                            <p class="txt">Package Price</p>
                            <p class="price">{{ f_currency($booking->repository->getBasePrice()) }}</p>
                        </li>
                        <li>
                            @php $singleOccupancy = $booking->repository->getSingleOccupancyAmount(); @endphp
                            <p class="txt">Single Supplement</p>
                            <p class="price">{{ f_currency($singleOccupancy) }}</p>
                        </li>

                        @if($booking->repository->getTaxes() !== null)
                            <li>
                                <p class="txt">{{ $tour->taxBracket()->name }}</p>
                                <p class="price">{{ f_currency($booking->repository->getTaxes()) }}</p>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="total-block">
                    <ul>
                        <li>
                            <p class="txt">Total</p>
                            <p class="price">{{ f_currency($booking->repository->getTotalCost()) }}</p>
                        </li>
                    </ul>
                </div>
                <div class="mob-static-see-more desktop_seemore">
                    <p>SEE MORE
                    <p>
                </div>
                <div class="submit-btn-cls">
                    <div class="inner">
                        <input type="submit" class="submit-btn" wire:click="proceed" value="Proceed">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>--}}
<script>
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
