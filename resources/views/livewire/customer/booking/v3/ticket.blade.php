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
                        @php //dd($this->tour->activityInventoryTours); @endphp
                        @foreach ($tour->activityInventoryTours as $activityInventory) 
                            @continue($activityInventory->tour_component_type == 'Upgrade')
                            <div class="single-block">
                                <div class="quantity-show">
                                    <span><img src="{{ asset('images/Ticket-Streamline-Core.svg') }}" alt="icon"></span>
                                    <span><img src="{{ asset('images/Tickets-X-icon.svg') }}" alt="icon"></span>
                                    <span>{{ $this->getTravellerCount() }}</span>
                                </div>
                                <div class="ticket-heading">
                                    <div class="ticket-heading-module">
                                        <div class="content-module">
                                            <h6>{{ $activityInventory->inventory->component->name }}</h6>
                                            <p>Rod Laver Arena</p>
                                        </div>
                                        <div class="ic-block">
                                            <div><img src="/images/Ticket-Icon.svg" alt="ticket-icon"></div>
                                        </div>
                                    </div>
                                    <select>
                                        <option>{{ $activityInventory->inventory->starts_at->format('d M Y') }}</option>
                                    </select>
                                    @if ($activityInventory->inventory->component->seating)
                                    <div class="individual-module">
                                        <p>Seating</p>
                                        <select>
                                            <option value="{{ $activityInventory->inventory->id }}">{{ $activityInventory->inventory->component->seating?->name }}</option>
                                            @if ($activityInventory->upgrades)
                                                @foreach ($activityInventory->upgrades as $upgrade)
                                                    <option value="{{ $upgrade->upgrade->activityInventory->id }}">{{ $upgrade->upgrade->activityInventory->activity->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @endif
                                    @if($activityInventory->inventory->component->session)
                                    <div class="individual-module">
                                        <p>Session</p>
                                        <div class="session-block">
                                            <label class="radio-option">
                                                <input type="radio" checked name="session_{{$activityInventory->inventory->starts_at->timestamp}}" value="{{ $activityInventory->inventory->component->session?->name }}">
                                                <span class="custom-radio"></span>
                                                <span class="option-title">{{ $activityInventory->inventory->component->session?->name }}</span>
                                            </label>
                                        </div>
                                        <button type="button" class="include-button">{{$activityInventory->tour_component_type}}</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tickets">
                    <h2 class="sub-heading-2-p">ADD A TICKET</h2>
                    <p>Want more tennis action? Add tickets now</p>
                    <div class="tickets-listing">
                    @foreach(\App\Repository\Model\Activity\ActivityInventoryRepository::getBetweenDates($tour->date_from, $tour->date_to, $tour->repository) as $activityInventory)
                        @if($activityInventory->component->activityType->name === 'Add On')
                            @php
                                $available = $activityInventory->repository->getAvailableStock();
                                $disabled = $available <= 0 ? 'element-disabled' : 'active';
                            @endphp
                            <div class="single-block">
                                    <div class="ticket-heading">
                                        <div class="ticket-heading-module">
                                            <div class="content-module">
                                                <h6>{{ $activityInventory->component->name }} {{ $activityInventory->id }}</h6>
                                                <p>Rod Laver Arena</p>
                                                <p>+{{ preg_replace('/\.00$/', '', f_currency($activityInventory->purchase_price))}}</p>
                                            </div>
                                            <div class="ic-block">
                                                <div><img src="/images/Ticket-Icon.svg" alt="ticket-icon"></div>
                                            </div>
                                        </div>
                                        <select>
                                            <option>{{ $activityInventory->starts_at->format('d M Y') }}</option>
                                        </select>
                                        @if ($activityInventory->component->seating)
                                        <div class="individual-module">
                                            <p>Seating</p>
                                            <select>
                                            <option>{{ $activityInventory->component->seating->name }}</option>
                                            </select>
                                        </div>
                                        @endif
                                        @if($activityInventory->component->session)
                                        <div class="individual-module">
                                            <p>Session</p>
                                            <div class="session-block">
                                                <label class="radio-option">
                                                    <input type="radio" checked name="session_{{$activityInventory->starts_at->timestamp}}" value="{{ $activityInventory->component->session?->name }}">
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
                        @endif
                    @endforeach
                    </div> 
                </div>
            </div>
            <div class="column right">
            <div class="package-details">
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
                    <div class="ticket-upgrades">
                    <h5>Ticket upgrades</h5>
                    <div class="single">
                        <p>Ticket alterations</p>
                        <p>A$500</p>
                    </div>activityInventory
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
                        The remaining balance of A$8,563 will be automatically charged to the same payment method on 24
                        June 2024
                        </div>
                    </div>
                    <div class="price">A$8,563</div>
                    </div>
                    <div class="card-block display-none">
                    <div class="card-type active">
                        <img src="/images/card.svg" alt="Debit card">
                        <p>Credit / Debit card</p>
                    </div>
                    <div class="card-type">
                        <img src="/images/document-text.svg" alt="Direct Debit">
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
                    <!-- <form style="display:none;">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">
                    </form> -->
                    </div>
                </div>
                </div>
                <button type="button" class="next-button">
                <span>
                    <span>NEXT</span>
                    <img src="/images/Right-arrow-mod.svg" alt="right-arrow">
                </span>
                </button>
                <!-- <span class="accomodation-travel-date-error">
                <span>
                    <img src="/images/Noti-Icon.svg" alt="icon">
                </span>
                <span>
                    Total number of travellers vs. the number of guests you have selected for rooms does not match -
                    please
                    update your room selection to proceed
                </span> -->
                </span>
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
</x-customer.booking.v3.layout>

<script type="text/javascript">
    $(document).ready(function () {

        $('.include-button').on('click', function () {
            const parent = $(this).closest('.single-block');
            parent.find('.action-controls').slideDown();
        });
        $('.cancel-ticket-btn').on('click', function () {
            $(this).closest('.action-controls').slideUp();
        });

        $('.add-ticket-btn').on('click', function () {
            const parent = $(this).closest('.single-block');
            const inventoryId = $(this).data('inventory-id');
            console.log("YESSSSSSSSSSSSS" + inventoryId);
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
        


        
    });
</script>