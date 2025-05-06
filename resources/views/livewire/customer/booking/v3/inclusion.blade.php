@php 
use App\Models\Helper\Enum\ActivityCategory;
@endphp
<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="4">
    <section class="package-container">
        <div class="container">
            <div class="column left">
                <x:customer.booking.v3.tour-info :tour="$tour" :booking="$booking" :selectedCurrency="$selectedCurrency" />
                <div class="additional-inclusions-module">
                    <h2 class="sub-heading-2-p">ADDITIONAL INCLUSIONS</h2>
                    <p>Enhance your experience with optional extras. Select from a range of add-ons to customise your package to suit your needs.</p>
                    <div class="add-inclusion-block">
                        @foreach ($tour->activityInventoryTours as $tourComponent)
                            @continue($tourComponent->inventory->component->activity_category !== ActivityCategory::NORMAL || $tourComponent->tour_component_type === 'Upgrade')
                            @php $active = !($tourComponent->tour_component_type === 'Included' || $this->hasActivity($tourComponent)); @endphp
                            <div class="inclusion-single">
                                @php $imagePath = public_path($tourComponent->inventory->component->image_url ?? ''); @endphp
                                @if(!empty($tourComponent->inventory->component->image_url) && file_exists($imagePath))
                                    <div class="inclusion-image">
                                        <img src="{{ asset($tourComponent->inventory->component->image_url) }}" alt="{{ $tourComponent->inventory->component->name }}" title="{{ $tourComponent->inventory->component->name }}">
                                    </div>
                                @endif
                                <div class="content-block">
                                    <h6>{{ $tourComponent->inventory->component->name }}</h6>
                                    <p>{{ $tourComponent->tour_component_type === 'Included' ? 'Included in package' : '+' . $this->formatCurrency($tourComponent->tour_sales_price) }}</p>
                                    <select style="max-width: 100%" wire:change="adjustActivityUpgrade($event.target.value)">
                                        <option value="{{ $tourComponent->id }}"
                                                @if($this->hasActivity($tourComponent)) selected @endif>{{ $tourComponent->inventory->component->name }}
                                            (Included)
                                        </option>
                                        @foreach ($tourComponent->upgrades ?? [] as $upgrade)
                                            <option value="{{ $upgrade->upgrade->id }}"
                                                    @if($this->hasActivity($upgrade->upgrade)) selected @endif>{{ $upgrade->upgrade->inventory->component->name }}
                                                (+{{ $this->formatCurrency($upgrade->upgrade->tour_sales_price) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @if (!empty($tourComponent->inventory->component?->description))
                                        <a>More information</a>
                                        <div class="additional-inclusion-popup">
                                            <div class="additional-contain">
                                                <div class="additional-block">
                                                    <h4>{{ $tourComponent->inventory->component->name }}</h4>
                                                    {!! $tourComponent->inventory->component?->description !!}
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
                                    <button type="button" class="include-button {{ $active ? 'active' : '' }}" wire:click="toggleActivityAddon({{$tourComponent->id}})">
                                        @if($tourComponent->tour_component_type === 'Included')
                                            Included
                                        @else
                                            @if($this->hasActivity($tourComponent))
                                                Remove
                                            @else
                                                +{{ $this->formatCurrency($tourComponent->tour_sales_price) }}
                                            @endif
                                        @endif
                                    </button>
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
                                    <p>{{ $this->formatCurrency($booking->repository->getBasePrice()) }}</p>
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
                            <div class="room-upgrades">
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
                            @php $default = $this->getDefaultHotel()->component; @endphp
                            <div class="Hotel" style="display:none;">
                                <h5>Hotel</h5>
                                <div class="single">
                                    <p>{{$default->name}}, {{ $default->address?->town }}</p>
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
                                    <p>{{ $this->formatCurrency($booking->repository->getTotalCost()) }}</p>
                                </div>
                                @if($booking->repository->getTaxes() !== null)
                                    <div class="single">
                                        <p>{{ $tour->taxBracket()->name }} (Included)</p>
                                        <p>{{ $this->formatCurrency($booking->repository->getTaxes()) }}</p>
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
                        </div>
                        <div class="payment-method ">
                            <div class="payable-now">
                                <div class="single">
                                    <p>Payable now   ({{ $booking->tour?->deposit_percentage }}%)</p>
                                    <p>{{ $this->formatCurrency($booking->repository->getDueTodayAmount())  }}</p>
                                </div>
                                <p>Balance {{ $this->formatCurrency($booking->repository->getTotalCost() - $booking->repository->getDueTodayAmount()) }} payable by {{ $tour->final_payment->format('d M Y') }}</p>
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
