@php 
use App\Models\Helper\Enum\ActivityCategory;
@endphp
<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="5">
    <section class="package-container">
        <div class="container">
            <div class="column left">
                <x:customer.booking.v3.tour-info :tour="$tour" :booking="$booking" :selectedCurrency="$selectedCurrency" />
                <div class="additional-inclusions-module">
                    <h2 class="sub-heading-2-p">ADDITIONAL INCLUSIONS</h2>
                    <p>Enhance your experience with optional extras. Select from a range of add-ons to customise your package to suit your needs.</p>
                    <div class="add-inclusion-block">
                        @foreach ($tour->activityInventoryTours as $activityInventory)
                            @if ($activityInventory->inventory->component->activity_category ===  ActivityCategory::NORMAL)
                                <div class="inclusion-single">
                                    @php $imagePath = public_path($activityInventory->inventory->component->image_url ?? ''); @endphp
                                    @if(!empty($activityInventory->inventory->component->image_url) && file_exists($imagePath))
                                        <div class="inclusion-image">
                                            <img src="{{ asset($activityInventory->inventory->component->image_url) }}" alt="{{ $activityInventory->inventory->component->name }}" title="{{ $activityInventory->inventory->component->name }}">
                                        </div>
                                    @endif
                                    <div class="content-block">
                                        @if($activityInventory->tour_component_type === 'Upgrade')
                                            <p>{{ $activityInventory->inventory?->starts_at->format('d M Y') }}</p>
                                        @endif
                                        <h6>{{ $activityInventory->inventory->component->name }}</h6>
                                        <p>Included in package </p>
                                        @if ($activityInventory->tour_component_type === 'Upgrade')
                                            <p class="no-of-guests">Number of guests</p>
                                            <p class="guest-value">+ {{ f_currency($booking->repository->convertBookingCurrency($activityInventory->inventory->purchase_price, $selectedCurrency) , $selectedCurrency) }} / Guest</p>
                                            <div class="quantity">
                                                <span class="minus" wire:click="removeGuest()"><img src="{{ asset('icons/Minus.svg') }}" alt="minus"></span>
                                                <span>|</span>
                                                <span class="value">{{ $this->getTravellerCount() }}</span>
                                                <span>|</span>
                                                <span class="plus" wire:click="addGuest()"><img src="{{ asset('icons/Plus.svg') }}" alt="plus"></span>
                                            </div>
                                        @endif
                                        @if (!empty($activityInventory->inventory->component?->description))
                                            <a>More information</a>
                                            <div class="additional-inclusion-popup">
                                                <div class="additional-contain">
                                                    <div class="additional-block">
                                                        <h4>{{ $activityInventory->inventory->component->name }}</h4>
                                                        {!! $activityInventory->inventory->component?->description !!}
                                                        <div class="add-cta">
                                                            <button type="button" class="cancel">Cancel</button>
                                                            <button type="button" class="Proceed">Proceed</button>
                                                        </div>
                                                        <div class="add-close-button">
                                                            <img src="{{ asset('icons/Close-Button.svg') }}" alt="package-details">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <button type="button" class="include-button {{ $activityInventory->tour_component_type === 'Upgrade' ? 'active' : '' }}">
                                            {{ $activityInventory->tour_component_type === 'Upgrade' ? 'Upgrade' : $activityInventory->tour_component_type }}
                                        </button>
                                    </div>
                                </div>
                            @endif
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
                            <h6 class="sub-heading-6">ADDITIONAL INCLUSIONS</h6>
                            <div class="select-currency">
                                @livewire("customer.booking.v3.currency-selector", ['currency' => $selectedCurrency], key('currency-selector'))
                                <div class="single">
                                    <p>Package price</p>
                                    <p>{{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getBasePrice(), $selectedCurrency), $selectedCurrency) }}</p>
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
                            <div class="ticket-upgrades">
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
                            <div class="additional-upgrades">
                                <h5>Additional upgrades</h5>
                                <div class="single">
                                    <p>Melbourne Foodie Walking Tour</p>
                                    <p>A$150</p>
                                </div>
                            </div>
                            <div class="total">
                                <div class="single">
                                    <p>Total</p>
                                    <p>{{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getTotalCost(), $selectedCurrency), $selectedCurrency) }}</p>
                                    </div>
                                <div class="single">
                                    <p>Starting package price</p>
                                    <p>{{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getBasePrice(), $selectedCurrency), $selectedCurrency) }}</p>
                                </div>
                                <div class="single">
                                    <p>Customisation cost</p>
                                    <p>$0</p>
                                </div>
                            </div>
                        </div>
                        <div class="payment-method ">
                            <div class="payable-now">
                                <div class="single">
                                    <p>Payable now</p>
                                    <p>{{ f_currency($booking->repository->convertBookingCurrency($booking->repository->getDueTodayAmount(), $selectedCurrency), $selectedCurrency)  }}</p>
                                </div>
                                <p>Balance {{ f_currency(($booking->repository->convertBookingCurrency($booking->repository->getTotalCost(), $selectedCurrency) - $booking->repository->convertBookingCurrency($booking->repository->getDueTodayAmount(), $selectedCurrency)), $selectedCurrency ) }} payable by {{ $tour->final_payment->format('d M Y') }}</p>
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
                            <img src="/images/Right-arrow-mod.svg" alt="right-arrow">
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </section>

<script>
    jQuery(document).on('click', '.inclusion-single .content-block a', function () {
        jQuery(this).closest('.content-block').find('.additional-inclusion-popup').css('display', 'flex')
    })
    jQuery(document).on('click', '.additional-inclusion-popup .add-close-button,.additional-inclusion-popup .cancel', function () {
        jQuery(this).closest('.content-block').find('.additional-inclusion-popup').css('display', 'none')
    })
</script>
</x-customer.booking.v3.layout>
