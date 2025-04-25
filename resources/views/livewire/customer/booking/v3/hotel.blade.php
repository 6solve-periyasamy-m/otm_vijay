<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="2">
    <section class="package-container">
        <div class="container">
            <div class="column left">
                <x:customer.booking.v3.tour-info :tour="$tour" :booking="$booking" :selectedCurrency="$selectedCurrency" />
                <div class="accommodation-detail ">
                    <h2 class="sub-heading-2-p">ACCOMMODATION DETAILS</h2>
                    <p>Review and customise your accommodation details. Selecting a different hotel or room type may
                        impact the total cost.</p>
                    <h6 class="sub-heading-6">DEFAULT HOTEL INCLUDED IN THIS PACKAGE</h6>
                    @php $default = $this->getDefaultHotel(); @endphp
                    <div class="locate">
                        @php $imagePath = public_path($default->image_url ?? ''); @endphp
                        @if(!empty($default->image_url) && file_exists($imagePath))
                            <div class="image">
                                <img src="{{ asset($default->image_url) }}" alt="{{ $default->name }}" title="{{ $default->name }}">
                            </div>
                        @endif
                        <div class="text-block">
                            <h6>{{ $default->name }}</h6>
                            <p>{{ $default->accommodationtype?->name }}</p>
                            <p>{{ $default['type'] ?? '' }} </p>
                            <p>{{ $default['board'] ?? '' }} </p>
                        </div>
                    </div>
                </div>
                <div class="booking-dates">
                    <h6 class="sub-heading-6">BOOKING DATES</h6>
                    <p>Change your check-in and check-out dates to extend your stay by adding extra nights before or
                        after the included 3-night package.</p>

                    <p class="mod">Extend your stay</p>

                    <div class="check-in-check-out">
                        <div class="first">
                            <div class="image-module">
                                <img src="{{ asset('icons/checkin.svg') }}" alt="icon">
                                <input type="text" id="dateRange-hidden" placeholder="Select Date Range">
                            </div>
                            <div class="text-block">
                                <p>Check-in</p>
                                <p>{{ $tour->date_from?->format('d M y') }}</p>
                            </div>
                        </div>
                        <div>
                            -
                        </div>
                        <div class="second">
                            <div class="image-module">
                                <img src="{{ asset('icons/checkin.svg') }}" alt="icon">
                            </div>
                            <div class="text-block">
                                <p>Check-out</p>
                                <p>{{ $tour->date_to?->format('d M y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="room-configuration">

                    <h6 class="sub-heading-6">ROOM CONFIGURATION</h6>

                    <div class="no-of-travellers">
                        <p>Number of rooms you wish to book</p>
                        <div class="quantity">
                            <span class="minus"><img src="{{ asset('icons/Minus.svg') }}" alt="minus"></span>
                            <span>|</span>
                            <span class="value">{{ count($this->rooms) }}</span>
                            <span>|</span>
                            <span class="plus"><img src="{{ asset('icons/Plus.svg') }}" alt="plus"></span>
                        </div>
                    </div>
                </div>
                <div class="room-selection">
                    <h6 class="sub-heading-6">ROOM SELECTION</h6>
                    <p>If you would like to upgrade, select from the upgrade options below.</p>
                    <p>Then, choose your preferred bedding configuration for each room.</p>
                    @php
                        $bedTypes = [
                            ['count' => 1, 'label' => 'Double'],
                            ['count' => 2, 'label' => 'Twin'],
                            ['count' => 3, 'label' => 'Triple'],
                        ];
                    @endphp
                    <div class="showcase">
                        @foreach($bedTypes as $type)
                            <div class="single">
                                <div>
                                    @for ($i = 0; $i < $type['count']; $i++)
                                        <img src="{{ asset('icons/Bed.svg') }}" alt="bed">
                                    @endfor
                                </div>
                                <p>{{ $type['label'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="room-listing-module">
                        @for($x = 0, $xMax = count($rooms); $x < $xMax; $x++)
                            <div class="single-room">
                                <h6>Room {{ $x + 1 }}</h6>
                                <div class="roomdesc" data-room-index="{{ $x }}"></div>
                                <p>Number of guests</p>
                                <div class="guest-module">
                                    @for($i = 1, $iMax = 3; $i <= $iMax; $i++)
                                        <label>
                                            <input
                                                type="radio"
                                                class="guest-radio"
                                                
                                                name="rooms[{{ $x }}][travellers]"
                                                value="{{ $i }}"
                                                data-room-index="{{ $x }}"
                                            >
                                            {{ $i }}
                                        </label>
                                    @endfor
                                    @error("rooms.$x.travellers") <label class="error-label">{{ $message }}</label> @enderror
                                </div>
                                <p>Bed configuration</p>
                                <div class="form-field" wire:key="{{ Str::random() }}">
                                    @php //dd($this->tour->repository->getBookingRooms($selectedHotel)); @endphp
                                    
                                    @foreach($this->tour->repository->getBookingRooms($selectedHotel) as $id => $item)
                                        @php $bedType = $item['name']; @endphp
                                        <label>
                                            <input
                                               
                                                type="radio"
                                                class="bed-radio disabled-bed"
                                                name="rooms[{{ $x }}][room]"
                                                value="{{ $id }}"
                                                data-room-index="{{ $x }}"
                                                data-room-id="{{ $id }}"
                                                data-bed-type="{{ $bedType }}"
                                                data-bed-occupancy="{{ $item['occupancy'] }}"
                                                data-bed-desc="{!! htmlspecialchars($item['room_desc']) !!}"                                                
                                                disabled
                                            >
                                            {{ $bedType }}
                                        </label>
                                    @endforeach
                                </div>
                                <button type="button" class="include-button">INCLUDE</button>
                            </div>
                            @error("rooms.$x.room") <label class="error-label">{{ $message }}</label> @enderror
                            <label class="bed-error text-danger" style="display:none;"></label>
                        @endfor
                    </div>
                </div>
                <div class="hotel">
                    <h6 class="sub-heading-6">HOTEL</h6>                   
                    <p>Your package includes a 3-night stay at Pan Pacific Melbourne, a 5-star hotel. If you’d like to
                        upgrade, please select from one of the other options below.</p>

                    <div class="hotel-listing">
                        @foreach($this->tour->repository->getHotels() as $id => $arrHotel)
                            @php $hotel = $arrHotel['hotel']; @endphp
                            <div class="single-hotel">
                                <div class="hotel-image-block">
                                    @foreach($hotel->gallery as $photo)
                                        <div><img src="{{ asset($photo->file_path) }}" alt="{{ $hotel->name }}"></div>
                                    @endforeach
                                </div>
                                <div class="hotel-block">
                                    <h6>{{ $hotel->name }}</h6>
                                    <p>3 star</p>
                                    <p>+A$0</p>

                                    <div class="room-type">
                                        <p>Room type</p>
                                        <select>
                                            <option>Deluxe room</option>
                                            <option>Basic room</option>
                                            <option>Deluxe room</option>
                                        </select>
                                        <p class="breakfast-note">Breakfast included daily</p>
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                                        <button type="button" class="include-button">INCLUDED</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="column right">
                <div class="package-details">
                    <div class="contain">
                        <div class="top-module">
                            <h4 class="sub-heading-4">Package details</h4>
                            <div class="hide-package-detail">Hide package details</div>
                        </div>
                        <div class="image-block">
                            <img src="{{ asset('images/sportEvent.png') }}" alt="package-details">
                        </div>
                        <div class="base-package">
                            <h6 class="sub-heading-6">BASE PACKAGE</h6>
                            <h2>{{ $tour->name }}</h2>
                            <ul>
                                <li>{{ $tour->date_from?->format('d M Y') }} - {{ $tour->date_to?->format('d M Y') }}</li>
                                @foreach($tour->repository->getInclusions() as $inclusion)
                                    <li>{{ $inclusion }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="additional-inclusions">
                            <h6 class="sub-heading-6">ADDITIONAL INCLUSIONS</h6>
                            <div class="select-currency">
                                @livewire("customer.booking.v3.currency-selector", ['currency' => $selectedCurrency], key('currency-selector'))
                                <div class="single">
                                    <p>Package price</p>
                                    <p>A$2,995</p>
                                </div>
                                <div class="single">
                                    <p>Number of packages - 5</p>
                                    <p>A$14,975</p>
                                </div>
                            </div>
                            <div class="added-nights">
                                <h5>Added nights</h5>
                                <div class="single">
                                    <p>
                                        <span>2 x Additional nights</span>
                                        <span>20 Jan - 25 Jan 2025</span>
                                    </p>
                                    <p>A$1,500</p>
                                </div>
                            </div>
                            <div class="room-upgrades">
                                <h5>Room upgrades</h5>
                                <div class="single">
                                    <p>Deluxe (Double)</p>
                                    <p>A$500</p>
                                </div>
                                <div class="single">
                                    <p>Deluxe (Twin)</p>
                                    <p>Price included</p>
                                </div>
                                <div class="single">
                                    <p>Deluxe (Double)</p>
                                    <p>Price included</p>
                                </div>
                            </div>
                            <div class="Hotel">
                                <h5>Hotel</h5>
                                <div class="single">
                                    <p>Pan Pacific, Melbourne</p>
                                    <p>Price included</p>
                                </div>
                            </div>
                            <div class="ticket-upgrades display-none">
                                <h5>Ticket upgrades</h5>
                                <div class="single">
                                    <p>Ticket alterations</p>
                                    <p>A$500</p>
                                </div>
                                <div class="single">
                                    <p>Additional ticket/s</p>
                                    <p>A$500</p>
                                </div>
                            </div>
                            <div class="additional-upgrades display-none">
                                <h5>Additional upgrades</h5>
                                <div class="single">
                                    <p>Melbourne Foodie Walking Tour</p>
                                    <p>A$150</p>
                                </div>
                            </div>
                            <div class="total">
                                <div class="single">
                                    <p>Total</p>
                                    <p>A$17,125</p>
                                </div>
                                <div class="single">
                                    <p>Starting package price</p>
                                    <p>$14,975</p>
                                </div>
                                <div class="single">
                                    <p>Customisation cost</p>
                                    <p>$500</p>
                                </div>
                            </div>
                        </div>
                        <div class="payment-method ">
                            <h6 class="sub-heading-6 display-none">PAYMENT METHOD</h6>
                            <div class="option-wrapper display-none">
                                <label class="radio-option">
                                    <input type="radio" name="payment" checked>
                                    <span class="custom-radio"></span>
                                    <span class="option-title">Pay in full</span>
                                </label>
                                <div class="price">A$17,125</div>
                            </div>
                            <div class="option-wrapper display-none">
                                <div>
                                    <label class="radio-option">
                                        <input type="radio" name="payment">
                                        <span class="custom-radio"></span>
                                        <span class="option-title">Pay a 50% deposit now, and the rest later</span>
                                    </label>
                                    <div class="option-subtext">
                                        The remaining balance of A$8,563 will be automatically charged to the same
                                        payment method on 24 June 2024
                                    </div>
                                </div>
                                <div class="price">A$8,563</div>
                            </div>

                            <div class="card-block display-none">
                                <div class="card-type active">
                                    <img src="{{ asset('icons/card.svg') }}" alt="Debit card">
                                    <p>Credit / Debit card</p>
                                </div>
                                <div class="card-type">
                                    <img src="{{ asset('icons/document-text.svg.svg') }}" alt="Direct Debit">
                                    <p>Invoice - Direct Debit</p>
                                </div>
                            </div>

                            <div class="payable-now">
                                <div class="single">
                                    <p>Payable now</p>
                                    <p>A$3,425</p>
                                </div>
                                <p>Balance A$13,700 payable by 14 Feb 2025</p>
                            </div>

                            <div class="email-quote">
                                <h6 class="sub-heading-6">EMAIL quote</h6>
                                <form style="display:none;">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email">
                                </form>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="next-button" wire:click="advance">
              <span>
                <span>NEXT</span>
                <img src="{{ asset('icons/Right-arrow-mod.svg') }}" alt="right-arrow">
              </span>
                    </button>
                    <span class="accomodation-travel-date-error">
                        <span>
                          <img src="{{ asset('icons/Noti-Icon.svg') }}" alt="icon">
                        </span>
                        <span>
                        Total number of travellers vs. the number of guests you have selected for rooms does not match - please update your room selection to proceed
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </section>
</x-customer.booking.v3.layout>
<script>
    jQuery(document).ready(function () {
        const No_of_Guests = parseInt("{{ $this->getTravellerCount() }}", 10);

        function calculateTotalGuests() {
            let total = 0;
            $('.guest-radio:checked').each(function () {
                total += parseInt($(this).val(), 10);
            });
            return total;
        }

        $('.guest-radio').on('change', function () {
            const roomIndex = $(this).data('room-index');
            const selectedGuests = parseInt($(this).val(), 10);
            const totalGuests = calculateTotalGuests();

            const $errorBlock = $('.accomodation-travel-date-error');
            const $errorMsg = $errorBlock.find('.error-msg');
            const error_text = 'Total number of travellers vs. the number of guests you have selected for rooms does not match - please update your room selection to proceed';
            console.log(`Total selected guests: ${totalGuests} / Expected: ${No_of_Guests}`);

            if (totalGuests > No_of_Guests) {
                $errorMsg.html(error_text);
                $errorBlock.fadeIn();
                $(this).prop('checked', false);
                return;
            }

            if (totalGuests !== No_of_Guests) {
                $errorMsg.html(error_text);
                $errorBlock.fadeIn();
            } else {
                $errorMsg.html('');
                $errorBlock.fadeOut();
            }

            const $bedRadios = $(`.bed-radio[data-room-index="${roomIndex}"]`);
            let matched = false;

            $bedRadios.each(function () {
                const occupancy = parseInt($(this).data('bed-occupancy'), 10);
                if (occupancy === selectedGuests) {
                    $(this).prop('disabled', false);
                    if (!matched) {
                        $(this).prop('checked', true).trigger('change');
                        matched = true;
                    }
                } else {
                    $(this).prop('disabled', true).prop('checked', false);
                }
            });
        });

        $('.bed-radio').on('change', function () {
            const roomIndex = $(this).data('room-index');
            const bedDesc = $(this).data('bed-desc');

            $(`.roomdesc[data-room-index="${roomIndex}"]`)
                .hide()
                .html(bedDesc)
                .fadeIn();
        });
        
    });
</script>