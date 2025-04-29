@php $noOfNights = $this->tour->repository->getTourNights(); @endphp
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
                            <!-- class="single-room" -->
                            <div id="room-{{ $x }}" class="single-room room" id="room-${i}">
                                <h6>Room {{ $x + 1 }}</h6>
                                <div class="roomdesc" data-room-index="{{ $x }}"></div>
                                <p>Number of guests</p>
                                <div class="guest-module">
                                    @for($i = 1, $iMax = 3; $i <= $iMax; $i++)

                                        <!-- <input
                                            type="radio"
                                            class="guest-radio"                                            
                                            name="rooms[{{ $x }}][travellers]"
                                            value="{{ $i }}"
                                            data-room-index="{{ $x }}"
                                            id="guest{{$i}}"
                                        > -->
                                        <input type="radio" id="guest{{$x}}-{{$i}}" name="room-{{$x}}-guests" value="{{ $i }}" class="room-${x}-guest">
                                        <label for="guest{{$x}}-{{$i}}"> {{ $i }} </label>
                                    @endfor
                                    @error('rooms.' . $x . '.travellers') <label class="error-label">{{ $message }}</label> @enderror
                                </div>
                                <p>Bed configuration</p>
                                <div class="bed-configuration-module form-field" id="bed-config-{{ $x }}">
                                    @foreach($this->tour->repository->getBookingRooms($selectedHotel) as $id => $item)                                       
                                            <!-- <input
                                                type="radio"
                                                wire:model="rooms.{{$x}}.room"
                                                class="bed-radio"
                                                name="rooms[{{ $x }}][room]"
                                                value="{{ $id }}"
                                                data-room-index="{{ $x }}"
                                                data-bed-occupancy="{{ $item['occupancy'] }}"
                                                data-bed-desc="{!! htmlspecialchars($item['room_desc']) !!}"
                                                data-readonly="true" {{-- Custom attribute to simulate readonly --}}
                                            > 

                                            singleBed.prop('disabled', true);
                                            doubleBed.prop('disabled', true);
                                            twinBed.prop('disabled', true);
                                            tripleBed.prop('disabled', true);
                                                                                
                                            -->
                                            @php
                                                $bedClass = match (true) {
                                                    str_contains('single', strtolower($item['name'])) => 'singleBed',
                                                    str_contains('twin', strtolower($item['name'])) => 'twinBed',
                                                    str_contains('triple', strtolower($item['name'])) => 'tripleBed',
                                                    str_contains('double', strtolower($item['name'])) => 'doubleBed',
                                                    default => "singeBed",
                                                }
                                            @endphp
                                            <input type="radio" id="room-{{$x}}-bed-{{$id}}" name="room-{{$x}}-bed" value="{{$id}}" data-bed-occupancy="{{ $item['occupancy'] }}" class="room-${x}-bed {{ $bedClass }} single-bed">
                                            <label for="room-{{$x}}-bed-{{$id}}" class="bed-configuration">
                                            <div class="bed-icon">
                                                @php
                                                 if ($item['occupancy'] == 2) {
                                                    $bedIcon = '/images/Double-bed.svg';
                                                 }
                                                 if ($item['occupancy'] == 1) {
                                                    $bedIcon = '/images/bed-double.svg';
                                                 }
                                                 if ($item['occupancy'] == 3) {
                                                    $bedIcon = '/images/Triple-Bed-d.svg';
                                                 }
                                                @endphp
                                                <img src="{{ asset($bedIcon) }}" alt="{{ $item['name'] }} Bed" class="default">                                                
                                            </div>                                            
                                            <div class="bed-text">{{ $item['name'] }}</div>
                                            </label>                                            
                                    @endforeach
                                    @error('rooms.' . $x . '.room') <label class="error-label">{{ $message }}</label> @enderror
                                </div>
                                <button type="button" class="include-button">INCLUDE</button>
                            </div>
                        @endfor
                    </div>
                </div>
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
                        @foreach($this->tour->repository->getHotels() as $id => $arrHotel)
                            @php
                                $hotel = $arrHotel['hotel'];
                                $rooms = $this->tour->repository->getBookingRooms($hotel->id);
                                $defaultRoom = reset($rooms);
                            @endphp
                            <div class="single-hotel">
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
                                    @if ($defaultRoom['component_type'] === 'Upgrade')
                                        <p>+A$0</p>
                                    @endif
                                    <div class="room-type">
                                        <p>Room type</p>
                                        <select>
                                            @foreach($this->tour->repository->getBookingRooms($hotel->id) as $id => $item)
                                                <option value="{{$id}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                        <p class="breakfast-note">{{ $defaultRoom['board_type'] ?? '' }} </p>
                                        <p>{!! $defaultRoom['room_desc'] ?? '' !!}</p>
                                        <button type="button" class="include-button {{ $defaultRoom['component_type'] === 'Upgrade' ? 'active' : '' }}">{{ $defaultRoom['component_type']}}</button>
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
                                    <p>Starting package price</p>
                                    <p>$14,975</p>
                                </div>
                                <div class="single">
                                    <p>Customisation cost</p>
                                    <p>$500</p>
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
                    </div>
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
</x-customer.booking.v3.layout>
<!-- <script>
    jQuery(document).ready(function () {
        const totalGuests = parseInt("{{ $this->getTravellerCount() }}");
        const errorContainer = document.getElementById('guest-distribution-error');
        const errorMessage = document.getElementById('guest-distribution-error-message');

        function showError(message) {
            errorMessage.textContent = message;
            errorContainer.style.display = 'flex';
        }

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

        // When guest number is selected
        $(document).on('change', '.guest-radio', function () {
            const roomIndex = $(this).data('room-index');
            const $room = $('[data-room-index="' + roomIndex + '"]').closest('.single-room');
            validateRoom($room);
            validateTotalGuests();
        });

        // When bed configuration is selected
        $(document).on('change', '.bed-radio', function () {
            const roomIndex = $(this).data('room-index');
            const $room = $('[data-room-index="' + roomIndex + '"]').closest('.single-room');
            validateRoom($room);
        });
    });
</script> -->
<script>
  $(document).ready(function() {
  const maxGuestsPerRoom = 3;
  const maxGuests = parseInt("{{ $this->getTravellerCount() }}"); 
  const errorContainer = document.getElementById('guest-distribution-error');
  const errorMessage = document.getElementById('guest-distribution-error-message');

//   function generateRooms(totalGuests) {
//     const neededRooms = Math.ceil(totalGuests / maxGuestsPerRoom);
//     $('#rooms-container').empty();

//     for (let i = 1; i <= neededRooms; i++) {
//       const roomMarkup = `
//         <div class="room" id="room-${i}">
//           <h4>Room ${i}</h4>
//           <div>
//             Guests: 
//             <input type="radio" name="room-${i}-guests" value="1" class="room-${i}-guest"> 1
//             <input type="radio" name="room-${i}-guests" value="2" class="room-${i}-guest"> 2
//             <input type="radio" name="room-${i}-guests" value="3" class="room-${i}-guest"> 3
//           </div>
//           <div>
//             Bed Type: 
//             <input type="radio" name="room-${i}-bed" value="1" class="room-${i}-bed single-bed"> Single
//             <input type="radio" name="room-${i}-bed" value="2" class="room-${i}-bed double-bed"> Double
//             <input type="radio" name="room-${i}-bed" value="2" class="room-${i}-bed twin-bed"> Twin
//             <input type="radio" name="room-${i}-bed" value="3" class="room-${i}-bed triple-bed"> Triple
//           </div>
//         </div>
//       `;
//       $('#rooms-container').append(roomMarkup);
//     }

//     updateRoomAvailability();
//   }

//   $('#generate-rooms').click(function() {
//     const totalGuests = parseInt($('#total-guests').val());
//     if (totalGuests > 0) {
//       generateRooms(totalGuests);
//     }
//   });

  // Handle guest selection
  $(document).on('change', 'input[type="radio"][name*="guests"]', function() {
    const roomDiv = $(this).closest('.room');
    roomDiv.find('input[type="radio"][name*="bed"]').prop('checked', false) 
    updateRoomAvailability();
  });

  // Handle bed type selection with validation
  $(document).on('change', 'input[type="radio"][name*="bed"]', function(e) {
    const roomDiv = $(this).closest('.room');
    const guestSelected = roomDiv.find('input[type="radio"][name*="guests"]:checked').val();

    if (!guestSelected) {
      // If guest not selected yet
      alert('Please select number of guests first!');
      $(this).prop('checked', false); // Uncheck the wrongly selected bed
      e.preventDefault();
      return false;
    }

    updateRoomAvailability();
  });

  function showError(message) {
    errorMessage.textContent = message;
    errorContainer.style.display = 'flex';
  }

  function updateRoomAvailability() {
    let totalGuestsSelected = 0;
    let selectedRooms = 0;
    let bedOccupancy = 0;
    //const maxGuests = parseInt($('#total-guests').val());

    $('.room').each(function() {
      const guestSelected = $(this).find('input[type="radio"][name*="guests"]:checked').val();      
      if (guestSelected) {
        bedOccupancy += parseInt($(this).find('input[type="radio"][name*="guests"]:checked').data('bed-occupancy'))      
        totalGuestsSelected += parseInt(guestSelected);       
        if (guestSelected > bedOccupancy) {
            showError('Mismatch: Bed occupancy (' + bedOccupancy + ') must match number of guests (' + guestsSelected + ') in the room.');
            return false;
        }
        selectedRooms++;
      }
    });

    

    // Disable all further guest selection once totalGuests is reached
    // if (totalGuestsSelected >= maxGuests) {
    //   $('.room input[type="radio"][name*="guests"]').each(function() {
    //     if (!$(this).is(':checked')) {
    //       $(this).prop('disabled', true);
    //     }
    //   });
    // } else {
    //   $('.room input[type="radio"][name*="guests"]').prop('disabled', false);
    // }

    // Update Bed Type enabling based on guests selected per room
    $('.room').each(function() {
      const guestSelected = $(this).find('input[type="radio"][name*="guests"]:checked').val();
      const singleBed = $(this).find('.singleBed');
      const doubleBed = $(this).find('.doubleBed');
      const twinBed = $(this).find('.twinBed');
      const tripleBed = $(this).find('.tripleBed');

      if (guestSelected == 1) {
        singleBed.prop('disabled', false);
        doubleBed.prop('disabled', true);
        twinBed.prop('disabled', true);
        tripleBed.prop('disabled', true);
      } else if (guestSelected == 2) {
        singleBed.prop('disabled', true);
        doubleBed.prop('disabled', false);
        twinBed.prop('disabled', false);
        tripleBed.prop('disabled', true);
      } else if (guestSelected == 3) {
        singleBed.prop('disabled', true);
        doubleBed.prop('disabled', true);
        twinBed.prop('disabled', true);
        tripleBed.prop('disabled', false);
      } else {
        // No guest selected yet
        singleBed.prop('disabled', true);
        doubleBed.prop('disabled', true);
        twinBed.prop('disabled', true);
        tripleBed.prop('disabled', true);
      }
    });
  }

  // Reset everything
//   $('#reset').click(function() {
//     $('#total-guests').val(5);
//     $('#rooms-container').empty();
//     $('#validation').text('');
//     $('#result').text('');
//     generateRooms(5);
//   });

  // Initial page load
  //generateRooms(5);
});
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const advanceBtn = document.getElementById('tocheckbedconfiguration');
        const errorContainer = document.getElementById('guest-distribution-error');
        const errorMessage = document.getElementById('guest-distribution-error-message');
        if (advanceBtn) {
            advanceBtn.addEventListener('click', function () {
            const expectedGuests = parseInt("{{ $this->getTravellerCount() }}");
            const roomCount = "{{ count($this->rooms) }}";
            let totalGuests = 0;
            let valid = true;
            let messages = [];

            for (let roomIndex = 0; roomIndex < roomCount; roomIndex++) {
                const guestInput = document.querySelector(`input[name="rooms[${roomIndex}][travellers]"]:checked`);
                const bedInput = document.querySelector(`input[name="rooms[${roomIndex}][room]"]:checked`);

                if (!guestInput) {
                    messages.push(`Room ${roomIndex + 1}: Please select number of guests.`);
                    valid = false;
                    continue;
                }

                const guestCount = parseInt(guestInput.value);
                totalGuests += guestCount;

                // Check for matching bed config
                const matchingBedConfig = document.querySelectorAll(`#bed-config-${roomIndex} .bed-radio:not([disabled])`);
                if (guestCount > 0 && (!bedInput || bedInput.disabled)) {
                    messages.push(`Room ${roomIndex + 1}: Please select a valid bed configuration for ${guestCount} guest(s).`);
                    valid = false;
                }
            }

            if (totalGuests !== expectedGuests) {
                messages.push(`Total number of guests selected (${totalGuests}) does not match expected (${expectedGuests}).`);
                valid = false;
            }

            if (!valid) {
                //alert(messages.join("\n"));
                errorMessage.textContent = messages.join("\n");
                errorContainer.style.display = 'flex';
            } else {
                Livewire.emit('advance');
            }
            });
        }
    });
</script>
