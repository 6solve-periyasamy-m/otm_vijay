@php
    $location = collect([$tour->city, $tour->country?->name])->filter()->implode(', ');
@endphp

<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="1">
    <section class="package-container first">
        <div class="container">
            <div class="column left">
                <x:customer.booking.v3.tour-info :tour="$tour" :booking="$booking" :selectedCurrency="$selectedCurrency" />                           
                <div class="top-form-contain">
                    <div class="email-quote">
                        <label for="email">Email *</label>
                        <input type="email" wire:model.lazy="lead.email_address" id="email" name="email">
                        @error('lead.email_address') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="email-quote"> <!-- customer_profile -->
                        <p>
                            <label for="firstname">First Name *</label>
                            <input type="text" id="firstname" wire:model.lazy="lead.first_name">
                            @error('lead.first_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </p>
                        <p>
                            <label for="lastname">Last Name *</label>
                            <input type="text" id="lastname" wire:model.lazy="lead.last_name">
                            @error('lead.last_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </p>
                        <p>
                            <label for="mobileno">Mobile No</label>
                            <input type="text" id="mobileno" wire:model.lazy="lead.mobile_number">
                        </p>
                    </div>
                </div>                
                <div class="no-of-travellers">
                    <h4 class="sub-heading-4">Number of Travellers</h4>
                    <div class="quantity">
                        <span class="minus" wire:click="removeTraveller()"><img src="{{ asset('icons/Minus.svg') }}" alt="minus"></span>
                        <span>|</span>
                        <span class="value">{{ $this->getTravellerCount() }}</span>
                        <span>|</span>
                        <span class="plus" wire:click="addTraveller()"><img src="{{ asset('icons/Plus.svg') }}" alt="plus"></span>
                    </div>
                </div>
                <div class="contact-block">
                    <p class="description">If you are a concession card holder, or booking with children under 12, get
                        in touch for a tailor-made package:</p>
                    <p class="phone">Domestic <a href="tel:1300 730 023">+1300 730 023</a></p>
                    <p class="phone">International <a href="tel:+61 2 7201 9353"> +61 2 7201 9353</a></p>
                    <p class="email">Email <a href="mailto:travel@kpt.com.au">travel@kpt.com.au</a></p>
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
                            <img src="{{ asset($tour->event->image_url) }}" class="package-image" alt="featured-img">
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
                            <h6 class="sub-heading-6  display-none">ADDITIONAL INCLUSIONS</h6>
                            <div class="select-currency">
                                @livewire("customer.booking.v3.currency-selector", ['currency' => $selectedCurrency], key('currency-selector'))
                                <div class="single">
                                    <p>Package price</p>
                                    <p>{{ $this->formatCurrency($booking->repository->getBasePrice()) }}</p>
                                </div>
                                @php $upgradePrice = $booking->repository->getUpgradeCosts(); @endphp
                                @if($upgradePrice > 0 || $upgradePrice < 0)
                                    <div class="single">
                                        <p>Upgrades Price</p>
                                        <p>{{ $this->formatCurrency($upgradePrice) }}</p>
                                    </div>
                                @endif
                                @php $singleOccupancy = $booking->repository->getSingleOccupancyAmount(); @endphp
                                @if($singleOccupancy > 0 || $singleOccupancy < 0)
                                    <div class="single">
                                        <p>Single occupancy surcharge</p>
                                        <p>{{ $this->formatCurrency($singleOccupancy) }}</p>
                                    </div>
                                @endif
                                @if($booking->repository->getTaxes() !== null)
                                    <div class="single">
                                        <p>{{ $tour->taxBracket()->name }} (Included)</p>
                                        <p>{{ $this->formatCurrency($booking->repository->getTaxes()) }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="total">
                                <div class="single">
                                    <p>Total</p>
                                    <p>{{ $this->formatCurrency($booking->repository->getTotalCost()) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="payment-method ">
                            <div class="payable-now">
                                <div class="single">
                                    <p>Payable now</p>
                                    <p>{{ $this->formatCurrency($booking->repository->getDueTodayAmount())  }}</p>
                                </div>
                                <p>Balance {{ $this->formatCurrency($booking->repository->getTotalCost()) }} payable by {{ $tour->final_payment->format('d M Y') }}</p>
                            </div>

                            <div class="email-quote">
                                <h6 class="sub-heading-6" wire:click="toggleCustomerForm">EMAIL Quote</h6>
                                @if ($showCustomerForm)
                                    <div class="customer_profile">
                                        <button wire:loading.attr="disabled" style="width:fit-content" wire:click="emailQuote" type="button" class="Go-next">
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
                    <button type="button" class="next-button" wire:click="advance">
                      <span>
                        <span>NEXT</span>
                        <img src="{{ asset('icons/Right-arrow-mod.svg') }}" alt="right-arrow">
                      </span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</x-customer.booking.v3.layout>
