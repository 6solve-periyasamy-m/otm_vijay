@php 
use App\Models\Helper\Enum\ActivityCategory;
@endphp
<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="3">
    <section class="package-container">
        <div class="container">
            <!-- Inside Container -->
            <div class="column left">
                <x:customer.booking.v3.tour-info :tour="$tour" :booking="$booking" :selectedCurrency="$selectedCurrency" /> 
                <div class="tickets">
                    <h2 class="sub-heading-2-p">Tickets</h2>
                    <p>Review your included tickets or upgrade.</p>
                    <div class="tickets-listing">
                        @foreach ($tour->activityInventoryTours()->where('tour_component_type', '=', 'Included')->get() as $tourComponent)
                            @continue($tourComponent->tour_component_type === 'Add-on' || $tourComponent->tour_component_type === 'Upgrade')
                            @if ($tourComponent->inventory->component->activity_category ===  ActivityCategory::MAIN)
                                <div class="single-block">
                                    <div class="quantity-show">
                                        <span><img src="{{ asset('images/Ticket-Streamline-Core.svg') }}" alt="icon"></span>
                                        <span><img src="{{ asset('images/Tickets-X-icon.svg') }}" alt="icon"></span>
                                        <span>{{ $this->getTravellerCount() }}</span>
                                    </div>
                                    <div class="ticket-heading">
                                        <div class="ticket-heading-module">
                                            <div class="content-module">
                                                <h6>{!! $tourComponent->inventory?->description !!}</h6>
                                                <p>{{ $tourComponent->inventory->component?->name}} </p>
                                            </div>
                                            <!-- <div class="ic-block">
                                                <div><img src="/images/Ticket-Icon.svg" alt="ticket-icon"></div>
                                            </div> -->
                                        </div>
                                        <select>
                                            <option value="{{ $tourComponent->inventory->id }}">{{ $tourComponent->inventory->starts_at->format('d M Y') }}</option>
                                        </select>
                                        @if ($tourComponent->inventory->component->seating)
                                        <div class="individual-module">
                                            <p>Seating</p>
                                            <select wire:change="adjustUpgrade($event.target.value)">
                                                <option value="{{ $tourComponent->id }}">{{ $tourComponent->inventory->component->seating?->name }} (Included)</option>
                                                @foreach ($tourComponent->upgrades ?? [] as $upgrade)
                                                    <option value="{{ $upgrade->upgrade->id }}">{{ $upgrade->upgrade->activityInventory->activity->seating?->name }} (+{{ fr_currency($upgrade->upgrade->tour_sales_price, $selectedCurrency) }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                        @if($tourComponent->inventory->component->session)
                                        <div class="individual-module">
                                            <p>Session</p>
                                            <div class="session-block">
                                                <label class="radio-option">
                                                    <input type="radio" checked name="session_{{$tourComponent->inventory->starts_at->timestamp}}" value="{{ $tourComponent->inventory->component->session?->name }}">
                                                    <span class="custom-radio"></span>
                                                    <span class="option-title">{{ $tourComponent->inventory->component->session?->name }}</span>
                                                </label>
                                            </div>                                                                            
                                        </div>
                                        @endif
                                        <button type="button" class="include-button {{ $tourComponent->upgrades->isNotEmpty() ? 'active' : '' }}">
                                            {{ $tourComponent->upgrades->isNotEmpty() ? 'Upgrade' : $tourComponent->tour_component_type }}
                                        </button> 
                                    </div>
                                </div>                            
                            @endif                                                        
                        @endforeach
                    </div>
                </div>
                @if($tour->activityInventoryTours()->where('tour_component_type', '=', 'Add-on')->count() > 0)
                <div class="tickets">
                    <h2 class="sub-heading-2-p">ADD A TICKET</h2>
                    <p>Want more tennis action? Add tickets now</p>
                    <div class="tickets-listing">
                    @foreach($tour->activityInventoryTours()->where('tour_component_type', '=', 'Add-on')->get() as $tourComponent)
                        @continue($tourComponent->inventory->component->activity_category !== ActivityCategory::MAIN)
                        @php
                            $activityInventory = $tourComponent->activityInventory;
                            $available = $activityInventory->repository->getAvailableStock();
                            $disabled = $available <= $booking->travellers()->count() ? 'element-disabled' : 'active';
                            $purchasePrice = round($booking->repository->convertBookingCurrency($activityInventory->purchase_price, $selectedCurrency), 2);
                        @endphp
                        <div class="single-block">
                                <div class="ticket-heading">
                                    <div class="ticket-heading-module">
                                        <div class="content-module">
                                            <h6>{!! $activityInventory->component->name !!}</h6>
                                            <p>{{ $activityInventory->component?->field1}}</p>
                                            <p>+{{ f_currency($booking->repository->convertBookingCurrency($activityInventory->purchase_price, $selectedCurrency) , $selectedCurrency)  }}</p>
                                        </div>
                                    </div>
                                    <select>
                                        <option value="{{$activityInventory->id }}">{{ $activityInventory->starts_at->format('d M Y') }}</option>
                                    </select>
                                    @if ($activityInventory->component->seating)
                                    <div class="individual-module">
                                        <p>Seating</p>
                                        <select>
                                            <option value="{{$activityInventory->id }}">{{ $activityInventory->component->seating->name }}</option>
                                        </select>
                                    </div>
                                    @endif
                                    @if($activityInventory->component->session)
                                    <div class="individual-module">
                                        <p>Session</p>
                                        <div class="session-block">
                                            <label class="radio-option">
                                                <input type="radio" checked id="{{ $activityInventory->id}}" name="session_{{$activityInventory->id}}" value="{{ $activityInventory->component->session?->name }}">
                                                <span class="custom-radio"></span>
                                                <span class="option-title">{{ $activityInventory->component->session?->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                <button type="button" class="include-button {{ $disabled }}" >Select</button>
                                <div class="individual-module">
                                    <p class="out-of-stock">{{ $available <= 0 ? 'Out of stock' : $activityInventory->repository->getAvailableStock() . ' Available' }}</p>
                                </div>
                                <div class="action-controls" style="display: none;">
                                    <div class="activity-action-block">
                                        <a href="javascript:void(0);" data-inventory-id="{{ $activityInventory->id }}" class="add-ticket-btn text-success me-2">Add</a>
                                        <a href="javascript:void(0);" class="cancel-ticket-btn text-danger">Cancel</a>
                                        <div class="feedback-message mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div> 
                </div>
                @endif
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
        <div class="container">
            <div class="ticket-pop-up-modal">
            <div class="ticket-contain-module">
                <div class="ticket-block">
                <div class="ticket-image">
                    <img src="/images/RLA_Seating-Original.png" alt="logo">
                </div>
                <div class="close-button">
                    <img src="/images/Close-Button.svg" alt="logo">
                </div>
                </div>
            </div>
            </div>

            <!-- End of inside container -->
        </div>
    </section>

    <script type="text/javascript">
        $(document).ready(function () {

            $('.include-button').on('click', function () {
                const parent = $(this).closest('.single-block');
                parent.find('.action-controls').slideDown();
            });
            $('.cancel-ticket-btn').on('click', function () {
                $(this).closest('.action-controls').slideUp();
            });
            @if($this->quote !== null)
            {{  dd($this->quote) }}
            $('.add-ticket-btn').on('click', function () {
                const parent = $(this).closest('.single-block');
                const inventoryId = $(this).data('inventory-id');
                const feedback = parent.find('.feedback-message');
                if (!inventoryId) {
                    feedback.html(`<div class="text-danger">No inventory ID found.</div>`);
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('api.quote.components.add', ['quote' => $this->quote, 'type' => 'activity']) }}",
                    dataType: "json",
                    data: {
                        "type": "Included",
                        "ids": [inventoryId],
                        "__api_token": '',
                    },
                    success: function () {
                        feedback.html(`<div class="text-success">Component added successfully!</div>`);
                    },
                    error: function (xhr) {
                        let msg = 'Error adding component';
                        if (xhr.status === 400) msg = 'Invalid component type.';
                        else if (xhr.status === 403) msg = 'Authentication expired. Please refresh.';
                        feedback.html(`<div class="text-danger">${msg}</div>`);
                    }
                });
            });
            @endif
        });
    </script>
</x-customer.booking.v3.layout>
