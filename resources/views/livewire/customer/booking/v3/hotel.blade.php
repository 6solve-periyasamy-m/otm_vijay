<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="2">
    <section class="package-container">
        <div class="container">
            <div class="column left">
                <x:customer.booking.v3.tour-info :tour="$tour" :booking="$booking" :selectedCurrency="$selectedCurrency" />
                <div class="accommodation-detail ">
                    <h2 class="sub-heading-2-p">ACCOMMODATION DETAILS</h2>
                    <p>Review and customise your accommodation details. Selecting a different hotel or room type may impact the total cost.</p>
                    <h6 class="sub-heading-6">DEFAULT HOTEL INCLUDED IN THIS PACKAGE</h6>
                    @php //dd($defaultdHotel['hotel']->accommodationtype?->name); @endphp
                    <div class="locate">
                        @if($defaultHotel)                            
                            @php $imagePath = public_path($defaultHotel['hotel']->image_url ?? ''); @endphp
                            @if(!empty($defaultHotel['hotel']->image_url) && file_exists($imagePath))
                                <div class="image">
                                    <img src="{{ asset($defaultHotel['hotel']->image_url) }}" alt="{{ $defaultHotel['hotel']->name }}" title="{{ $defaultHotel['hotel']->name }}">
                                </div>
                            @endif
                            <div class="text-block">
                                <h6>{{ $defaultHotel['hotel']->name }}</h6>
                                <p>{{ $defaultHotel['hotel']->accommodationtype?->name }}</p>
                                <p>{{ $defaultHotel['type'] ?? '' }} </p>
                                <p>{{ $defaultHotel['board'] ?? '' }} </p>
                            </div>
                        @endif
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
                                <input type="text" id="dateRange-hide" placeholder="Select Date Range">
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
                    @php //dd($this->tour->repository->getRooms($selectedHotel)) @endphp 
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
                    @php dd(count($rooms)); @endphp
                    <div class="room-listing-module">
                        @for($x = 0, $xMax = count($rooms); $x < $xMax; $x++)
                            <div class="single-room">
                                <h6>Room {{ $x + 1 }}</h6>
                                <p>Lorem Ipsum is simply dummy</p>
                                <ul>
                                    <li>Size of room: 52 sq m</li>
                                    <li>Size of bed: 1 king bed</li>
                                </ul>
                                <p>Number of guests</p>
                                <div class="guest-module">
                                    <div>1</div>
                                    <div class="active">2</div>
                                    <div>3</div>
                                </div>
                            </div>
                        @endfor
                        <!-- <div class="single-room">
                            <h6>Room 1</h6>
                            <p>Lorem Ipsum is simply dummy</p>
                            <ul>
                                <li>Size of room: 52 sq m</li>
                                <li>Size of bed: 1 king bed</li>
                            </ul>
                            <p>Number of guests</p>
                            <div class="guest-module">
                                <div>1</div>
                                <div class="active">2</div>
                                <div>3</div>
                            </div>
                            <p>Bed configuration</p>
                            <div class="bed-configuration active">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Double
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Twin-bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/twin-bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Twin
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Triple-Bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Triple-Bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Triple
                                </div>
                            </div>
                            <button type="button" class="include-button">INCLUDE</button>
                        </div>
                        <div class="single-room">
                            <h6>Room 2</h6>
                            <p>Lorem Ipsum is simply dummy</p>
                            <ul>
                                <li>Size of room: 52 sq m</li>
                                <li>Size of bed: 1 king bed</li>
                            </ul>
                            <p>Number of guests</p>
                            <div class="guest-module">
                                <div>1</div>
                                <div class="active">2</div>
                                <div>3</div>
                            </div>
                            <p>Bed configuration</p>
                            <div class="bed-configuration active">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Double
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Twin-bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/twin-bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Twin
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Triple-Bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Triple-Bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Triple
                                </div>
                            </div>
                            <button type="button" class="include-button">INCLUDE</button>
                        </div>
                        <div class="single-room">
                            <h6>Room 3</h6>
                            <p>Lorem Ipsum is simply dummy</p>
                            <ul>
                                <li>Size of room: 52 sq m</li>
                                <li>Size of bed: 1 king bed</li>
                            </ul>
                            <p>Number of guests</p>
                            <div class="guest-module">
                                <div class="active">1</div>
                                <div>2</div>
                                <div>3</div>
                            </div>
                            <p>Bed configuration</p>
                            <div class="bed-configuration active">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Double
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Twin-bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/twin-bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Twin
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Triple-Bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Triple-Bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Triple
                                </div>
                            </div>
                            <button type="button" class="include-button">INCLUDE</button>
                        </div> -->
                    </div>
                </div>
                <div class="hotel">
                    <h6 class="sub-heading-6">HOTEL</h6>
                    <p>Your package includes a 3-night stay at Pan Pacific Melbourne, a 5-star hotel. If you’d like to
                        upgrade, please select from one of the other options below.</p>

                    <div class="hotel-listing">
                        <div class="single-hotel">
                            <div class="hotel-image-block">
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_2.webp') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_3.jpg') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_4.jpg') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_5.jpg') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/hotel_1.jpg') }}" alt="hotel-images">
                                </div>
                            </div>
                            <div class="hotel-block">
                                <h6>Pan Pacific, Melbourne</h6>
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
                        <div class="single-hotel">
                            <div class="hotel-image-block">
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_2.webp') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_3.jpg') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_4.jpg') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_5.jpg') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/hotel_1.jpg') }}" alt="hotel-images">
                                </div>
                            </div>
                            <div class="hotel-block">
                                <h6>The Langham, Melbourne</h6>
                                <p>5 star</p>
                                <p>+ A$200</p>

                                <div class="room-type">
                                    <p>Room type</p>

                                    <select>
                                        <option>Deluxe room</option>
                                        <option>Basic room</option>
                                        <option>Deluxe room</option>
                                    </select>

                                    <p class="breakfast-note">Breakfast included daily</p>

                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>

                                    <button type="button" class="include-button">NOT AVAILABLE</button>

                                    <span class="not-available">Change travel dates above to check availability</span>

                                </div>
                            </div>
                        </div>
                        <div class="single-hotel">
                            <div class="hotel-image-block">
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_2.webp') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_3.jpg') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_4.jpg') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_5.jpg') }}"
                                         alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/hotel_1.jpg') }}" alt="hotel-images">
                                </div>
                            </div>
                            <div class="hotel-block">
                                <h6>The Westin, Melbourne</h6>
                                <p>5 star</p>
                                <p>+ A$300</p>

                                <div class="room-type">
                                    <p>Room type</p>

                                    <select>
                                        <option>Deluxe room</option>
                                        <option>Basic room</option>
                                        <option>Deluxe room</option>
                                    </select>

                                    <p class="breakfast-note">Breakfast included daily</p>

                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>

                                    <button type="button" class="include-button active">Upgrade</button>
                                </div>
                            </div>
                        </div>
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
                            <h2>QUARTER FINALS PACKAGE</h2>
                            <ul>
                                <li>21 Jan 25 - 24 Jan 25</li>
                                <li>Mens Semi Final Ticket</li>
                                <li>3 Nights, 5-Star Accommodation</li>
                                <li>Exclusive function & more</li>
                            </ul>
                        </div>
                        <div class="additional-inclusions">
                            <h6 class="sub-heading-6">ADDITIONAL INCLUSIONS</h6>
                            <div class="select-currency">
                                <div class="single">
                                    <p>Select-currency</p>
                                    <select>
                                        <option>AUD</option>
                                        <option>GBP</option>
                                        <option>USD</option>
                                    </select>
                                </div>
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
                    <button type="button" class="next-button">
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