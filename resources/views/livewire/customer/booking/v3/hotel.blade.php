@php $noOfNights = $this->tour->repository->getTourNights(); @endphp
<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="2">
    <section class="package-container">
        <div class="container">
            <div class="column left">
                <x:customer.booking.v3.tour-info :tour="$tour" :booking="$booking" :selectedCurrency="$selectedCurrency" />
                <div class="accommodation-detail ">
                    <h2 class="sub-heading-2-p">ACCOMMODATION DETAILS</h2>
                    <p>Review and customise your accommodation details.Selecting a different hotel or room type may
                        impact the total cost.</p>
                    <h6 class="sub-heading-6">DEFAULT HOTEL INCLUDED IN THIS PACKAGE</h6>
                    @php $default = $this->getDefaultHotel()->component; @endphp
                    <div class="locate">
                        @php $imagePath = asset($default->image_url ?? $default->gallery()->first()?->file_path ?? ''); @endphp
                        @if(!empty($imagePath))
                            <div class="image">
                                <img src="{{ asset($imagePath) }}" alt="{{ $default->name }}" title="{{ $default->name }}">
                            </div>
                        @endif
                        <div class="text-block">
                            <h6>{{ $default->name }}</h6>
                            <p>{{ $default->accommodationtype?->name }}</p>
                            <p>{{ $this->getDefaultHotel()->roomType->name ?? '' }} </p>
                            <p>{{ $this->getDefaultHotel()->boardType->name ?? '' }} </p>
                        </div>
                    </div>
                </div>
                <div class="booking-dates">
                    <h6 class="sub-heading-6">BOOKING DATES</h6>
                    <p>Change your check-in and check-out dates to extend your stay by adding extra nights before or
                        after the included {{ $noOfNights == 1 ? 'night' : $noOfNights.'-nights' }} package.</p>

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
<!-- BREAKPOINT: Room Quantity -->
                <div class="room-configuration">
                    <h6 class="sub-heading-6">ROOM CONFIGURATION</h6>
                    <div class="no-of-travellers">
                        <p>Number of rooms you wish to book</p>
                        <div class="quantity">
                            <span class="minus" wire:click="removeRoom"><img src="{{ asset('icons/Minus.svg') }}" alt="minus"></span>
                            <span>|</span>
                            <span class="value">{{ count($this->rooms) }}</span>
                            <span>|</span>
                            <span class="plus" wire:click="addRoom"><img src="{{ asset('icons/Plus.svg') }}" alt="plus"></span>
                        </div>
                    </div>
                </div>
<!-- BREAKPOINT: Room Selection -->
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
                        @php
                            $bookingRooms = $this->tour->repository->getBookingRooms($selectedHotel);
                            $firstRoom = reset($bookingRooms);
                        @endphp
                        @for($x = 0, $xMax = count($rooms); $x < $xMax; $x++)
                            <div class="single-room" id="room-{{ $x }}">
                                <h6>Room {{ $x + 1 }}</h6>
                                <div class="roomdesc" data-room-index="">{!! $firstRoom['room_desc'] !!}</div>
                                <p>Number of guests</p>
                                <div class="guest-module">
                                    @for($i = 1, $iMax = 3; $i <= $iMax; $i++)
                                        <input
                                            type="radio"
                                            class="guest-radio"
                                            name="rooms[{{ $x }}][travellers]"
                                            wire:model="rooms.{{ $x }}.travellers"
                                            value="{{ $i }}"
                                            data-room-index="{{ $x }}"
                                            id="guest{{ $x }}_{{ $i }}"
                                        >
                                        <label for="guest{{ $x }}_{{ $i }}"> {{ $i }} </label>
                                    @endfor
                                    @error('rooms.' . $x . '.travellers') <label class="error-label">{{ $message }}</label> @enderror
                                </div>
                                <p>Bed configuration</p>
                                <div class="form-field" id="bed-config-{{ $x }}">
                                    @foreach($this->tour->repository->getBookingRooms($selectedHotel) as $id => $item)
                                        @php 
                                            $selectedRoomId = $rooms[$x]['room'] ?? null;
                                            $isSelected = $selectedRoomId == $id;
                                            $cls = $isSelected ? 'selected-bed' : '';
                                            $img = match($item['occupancy']) {
                                                1 => $isSelected ? 'icons/bed_1_selected.svg' : 'icons/bed_1.svg',
                                                2 => $isSelected ? 'icons/twin-bed-hover.svg' : 'icons/bed_2.svg',
                                                default => $isSelected ? 'icons/Triple-Bed-hover.svg' : 'icons/bed_3.svg',
                                            };
                                        @endphp
                                        <label class="bed-configuration-h {{$cls}}">
                                            <input
                                                type="radio"
                                                wire:model="rooms.{{$x}}.room"
                                                class="bed-radio"
                                                name="rooms[{{ $x }}][room]"
                                                value="{{ $id }}"
                                                data-room-index="{{ $x }}"
                                                data-bed-occupancy="{{ $item['occupancy'] }}"
                                                data-desc-id="desc-{{ $id }}"
                                                data-readonly="true" {{-- Custom attribute to simulate readonly --}}
                                            >                                            
                                            <p class="bed_imgs"> <img src="{{ asset($img) }}" alt="icon"></p>
                                            <span class="midle_bar"></span>
                                            <p class="radio_txt"> {{ $item['name'] }}</p>
                                        </label>
                                    @endforeach
                                    @error('rooms.' . $x . '.room') <label class="error-label">{{ $message }}</label> @enderror
                                </div>
                                <button type="button" class="include-button">INCLUDE</button>
                            </div>
                        @endfor
                    </div>
                </div>
<!-- BREAKPOINT: Hotels Section -->
                <div class="hotel">
                    <h6 class="sub-heading-6">HOTEL</h6>
                        @php $hotels = $this->tour->repository->getHotels();
                            $currentRating = $default->accommodationtype?->name;
                            $nextHotel = $this->tour->repository->getNextAccommodationByRating($hotels, $currentRating ?? '');
                        @endphp
                        @if ($nextHotel)
                            @php
                                $hotelName = $nextHotel['hotel']->name ?? '';
                                $hotelType = $nextHotel['accommodationType'] ?? '';
                            @endphp
                            <p>Your package includes a {{ $noOfNights == 1 ? 'night' : $noOfNights.'-nights' }} stay at {{ $hotelName }}, a {{ $hotelType }} hotel. If you’d like to upgrade, please select from one of the other options below.</p>
                        @endif
                    <div class="hotel-listing">
                        {{--@foreach($this->tour->repository->getHotels() as $id => $arrHotel)
                            @php
                                $hotel = $arrHotel['hotel'];
                                $rooms = $this->tour->repository->getBookingRooms($hotel->id);
                                $defaultRoom = reset($rooms);
                            @endphp
                            <div class="single-hotel" wire:click="setHotel({{$hotel->id}})">
                                @if(!empty($hotel->gallery) && count($hotel->gallery))
                                    <div class="hotel-image-block">
                                        @foreach($hotel->gallery as $photo)
                                            <div><img src="{{ asset($photo->file_path) }}" alt="{{ $hotel->name }}"></div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="hotel-block">
                                    <h6>{{ $hotel->name }}</h6>
                                    <p>{{ $hotel->accommodationtype?->name }}</p>
                                    <p class="tour_sales_price" id="tour_sales_price_{{ $hotel->id }}">
                                        @if ($defaultRoom['component_type'] === 'Upgrade')
                                            +A$ {{ number_format($defaultRoom['sales_price'], 2) }}
                                        @endif
                                    </p>
                                    <div class="room-type">
                                        <p>Room type</p>
                                        <select class="room-selector" data-hotel-id="{{ $hotel->id }}">
                                            @foreach($rooms as $id => $item)
                                                <option value="{{ $id }}" data-sales_price="{{ $item['sales_price'] }}" {{ $id == key($rooms) ? 'selected' : '' }}>
                                                    {{ $item['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="breakfast-note">{{ $defaultRoom['board_type'] ?? '' }} </p>
                                        <p>{!! $defaultRoom['room_desc'] ?? '' !!}</p>
                                        @php $selected = $booking->booking_accommodation_id === $hotel->id; @endphp
                                        <button type="button" class="include-button {{ $selected ? '' : 'active' }}">{{ $selected ? 'Selected' : $defaultRoom['component_type'] }}</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach--}}
                        @foreach($this->tour->repository->getHotelGroups() as $hotel => $hotelGroups)
                            @php $defaultGroup = $hotelGroups[array_key_first($hotelGroups)]; $hotel = $defaultGroup->hotel; @endphp
                            <div class="single-hotel" wire:click="setHotel({{$hotel->id}})">
                                    <div wire:ignore>
                                        @if(!empty($hotel->gallery) && count($hotel->gallery))
                                            <div class="hotel-image-block">
                                                @foreach($hotel->gallery as $photo)
                                                    <div><img src="{{ asset($photo->file_path) }}" alt="{{ $hotel->name }}"></div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="hotel-block">
                                        <h6>{{ $hotel->name }}</h6>
                                        <p>{{ $hotel->accommodationtype?->name }}</p>
                                        {{--<p class="tour_sales_price" id="tour_sales_price_{{ $hotel->id }}">
                                            @if ($defaultRoom['component_type'] === 'Upgrade')
                                                +A$ {{ number_format($defaultRoom['sales_price'], 2) }}
                                            @endif
                                        </p>--}}
                                        <div class="room-type">
                                            <p>Room type</p>
                                            <select class="room-selector" data-hotel-id="{{ $hotel->id }}">
                                                @foreach($hotelGroups as $group)
                                                    <option value="{{ $group->occupancy->id }}" data-sales_price="{{ $group->getUpgradeCost() }}" {{ $id == key($rooms) ? 'selected' : '' }}>
                                                        {{ $group->category?->name }} ({{ fr_currency($group->getUpgradeCost() * $this->getFXRate(), $this->getCurrency()) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="breakfast-note">{{ $defaultGroup->board->name }} </p>
                                            <p>{!! $hotel->description !!}</p>
                                            @php $selected = $booking->booking_accommodation_id === $hotel->id; @endphp
                                            <button type="button" class="include-button {{ $selected ? '' : 'active' }}">{{ $selected ? 'Selected' : $defaultGroup->rooms[0]->tour_component_type }}</button>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
<!-- BREAKPOINT: Sidebar -->
            <div class="column right">
                <div class="package-details">
                    <div class="contain">
                        <div class="top-module">
                            <h4 class="sub-heading-4">Package details</h4>
                            <div class="hide-package-detail">Hide package details</div>
                        </div>
                        @if($tour?->event?->image_url !== null)
                        <div class="image-block">
                            <img src="{{ asset($tour->event->image_url) }}" class="package-image" alt="featured-img">
                        </div>
                        @endif
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
                                    <p>{{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getBasePrice(), $selectedCurrency), $selectedCurrency) }}</p>
                                </div>
                                @php $singleOccupancy = $booking->repository->getSingleOccupancyAmount(); @endphp
                                @if($singleOccupancy > 0 || $singleOccupancy < 0)
                                    <div class="single">
                                        <p>Single occupancy surcharge</p>
                                        <p>{{ f_currency($booking->repository->convertBookingCurrency($singleOccupancy, $selectedCurrency), $selectedCurrency) }}</p>
                                    </div>
                                @endif
                                <div class="single">
                                    <p>Number of packages - {{ $this->getTravellerCount() }}</p>
                                    <p>{{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getBasePrice(), $selectedCurrency), $selectedCurrency) }}</p>
                                </div>
                            </div>
                            <div class="added-nights txt-org" style="display:none;">
                                <h5>Added nights</h5>
                                <div class="single">
                                    <p>
                                        <span>2 x Additional nights</span>
                                        <span>20 Jan - 25 Jan 2025</span>
                                    </p>
                                    <p>A$1,500</p>
                                </div>
                            </div>
                            <div class="room-upgrades txt-org">
                                <h5>Accommodation upgrade</h5>
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
                            <div class="Hotel" style="display:none;">
                                <h5>Hotel</h5>
                                <div class="single">
                                    <p>{{$default->name}}, {{ $default->address?->town }}</p>
                                    <p>Price included</p>
                                </div>
                            </div>
                            
                            <div class="total">                                
                                <div class="single">
                                    <p>Total</p>
                                    <p>{{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getTotalCost(), $selectedCurrency), $selectedCurrency) }}</p>
                                </div>
                                @if($booking->repository->getTaxes() !== null)
                                    <div class="single">
                                        <p>{{ $tour->taxBracket()->name }} (Included)</p>
                                        <p>{{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getTaxes(), $selectedCurrency), $selectedCurrency) }}</p>
                                    </div>
                                @endif
                                <div class="single">
                                    <p>Base Package Price</p>
                                    <p>{{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getBasePrice(), $selectedCurrency), $selectedCurrency) }}</p>
                                </div>
                                @php $upgradePrice = $booking->repository->getUpgradeCosts(); @endphp
                                @if($upgradePrice > 0 || $upgradePrice < 0)
                                    <div class="single">
                                        <p>Upgardes & Add Ons</p>
                                        <p>{{ f_currency($booking->repository->convertBookingCurrency($upgradePrice, $selectedCurrency), $selectedCurrency) }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="payment-method">
                                <div class="payable-now">
                                    <div class="single">
                                        <p>Payable now ({{ $booking->tour?->deposit_percentage }}%)</p>
                                        <p>{{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getDueTodayAmount(), $selectedCurrency), $selectedCurrency)  }}</p>
                                    </div>
                                    <p>
                                        Balance {{ f_currency(($booking->repository->convertBookingCurrency($booking->repository->getTotalCost(), $selectedCurrency) - $booking->repository->convertBookingCurrency($booking->repository->getDueTodayAmount(), $selectedCurrency)), $selectedCurrency ) }}
                                        payable by {{ $tour->final_payment->format('d M Y') }}</p>
                                </div>

                                <div class="email-quote">
                                    <h6 class="sub-heading-6" wire:click="toggleCustomerForm">EMAIL Quote</h6>
                                    @if ($showCustomerForm)
                                        <div class="customer_profile">
                                            <button wire:loading.attr="disabled" style="width:fit-content"
                                                    wire:click="emailQuote" type="button" class="Go-next">
                                                <span wire:loading.remove>Send Quote</span>
                                                <span wire:loading>Sending...</span>
                                            </button>
                                        </div>
                                    @endif
                                    @if (session()->has('error'))
                                        <div class="alert alert-danger" aria-live="polite">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="next-button" id="tocheckbedconfiguration_lock" wire:click="advance">
                        <span>
                            <span>NEXT</span>
                            <img src="{{ asset('icons/Right-arrow-mod.svg') }}" alt="right-arrow">
                        </span>
                    </button>
                    @error('common')
                    <span class="accomodation-travel-date-error">
                        <span><img src="{{ asset('icons/Noti-Icon.svg') }}" alt="icon"></span>
                        <span>{{ $message }}</span>
                    </span>
                    @enderror
                    <span class="accomodation-travel-date-error" id="guest-distribution-error" style="display: none;">
                        <span>
                            <img src="{{ asset('icons/Noti-Icon.svg') }}" alt="icon">
                        </span>
                        <span id="guest-distribution-error-message"></span>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roomSelectors = document.querySelectorAll('.room-selector');
            roomSelectors.forEach(selector => {
                selector.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const newSalesPrice = selectedOption.getAttribute('data-sales_price');
                    const hotelId = this.getAttribute('data-hotel-id');
                    // Find the tour_sales_price block for this hotel and update it
                    const priceBlock = document.getElementById('tour_sales_price_' + hotelId);
                    // Format the new price as currency
                    priceBlock.textContent = `+A$ ${parseFloat(newSalesPrice).toFixed(2)}`;
                });
            });
        });

        jQuery(document).ready(function () {
            const totalGuests = parseInt("{{ $this->getTravellerCount() }}");
            const errorContainer = document.getElementById('guest-distribution-error');
            const errorMessage = document.getElementById('guest-distribution-error-message');

            // Show error message
            function showError(message) {
                errorMessage.textContent = message;
                errorContainer.style.display = 'flex';
            }

            // Validate a single room's guest selection and bed configuration
            function validateRoom($room) {
                const guestsSelected = parseInt($room.find('.guest-radio:checked').val() || 0);
                const $bedSelected = $room.find('.bed-radio:checked');

                if (guestsSelected > 0 && $bedSelected.length > 0) {
                    const bedOccupancy = parseInt($bedSelected.data('bed-occupancy'));
                    if (bedOccupancy !== guestsSelected) {
                        showError('Mismatch: Bed occupancy (' + bedOccupancy + ') must match number of guests (' + guestsSelected + ') in the room.');
                        $bedSelected.prop('checked', false);
                    }
                }
            }

            // Validate the total number of guests across all rooms
            function validateTotalGuests() {
                let assignedGuests = 0;

                $('.single-room').each(function () {
                    const guestsSelected = parseInt($(this).find('.guest-radio:checked').val() || 0);
                    assignedGuests += guestsSelected;
                });

                if (assignedGuests > totalGuests) {
                    showError('Assigned guests (' + assignedGuests + ') exceed total booking guests (' + totalGuests + ').');
                    return false;
                }

                // Don't hide error container if guests are still unassigned
                if (assignedGuests < totalGuests) {
                    console.log('Still need to assign more guests.');
                }
                return true;
            }

            // Enable/Disable bed configurations based on selected guests
            function updateBedConfigs($room) {
                const guestsSelected = parseInt($room.find('.guest-radio:checked').val() || 0);
                const $bedConfigs = $room.find('.bed-radio');

                // Disable all bed configurations initially
                $bedConfigs.prop('disabled', true);

                // Enable the bed configurations that match the number of selected guests
                $bedConfigs.each(function() {
                    const bedOccupancy = parseInt($(this).data('bed-occupancy'));

                    if (bedOccupancy === guestsSelected) {
                        $(this).prop('disabled', false); // Enable matching bed config
                    }
                });
            }

            // When guest number is selected, update bed configurations
            $(document).on('change', '.guest-radio', function () {
                const roomIndex = $(this).data('room-index');
                const $room = $('[data-room-index="' + roomIndex + '"]').closest('.single-room');

                // Validate room and update bed configurations
                validateRoom($room);
                updateBedConfigs($room);
                validateTotalGuests();
            });

            // When bed configuration is selected, validate the room again
            $(document).on('change', '.bed-radio', function () {
                const roomIndex = $(this).data('room-index');
                const $room = $('[data-room-index="' + roomIndex + '"]').closest('.single-room');
                validateRoom($room);
            });

            // Initialize bed configurations when the page is loaded (if any initial guest number is preselected)
            $('.single-room').each(function() {
                const $room = $(this);
                updateBedConfigs($room);
            });
        });

    </script>
</x-customer.booking.v3.layout>
