@php $noOfNights = $this->tour->repository->getTourNights(); @endphp
<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="2" payFull="{{ $payFull }}">
    <x-slot:left>
        <div class="accommodation-detail ">
            <h2 class="sub-heading-2-p">ACCOMMODATION DETAILS</h2>
            <p>Review and customise your accommodation details. Selecting a different hotel or room type may
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
                    <p class="default-hotel-more-info">
                        <a href="#" class="default-hotel-moreinfo-href" data-action="popup" data-target="default-hotel-more-popup-info">More information</a>
                    </p>
                </div>                    
            </div>
        </div>
        <div class="booking-dates">
            <h6 class="sub-heading-6">BOOKING DATES</h6>
            <p>If you would like to extend your stay, please contact our Sales team at <a href="mailto:travel@kpt.com.au">travel@kpt.com.au</a></p>
            <!-- <p class="mod">Extend your stay</p> -->
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
                    ['count' => 1, 'label' => 'Single/Double'],
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
            
            <div class="reselect_config">
                @if($reselectConfigMessage)
                    <p>{{ $reselectConfigMessage }}</p>
                @endif
                @error('common')
                <div style="padding-top: 1rem; color: red;">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="room-listing-module">
                @php
                    $bookingRooms = $this->tour->repository->getBookingRooms($selectedHotel);
                    $firstRoom = reset($bookingRooms);
                @endphp
                @for($x = 0, $xMax = count($rooms); $x < $xMax; $x++)
                    <div class="single-room" id="room-{{ $x }}">
                        <h6>Room {{ $x + 1 }}</h6>
                        <div class="roomdesc" data-room-index="{{ $x }}">{!! $roomDescriptions[$x] ?? '' !!}</div>
                        <p>Number of guests</p>
                        <div class="guest-module">
                            @for($i = 1, $iMax = min($this->getTravellingCount(), 3); $i <= $iMax; $i++)
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
                        </div>
                        <p>Bed configuration</p>
                        <div class="form-field" id="bed-config-{{ $x }}">
                            @foreach($this->tour->repository->getBookingRooms($selectedHotel) as $id => $item)
                                @php
                                    $available = $item['availability'] > count($rooms);
                                    $selectedRoomId = $rooms[$x]['room'] ?? null;
                                    $isSelected = $selectedRoomId == $id;
                                    $cls = $isSelected ? 'selected-bed' : '';
                                    if (!$available) { $cls .= ' disabled'; }
                                    $roomName = strtolower($item['name']);
                                    $img = match(true) {
                                        str_contains($roomName, 'single'), str_contains($roomName, 'double') =>
                                            $isSelected ? 'icons/bed_1_selected.svg' : 'icons/bed_1.svg',
                                        str_contains($roomName, 'twin') =>
                                            $isSelected ? 'icons/twin-bed-hover.svg' : 'icons/bed_2.svg',
                                        str_contains($roomName, 'triple') =>
                                            $isSelected ? 'icons/Triple-Bed-hover.svg' : 'icons/bed_3.svg',
                                        default =>
                                            $isSelected ? 'icons/bed_1_selected.svg' : 'icons/bed_1.svg',
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
                                            data-readonly="true"
                                            @if(!$available) disabled @endif
                                    >
                                    <p class="bed_imgs"> <img src="{{ asset($img) }}" alt="icon"></p>
                                    <span class="midle_bar"></span>
                                    <p class="radio_txt"> {{ $item['name'] }}</p>
                                </label>
                            @endforeach
                        </div>
                        <button type="button" class="include-button">INCLUDED</button>
                        @error('rooms.' . $x . '.room') <label class="error-label">{{ $message }}</label> @enderror
                        @error('rooms.' . $x . '.travellers') <label class="error-label">{{ $message }}</label> @enderror
                    </div>
                @endfor
            </div>
        </div>
        <!-- BREAKPOINT: Hotels Section -->
        <div class="hotel">
            <h6 class="sub-heading-6">HOTEL</h6>
            {{-- @php
                $hotels = $this->tour->repository->getHotels();
                $currentRating = $default->accommodationtype?->name;
                $nextHotel = $this->tour->repository->getNextAccommodationByRating($hotels, $currentRating ?? '');
            @endphp
            @if ($nextHotel)
                @php
                    $hotelName = $nextHotel['hotel']->name ?? '';
                    $hotelType = $nextHotel['accommodationType'] ?? '';
                @endphp
                <p>Your package includes a {{ $noOfNights == 1 ? 'night' : $noOfNights.'-nights' }} stay at {{ $default->name }}, a {{ $default->accommodationtype?->name }} hotel. If you’d like to upgrade, please select from one of the other options below.</p>
            @endif --}}
            @if($this->hasHotelUpgrades())
                <p>
                    Your package includes a {{ $noOfNights == 1 ? 'night' : $noOfNights . '-nights' }} stay at
                    {{ $default->name }}, a {{ $default->accommodationtype?->name }} hotel.
                    If you’d like to upgrade, please select from one of the other options below.
                </p>
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
                    <div class="single-hotel">
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
                            <div class="room-type">
                                @if ($defaultGroup->rooms[0]->tour_component_type === 'Upgrade')
                                    <p class="tour_sales_price"> +{{ $this->formatCurrency($this->calculateUpgradeCost($hotel->id)) }}</p>
                                @elseif($defaultGroup->rooms[0]->tour_component_type === 'Included')
                                    <p class="tour_sales_price"> +{{ $this->formatCurrency(0) }}</p>
                                @endif
                                <p>Room type</p>
                                <select wire:model="categories.{{$hotel->id}}" class="room-selector" data-hotel-id="{{ $hotel->id }}">
                                    @php $seen = []; @endphp
                                    @foreach($hotelGroups as $group)
                                        @continue(in_array($group->category?->id, $seen))
                                        @php
                                            $seen[] = $group->category?->id;
                                            $isUpgrade = $group->rooms[0]->tour_component_type === 'Upgrade';
                                            $upgradeCost = $isUpgrade ? '(' . $this->formatCurrency($group->getUpgradeCost() * count($this->rooms)) . ')' : '';
                                        @endphp
                                        <option value="{{ $group->category?->id }}" data-sales_price="{{ $this->calculateUpgradeCost($hotel->id, $group->category?->id) }}" {{ $id == key($rooms) ? 'selected' : '' }}>
                                            {{ $group->category?->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @php
                                    $cleanText = strip_tags($hotel->description);
                                    $descTruncated = Str::limit($cleanText, 200, '...');
                                @endphp
                                <p class="breakfast-note">{{ $defaultGroup->board->name }} </p>
                                <p>{!! $descTruncated !!}</p>
                                <p class="hotel-more-info"><a>More information</a></p>
                                <div class="hotel-more-info-popup">
                                    <div class="hotel-more-info-contain">
                                        <div class="hotel-more-info-block">
                                            <div class="info-body">
                                                <div class="hotel-close-button"><img src="{{ asset('icons/Close-Button.svg') }}" alt="package-details"></div>
                                                <h4>{{ $hotel->name }}</h4>
                                                <p>{{ $hotel->address }}</p>
                                                <div wire:ignore>
                                                    @if(!empty($hotel->gallery) && count($hotel->gallery))
                                                        <div class="hotel-image-popup-block">
                                                            @foreach($hotel->gallery as $photo)
                                                                <div><img class="hotel-zoomable-image" src="{{ asset($photo->file_path) }}" alt="{{ $hotel->name }}" loading="lazy"></div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                                <div wire:ignore>
                                                    @if(!empty($hotel->gallery) && count($hotel->gallery))
                                                        <div class="hotel-zoom-overlay">
                                                            <div class="hotel-zoom-slider">
                                                                @foreach($hotel->gallery as $photo)
                                                                    <div><img src="{{ asset($photo->file_path) }}" alt="{{ $hotel->name }}" loading="lazy"></div>
                                                                @endforeach
                                                            </div>
                                                            <span class="hotel-close-zoom">×</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div wire:ignore class="amenities-container">
                                                    @if(!empty($hotel->amenities) && count($hotel->amenities))
                                                        <div class="row">
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
                                                <div class="hotel-description"><p>{!! $hotel->description !!}</p></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $selected = $booking->booking_accommodation_id === $hotel->id; @endphp
                                <button type="button" wire:click="setHotel({{$hotel->id}})" class="include-button {{ $selected ? '' : 'active' }}">{{ $selected ? 'Selected' : $defaultGroup->rooms[0]->tour_component_type }}</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-slot:left>

        <!-- Default hotel Popup more information -->
        <div class="default-hotel-more-info-popup"> <!-- Ensure this is hidden by default -->
            <div class="default-hotel-more-contain">
                <div class="more-default-hotel-block">
                    <div class="popup-default-hotel-details">
                        <div class="default-info-body">
                            <div class="default-hotel-close-button"><img src="{{ asset('icons/Close-Button.svg') }}" alt="package-details"></div>
                            <h4>{{ $default->name }}</h4>
                            <p>{{ $default->address }}</p>
                            <div wire:ignore>
                                @if(!empty($default->gallery) && count($default->gallery))
                                    <div class="default-hotel-image-popup-block">
                                        @foreach($default->gallery as $photo)
                                            <div><img src="{{ asset($photo->file_path) }}" alt="{{ $hotel->name }}" loading="lazy"></div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div wire:ignore class="default-amenities-container">
                                @if(!empty($default->amenities) && count($default->amenities))
                                    <div class="row">
                                        @foreach($default->amenities as $item)
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
                            <div class="hotel-description"><p>{!! $default->description !!}</p></div>
                        </div>
                        <!-- Add any more details about the hotel here -->
                    </div>
                </div>
            </div>
        </div>
        <!-- end of the Default hotel popup more information -->

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

       jQuery(document).on('click', '.single-hotel .hotel-block a', function () {
           jQuery(this).closest('.hotel-block').find('.hotel-more-info-popup').css('display', 'flex')
        })
        jQuery(document).on('click', '.hotel-more-info-popup .hotel-close-button,.hotel-more-info-popup .cancel', function () {
            jQuery(this).closest('.hotel-block').find('.hotel-more-info-popup').css('display', 'none')
        })
    </script>

    <script>
        window.addEventListener('scroll-to-reselect', event => {
            const reselectConfigDiv = document.querySelector('.reselect_config');
            if (reselectConfigDiv) {
                window.scrollTo({
                    top: reselectConfigDiv.offsetTop,
                    behavior: 'smooth'
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const moreInfoLink = document.querySelector('.default-hotel-moreinfo-href');
            const popup = document.querySelector('.default-hotel-more-info-popup');
            const closeBtn = document.querySelector('.default-hotel-close-button');
            moreInfoLink?.addEventListener('click', function (e) {
                e.preventDefault();
                popup.style.display = 'flex';
                popup.classList.add('active');
            });

            closeBtn?.addEventListener('click', function () {
                // Hide the popup
                popup.style.display = 'none';
                popup.classList.remove('active');
            });

            // Optional: Click outside the popup to close
            popup?.addEventListener('click', function (e) {
                if (e.target === popup) {
                    popup.style.display = 'none';
                    popup.classList.remove('active');
                }
            });
        });
    </script>
</x-customer.booking.v3.layout>