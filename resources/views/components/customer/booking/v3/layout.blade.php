@php
    $location = collect([$tour->city, $tour->country?->name])->filter()->implode(',  ');
    $selectedCurrency = $this->booking->currency?->code ?? setting('system.currency');
    $captchaEnabled = setting('booking.captcha.key') && setting('booking.captcha.secret');
@endphp
<div>
    <header>
        <div class="container">
            <div class="column">
                @if(isset($this->brand))
                    <a href="https://www.keithprowsetravel.com/" target="_blank"><img src="{{ asset($this->brand->alt_logo ?? $this->brand->logo) }}" style="max-height: 75px" alt="logo" title="{{ $this->brand->name }}"></a>
                @endif
            </div>
            <div class="column right">
                <p>Require assistance?</p>
                @if(isset($this->brand))
                    <a href="tel:{{$this->brand->phone}}">
                        <span><img src="{{ asset('icons/Call-Icon.svg') }}" alt="logo"></span><span>{{$this->brand->phone}}</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <main>
        <section class="secure-booking">
            <div class="container">
                <div class="heading">
                    <div class="breadcrumbs" wire:click="back"><span><img src="{{ asset('icons/Arrow-left.svg') }}" alt="left-arrow"></span><span>BACK</span></div>
                    <h1>Secure Booking</h1>
                </div>
                <div class="timeline text-dark">
                    @foreach ($this->steps as $step => $data)
                        <div class="step @if($stage === $step) active @elseif($stage > $step) completed @endif">
                            @if ($stage >= $step)
                                <a class="circle-completed" href="{{ route($data['route'], ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]) }}">
                                    <div class="circle"><span>{{ sprintf('%02d', $step) }}</span></div>
                                </a>
                            @else
                                <div class="circle"><span>{{ sprintf('%02d', $step) }}</span></div>
                            @endif
                            <div class="label">
                                @if ($stage >= $step)
                                    <a class="text-dark" href="{{ route($data['route'], ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]) }}">
                                        {{ $data['label'] }}
                                    </a>
                                @else
                                    {{ $data['label'] }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="package-container first">
            <div class="container">
                <div class="column left">
                    <div class="tour-details">
                        <h2 class="sub-heading-2">{{ $tour->booking_package_name }}</h2>
                        <h3 class="sub-heading-3">{{ $tour->event?->name }}</h3>
                        <div class="location-dollar-value">
                            @if($location)
                                <p class="location">{{ $location }}</p>
                                <span></span>
                            @endif
                            <p class="dollar">From {{ $this->formatCurrency($tour->base_price_per_person) }} / person twin share</p>
                        </div>
                    </div>
                    {{ $left }}
                </div>
                <div class="column right">
                    <div class="package-details">
                        <div class="contain">
                            <div class="top-module">
                                <h4 class="sub-heading-4">Package details</h4>
                                <div class="hide-package-detail">Hide package details</div>
                            </div>
                            @if($tour->event?->image_url !== null)
                            <div class="image-block">
                                <img src="{{ asset($tour->event?->image_url) }}" alt="package-details">
                            </div>
                            @endif
                            <div class="base-package">
                                <h6 class="sub-heading-6">BASE PACKAGE</h6>
                                <h2>{{ $tour->booking_package_name }}</h2>
                                <p class="date-align"><img src="{{ asset('icons/checkin.svg') }}" alt="calendar"> {{ $tour->date_from?->format('d M Y') }} - {{ $tour->date_to?->format('d M Y') }}</p>
                                <ul>
                                    @foreach($tour->repository->getInclusions(4) as $inclusion)
                                        <li>{{ $inclusion }}</li>
                                    @endforeach
                                </ul>
                                <p class="more-package-info">
                                    <a href="#" class="moreinfo-href" data-action="popup" data-target="more-popup-info">MORE INFORMATION</a>
                                </p>
                            </div>

                            @if($hidePrices ?? true)
                            <div class="additional-inclusions">
                                {{-- @if($stage !== 1)
                                    <h6 class="sub-heading-6">ADDITIONAL INCLUSIONS</h6>
                                @endif --}}
                                <div class="select-currency">
                                    {{-- @livewire("customer.booking.v3.currency-selector", ['currency' => $selectedCurrency], key('currency-selector')) --}}
                                    <div class="single">
                                        <p class="font-weight-bold">Package price</p>
                                        <p class="font-weight-bold">{{ $this->formatCurrency($booking->repository->getBasePrice()) }}</p>
                                    </div>
                                    <div class="cart_sub_title">
                                        <p>Price per Person</p>
                                        <p>{{ $this->formatCurrency($this->tour->base_price_per_person) }}</p>
                                    </div>
                                    {{-- <div class="single">
                                        <p>Number of packages - {{ $this->getTravellerCount() }}</p>
                                        <p>{{ $this->formatCurrency($booking->repository->getBasePrice()) }}</p>
                                    </div>
                                    @if($this->booking->repository->getSingleOccupancyAmount() > 0)
                                    <div class="single">
                                        <p>Single Occupancy - {{ $this->booking->repository->getSingleOccupancyCount() }}</p>
                                        <p>{{ $this->formatCurrency($this->booking->repository->getSingleOccupancyAmount()) }}</p>
                                    </div>
                                    @endif --}}
                                    @php $estimateSingleOccupancy = 0; @endphp
                                    {{-- @if($this->booking->groups()->count() <= 0)
                                        @php $singleCount = $this->booking->travellers()?->count() % 2; @endphp
                                        @if($singleCount > 0)
                                            @php $estimateSingleOccupancy = $this->tour->single_occupancy_surcharge * $singleCount; @endphp
                                            <div class="single">
                                                <p>Single Occupancy - {{ $singleCount }}</p>
                                                <p>{{ $this->formatCurrency($this->tour->single_occupancy_surcharge * $singleCount) }}</p>
                                            </div>
                                        @endif
                                    @endif --}}
                                </div>
                                @php $upgrades = $this->booking->repository->getUpgradesForPackageDetails(); @endphp
                                <div class="added-nights" style="display:none;">
                                    <h5>Added nights</h5>
                                    <div class="single">
                                        <p>
                                            <span>2 x Additional nights</span>
                                            <span>20 Jan - 25 Jan 2025</span>
                                        </p>
                                        <p>A$1,500</p>
                                    </div>
                                </div>
                                {{--
                                @if(count($upgrades['rooms']) > 0)
                                <div class="room-upgrades @if($stage === 2) txt-org @endif">
                                    <h5>Room upgrades</h5>
                                    @foreach($upgrades['rooms'] as $room)
                                        <div class="single">
                                            <p>{{ $room['description'] }}</p>
                                            <p>{{ $this->formatCurrency($room['cost']) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                @endif --}}
                                @php $default = $this->getDefaultHotel()?->component; @endphp
                                @if ($default !== null)
                                <div class="Hotel" style="display:none;">
                                    <h5>Hotel</h5>
                                    <div class="single">
                                        <p>{{$default->name}}, {{ $default->address?->town }}</p>
                                        <p>Price included</p>
                                    </div>
                                </div>
                                @endif
                                {{--
                                @if(count($upgrades['tickets']) > 0)
                                <div class="ticket-upgrades @if($stage === 3) txt-org @endif">
                                    <h5>Ticket upgrades</h5>
                                    @foreach($upgrades['tickets'] as $room)
                                        <div class="single">
                                            <p>{{ $room['description'] }}</p>
                                            <p>{{ $this->formatCurrency($room['cost']) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                                @if(count($upgrades['inclusions']) > 0)
                                <div class="additional-upgrades @if($stage === 4) txt-org @endif">
                                    <h5>Additional inclusions</h5>
                                    @foreach($upgrades['inclusions'] as $room)
                                        <div class="single">
                                            <p>{{ $room['description'] }}</p>
                                            <p>{{ $this->formatCurrency($room['cost']) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                @endif --}}
                                <div class="total">
                                    <div class="single">
                                        @php
                                            $rawAmount = $booking->repository->getTotalCost() + $estimateSingleOccupancy;
                                            $convertedAmount = $rawAmount * $this->getFXRate();
                                            $currencyCode = $this->getCurrency()->code;
                                            $upgradePrice = $booking->repository->getUpgradeCosts();
                                            $balance = $rawAmount - $booking->repository->getDueTodayAmount();
                                            $hasBalance = $balance > 0;
                                        @endphp
                                        <p>Total ({{ $currencyCode }})</p>
                                        <p>{{ $this->formatCurrency($booking->repository->getTotalCost() + $estimateSingleOccupancy) }}</p>
                                        <p style="display:none">
                                            <span class="currency-code">{{ $currencyCode }} </span>
                                            <span class="total-cost">{{ $convertedAmount }} </span>
                                        </p>
                                    </div>

                                    <div class="cart_sub_title">
                                        <p class="cart_sub_title_color">Payable now
                                            @if($hasBalance && $booking->tour?->deposit_percentage)
                                                ({{ $booking->tour->deposit_percentage }}%)
                                            @endif
                                        </p>
                                        <p class="fw-bold cart_sub_title_color">
                                            {{ $this->formatCurrency($payFull ? $booking->repository->getTotalCost() + $estimateSingleOccupancy : $booking->repository->getDueTodayAmount())  }}
                                        </p>
                                    </div>

                                    {{--
                                    <div class="single">
                                        <p>Base Package Price</p>
                                        <p>{{ $this->formatCurrency($booking->repository->getBasePrice()) }}</p>
                                    </div>
                                    @if(($this->tour->booking_fee ?? 0.0) > 0)
                                        <div class="single">
                                            <p>Booking Fee</p>
                                            <p>{{ $this->formatCurrency($this->tour->booking_fee) }}</p>
                                        </div>
                                    @endif
                                    @if($this->booking->repository->getSingleOccupancyAmount() > 0)
                                        <div class="single">
                                            <p>Single Occupancy - {{ $this->booking->repository->getSingleOccupancyCount() }}</p>
                                            <p>{{ $this->formatCurrency($this->booking->repository->getSingleOccupancyAmount()) }}</p>
                                        </div>
                                    @endif
                                    @if($this->booking->groups()->count() <= 0)
                                        @php $singleCount = $this->booking->travellers()?->count() % 2; @endphp
                                        @if($singleCount > 0)
                                            <div class="single">
                                                <p>Single Occupancy - {{ $singleCount }}</p>
                                                <p>{{ $this->formatCurrency($this->tour->single_occupancy_surcharge * $singleCount) }}</p>
                                            </div>
                                        @endif
                                    @endif
                                    @if($upgradePrice > 0 || $upgradePrice < 0)
                                        <div class="single">
                                            <p>Upgrades & Add Ons</p>
                                            <p>{{ $this->formatCurrency($upgradePrice) }}</p>
                                        </div>
                                    @endif
                                    @if($booking->repository->getTaxes() !== null)
                                        <div class="single">
                                            <p>{{ $tour->taxBracket()->name }} (Included)</p>
                                            <p>{{ $this->formatCurrency($booking->repository->getTaxes(), false) }}</p>
                                        </div>
                                    @endif
                                    --}}
                                </div>
                            </div>
                            <div class="payment-method ">
                                {{--
                                <div class="payable-now">
                                    <div class="single">
                                        <p>Payable now @if(!$payFull)({{ $booking->tour?->deposit_percentage }}%)@endif</p>
                                        <p>{{ $this->formatCurrency($payFull ? $booking->repository->getTotalCost() + $estimateSingleOccupancy : $booking->repository->getDueTodayAmount())  }}</p>
                                    </div>
                                    @if(!$payFull)
                                    <p>
                                        Balance {{ $this->formatCurrency(($booking->repository->getTotalCost() + $estimateSingleOccupancy) - $booking->repository->getDueTodayAmount()) }}
                                        payable by {{ $tour->final_payment->format('d M Y') }}
                                    </p>
                                    @endif
                                </div>
                                --}}
                                {{--
                                <div class="email-quote">
                                    <h6 class="sub-heading-6" wire:click="toggleCustomerForm">EMAIL Quote</h6>
                                    @if ($this->showCustomerForm ?? false)
                                        <div class="customer_profile">
                                            <button wire:loading.attr="disabled" style="width:fit-content"
                                                    wire:click="emailQuote" type="button" class="Go-next">
                                                <span wire:loading.remove>Send Quote</span>
                                                <span wire:loading>Sending...</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                --}}
                            </div>
                           @endif
                        </div>
                        @if($stage < 5)
                            @if($captchaEnabled)
                                <button type="button" class="next-button" onclick="handleNext()">
                            @else
                                <button type="button" class="next-button" wire:click="advance">
                            @endif
                            <span>
                                <span>NEXT</span>
                                <img src="{{ asset('icons/Right-arrow-mod.svg') }}" alt="right-arrow">
                            </span>
                            </button>
                            @error('common')
                            <div style="padding-top: 1rem; color: red;">
                                {{ $message }}
                            </div>
                            @enderror
                        @endif                        
                        {{ $sidebar ?? '' }}
                        {{-- <div style="padding-top: 1rem;">
                            <div id="stripe-hidden" style="visibility: hidden">
                                <div id="stripe-container"></div>
                                <button type="submit" class="next-button" id="pay-button">
                                    <span>
                                        <span>PAY</span>
                                    </span>
                                </button>
                                <div id="confirm-errors"></div>
                            </div>
                            <div id="airwallex-container" class="airwallex-content"></div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </section>
    </main>
    {{ $slot }}
    <footer>
        <div class="container">
            <div class="Go-back" wire:click="back">
                BACK
            </div>
            <div class="value">
                <div>
                    <h6 class="text-center">{{ $this->getTravellerCount() }}</h6>
                    <p>No. of Travellers</p>                    
                </div>
                <span></span>
                <div>
                    <h6>{{ $this->formatCurrency($booking->repository->getTotalCost() + $estimateSingleOccupancy) }}</h6>
                    <p>Total package cost</p>
                </div>
            </div>
            <div class="view-details">
                View package details
            </div>
                @if($stage < 5)
                    <div class="Go-next"
                        @if($captchaEnabled)
                            onclick="handleNext()"
                        @else
                            wire:click="advance"
                        @endif
                    >
                        NEXT
                    </div>
                @else
                    <div class="disable-next">NEXT</div>
                @endif
        </div>
    </footer>
</div>

<script>
function handleNext() {
    runRecaptcha('booking_advance', function(token) {
        Livewire.emit('advanceWithRecaptcha', token);
    });
}
</script>

<!-- Package Popup more information -->
<div class="more-package-info-popup">
    <div class="more-package-contain">
        <div class="more-package-block">
            <div class="popup-package-details">
                <div class="info-close-button"><img src="{{ asset('icons/Close-Button.svg') }}" alt="package-details"></div>
                <div class="popup-top-module">
                    <h4 class="sub-heading-4">Package details</h4>
                    <div class="hide-package-detail">Hide package details</div>
                </div>
                @if($tour->event?->image_url !== null)
                <div class="image-block">
                    <img src="{{ asset($tour->event?->image_url) }}" alt="package-details">
                </div>
                @endif
                <div class="popup-base-package">
                    <h2>{{ $tour->name }}</h2>
                    <p class="date-align"><img src="{{ asset('icons/checkin.svg') }}" alt="calendar"> {{ $tour->date_from?->format('d M Y') }} - {{ $tour->date_to?->format('d M Y') }}</p>
                    @if ($tour->description !== null)
                        <h6>Description</h6>
                        <span class="popup-tour-description"><p class="description">{!! $tour->description !!}</p></span>
                    @endif
                    <h6>Inclusions</h6>
                    <ul>
                        @foreach($tour->repository->getInclusions() as $inclusion)
                            <li>{{ $inclusion }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end of the Package popup more information -->
