@php
    $location = collect([$tour->city, $tour->country?->name])->filter()->implode(', ');
    $selectedCurrency = $this->booking->currency?->code ?? setting('system.currency');
@endphp
<div>
    <header>
        <div class="container">
            <div class="column">
                @if(isset($this->brand))
                    <img src="{{ asset($this->brand->alt_logo ?? $this->brand->logo) }}" style="max-height: 75px" alt="logo" title="{{ $this->brand->name }}">
                @endif
            </div>
            <div class="column right">
                {{-- TODO: Remove when complete --}}
                @if(config('app.features.bleeding-edge', false) === true)
                    <a target="_blank" href="{{ route('admin.booking.view', ['booking' => $this->booking]) }}">Preview Booking in Admin</a>
                @else
                    <p>Require assistance?</p>
                @endif
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
                    <div class="breadcrumbs" wire:click="back"><span><img src="{{ asset('icons/Arrow-left.svg') }}"
                                                        alt="left-arrow"></span><span>BACK</span></div>
                    <h1>Secure Booking</h1>
                </div>
                <div class="timeline">
                    <div class="step @if($stage === 1) active @elseif($stage > 1) completed @endif">
                        <div class="circle">
                            <span>01</span>
                        </div>
                        <div class="label">Guests</div>
                    </div>
                    <div class="step @if($stage === 2) active @elseif($stage > 2) completed @endif">
                        <div class="circle">
                            <span>02</span>
                        </div>
                        <div class="label">Accommodation</div>
                    </div>
                    <div class="step @if($stage === 3) active @elseif($stage > 3) completed @endif">
                        <div class="circle">
                            <span>03</span>
                        </div>
                        <div class="label">Ticket(s)</div>
                    </div>
                    <div class="step @if($stage === 4) active @elseif($stage > 4) completed @endif">
                        <div class="circle">
                            <span>04</span>
                        </div>
                        <div class="label">Additional Inclusions</div>
                    </div>
                    <div class="step @if($stage === 5) active @elseif($stage > 5) completed @endif">
                        <div class="circle">
                            <span>05</span>
                        </div>
                        <div class="label">Details</div>
                    </div>
                    <div class="step @if($stage === 6) active @elseif($stage > 6) completed @endif">
                        <div class="circle">
                            <span>06</span>
                        </div>
                        <div class="label">Confirmation</div>
                    </div>
                </div>
            </div>
        </section>
        <section class="package-container first">
            <div class="container">
                <div class="column left">
                    <div class="tour-details">
                        <h2 class="sub-heading-2">{{ $tour->name }}</h2>
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
                            <div class="image-block">
                                <img src="{{ asset('images/sportEvent.png') }}" alt="package-details">
                            </div>
                            <div class="base-package">
                                <h6 class="sub-heading-6">BASE PACKAGE</h6>
                                <h2>{{ $tour->name }}</h2>
                                <p class="date-align"><img src="{{ asset('icons/checkin.svg') }}" alt="calendar"> {{ $tour->date_from?->format('d M Y') }} - {{ $tour->date_to?->format('d M Y') }}</p>
                                <ul>
                                    @foreach($tour->repository->getInclusions() as $inclusion)
                                        <li>{{ $inclusion }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @if($hidePrices ?? true)
                            <div class="additional-inclusions">
                                @if($stage !== 1)
                                    <h6 class="sub-heading-6">ADDITIONAL INCLUSIONS</h6>
                                @endif
                                <div class="select-currency">
                                    @livewire("customer.booking.v3.currency-selector", ['currency' => $selectedCurrency], key('currency-selector'))
                                    <div class="single">
                                        <p>Package price</p>
                                        <p>{{ $this->formatCurrency($booking->repository->getBasePrice()) }}</p>
                                    </div>
                                    <div class="single">
                                        <p>Number of packages - {{ $this->getTravellerCount() }}</p>
                                        <p>{{ $this->formatCurrency($booking->repository->getBasePrice()) }}</p>
                                    </div>
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
                                @if(count($upgrades['rooms']) > 0)
                                <div class="room-upgrades">
                                    <h5>Room upgrades</h5>
                                    @foreach($upgrades['rooms'] as $room)
                                        <div class="single">
                                            <p>{{ $room['description'] }}</p>
                                            <p>{{ $this->formatCurrency($room['cost']) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                                @php $default = $this->getDefaultHotel()->component; @endphp
                                <div class="Hotel" style="display:none;">
                                    <h5>Hotel</h5>
                                    <div class="single">
                                        <p>{{$default->name}}, {{ $default->address?->town }}</p>
                                        <p>Price included</p>
                                    </div>
                                </div>
                                @if(count($upgrades['tickets']) > 0)
                                <div class="ticket-upgrades txt-org">
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
                                <div class="additional-upgrades">
                                    <h5>Additional upgrades</h5>
                                    @foreach($upgrades['inclusions'] as $room)
                                        <div class="single">
                                            <p>{{ $room['description'] }}</p>
                                            <p>{{ $this->formatCurrency($room['cost']) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                                <div class="total">
                                    <div class="single">
                                        <p>Total</p>
                                        <p>{{ $this->formatCurrency($booking->repository->getTotalCost()) }}</p>
                                    </div>
                                    <div class="single">
                                        <p>Base Package Price</p>
                                        <p>{{ $this->formatCurrency($booking->repository->getBasePrice()) }}</p>
                                    </div>
                                    @php $upgradePrice = $booking->repository->getUpgradeCosts(); @endphp
                                    @if($upgradePrice > 0 || $upgradePrice < 0)
                                        <div class="single">
                                            <p>Upgrades & Add Ons</p>
                                            <p>{{ $this->formatCurrency($upgradePrice) }}</p>
                                        </div>
                                    @endif
                                    @if($booking->repository->getTaxes() !== null)
                                        <div class="single">
                                            <p>{{ $tour->taxBracket()->name }} (Included)</p>
                                            <p>{{ $this->formatCurrency($booking->repository->getTaxes()) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="payment-method ">
                                @if($stage === 5)
                                    <h6 class="sub-heading-6">PAYMENT METHOD</h6>
                                    <div class="option-wrapper">
                                        <label class="radio-option" wire:click="payFull()">
                                            <input type="radio" name="payment" @if($payFull) checked @endif>
                                            <span class="custom-radio"></span>
                                            <span class="option-title">Pay in full</span>
                                        </label>
                                        <div class="price">{{ $this->formatCurrency($this->booking->repository->getTotalCost()) }}</div>
                                    </div>
                                    <div class="option-wrapper">
                                        <div>
                                            <label class="radio-option" wire:click="payDueToday()">
                                                <input type="radio" name="payment" @if(!$payFull) checked @endif>
                                                <span class="custom-radio"></span>
                                                <span class="option-title">Pay a {{ $booking->tour?->deposit_percentage }}% deposit now, and the rest later</span>
                                            </label>
                                            <div class="option-subtext">
                                                You will receive a reminder to pay the remaining balance of {{ $this->formatCurrency($this->booking->repository->getTotalCost() - $this->booking->repository->getDueTodayAmount()) }} before {{ $tour->final_payment->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="price">{{ $this->formatCurrency($this->booking->repository->getDueTodayAmount()) }}</div>
                                    </div>
                                    <div class="card-block">
                                        <div class="card-type active">
                                            <img src="{{ asset('icons/card.svg') }}" alt="Debit card">
                                            <p>Credit / Debit card</p>
                                        </div>
                                        <div class="card-type">
                                            <img src="{{ asset('icons/document-text.svg') }}" alt="Direct Debit">
                                            <p>Invoice - Direct Debit</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="payable-now">
                                    <div class="single">
                                        <p>Payable now @if(!$payFull)({{ $booking->tour?->deposit_percentage }}%)@endif</p>
                                        <p>{{ $this->formatCurrency($payFull ? $booking->repository->getTotalCost() : $booking->repository->getDueTodayAmount())  }}</p>
                                    </div>
                                    @if(!$payFull)
                                    <p>
                                        Balance {{ $this->formatCurrency($booking->repository->getTotalCost() - $booking->repository->getDueTodayAmount()) }}
                                        payable by {{ $tour->final_payment->format('d M Y') }}
                                    </p>
                                    @endif
                                </div>
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
                        <button type="button" class="next-button" wire:click="advance">
                          <span>
                            <span>NEXT</span>
                            <img src="{{ asset('icons/Right-arrow-mod.svg') }}" alt="right-arrow">
                          </span>
                        </button>
                        @else
                            <div class="acc-tp-cond">
                                <label class="contain-v"><span class="fnal-txt">I accept the <a href="https://www.kpt.com.au/terms-and-conditions/" target="_blank">Terms & Conditions</a></span>
                                    <input type="checkbox" wire:model="terms">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="submit-btn-cls">
                                <div class="inner">
                                    <input class="submit-btn" wire:click="advance" type="submit" value="Checkout">
                                </div>
                            </div>
                        @endif
                        @error('common')
                        <div style="padding-top: 1rem; color: red;">
                            {{ $message }}
                        </div>
                        @enderror
                        {{ $sidebar ?? '' }}
                        <div style="padding-top: 1rem;">
                            <div id="stripe-hidden" style="visibility: hidden">
                                <div id="stripe-container"></div>
                                <button id="pay-button">Pay</button>
                                <div id="confirm-errors"></div>
                            </div>
                            <div id="airwallex-container" class="airwallex-content"></div>
                        </div>
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
                    <h6>{{ $this->formatCurrency($tour->base_price_per_person) }}</h6>
                    <p>Per person</p>
                </div>
                <span></span>
                <div>
                    <h6>{{ $this->formatCurrency($booking->repository->getTotalCost()) }}</h6>
                    <p>Total package cost</p>
                </div>
            </div>
            <div class="view-details">
                View package details
            </div>
            <div class="Go-next" wire:click="advance">
                NEXT
            </div>
        </div>
    </footer>
</div>