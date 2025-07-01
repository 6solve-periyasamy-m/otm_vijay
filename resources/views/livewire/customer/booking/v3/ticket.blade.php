@php 
use App\Models\Helper\Enum\ActivityCategory;
@endphp
<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="3" payFull="{{ $payFull }}">
    <x-slot:left>
        <div class="tickets">
            <h2 class="sub-heading-2-p">Tickets</h2>
            <p>Review your included tickets or upgrade.</p>
            <div class="tickets-listing">
                @foreach ($tour->activityInventoryTours()->where('tour_component_type', '=', 'Included')->get() as $tourComponent)                    
                    @continue($tourComponent->tour_component_type === 'Add-on' || $tourComponent->tour_component_type === 'Upgrade')
                    @if ($tourComponent->inventory->component->activity_category ===  ActivityCategory::MAIN)
                        @php
                            $activeUpgrade = $tourComponent->repository->getActiveUpgrade($this->booking->leadTraveller)?->get() ?? $tourComponent;
                        @endphp
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
                                        <p>{{ $tourComponent->inventory->component?->field1 }} </p>
                                        <div>
                                            @if($activeUpgrade->tour_component_type === 'Included')
                                                <p>Included</p>
                                            @else
                                                <p><span class="upgrade_tour_sales_price">+{{ $this->formatCurrency($activeUpgrade->tour_sales_price) }} </span> per person price</p>
                                                <p><span class="upgrade_tour_sales_price">+{{ $this->formatCurrency($activeUpgrade->tour_sales_price * $this->getTravellerCount()) }} </span>Total</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if (!empty($tourComponent->inventory->component->SeatingMap))
                                    @php //dd($tourComponent->inventory->component); @endphp
                                        @php $seatingMap = $tourComponent->inventory->component->SeatingMap; @endphp
                                        @if($seatingMap->image_url && !empty($seatingMap->image_url))
                                            <div class="seating-map-wrapper">
                                                <div class="seating-map-wrapper-img-block">
                                                    <img src="/images/Ticket-Icon.svg" alt="ticket-icon" class="seating-map-link" alt="Stadium" title="Stadium">
                                                </div>
                                                <div class="ticket-pop-up-modal" style="display:none;">
                                                    <div class="ticket-contain-module">
                                                        <div class="ticket-block">
                                                            <div class="ticket-image">
                                                                <img src="{{ asset($seatingMap->image_url) }}" alt="{{ $seatingMap->name }}" title="{{ $seatingMap->name }}">
                                                            </div>
                                                            <div class="map-close-button">
                                                                <img src="{{ asset('icons/Close-Button.svg') }}" alt="Close">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <select>
                                    <option value="{{ $tourComponent->inventory->id }}">
                                        {{ $tourComponent->inventory->starts_at->format('d M Y') }}
                                        @if(!$tourComponent->inventory->ends_at->isSameDay($tourComponent->inventory->starts_at))
                                            - {{ $tourComponent->inventory->ends_at->format('d M Y') }}
                                        @endif
                                    </option>
                                </select>
                                @if ($tourComponent->inventory->component->seating)
                                    <div class="individual-module">
                                        <p>Category (Select Upgrade)</p>
                                        <select wire:model="ticketUpgrades.{{$tourComponent->id}}" wire:change="adjustActivityUpgrade($event.target.value)">
                                            <option value="{{ $tourComponent->id }}"
                                                    @if($this->hasActivity($tourComponent)) selected @endif>{{ $tourComponent->inventory->component->seating?->name }}
                                                (Included)
                                            </option>
                                            @foreach ($tourComponent->upgrades ?? [] as $upgrade)
                                                <option value="{{ $upgrade->upgrade->id }}"
                                                        @if($this->hasActivity($upgrade->upgrade)) selected @endif>{{ $upgrade->upgrade->activityInventory->activity->seating?->name }}
                                                    {{-- (+{{ $this->formatCurrency($upgrade->upgrade->tour_sales_price) }}
                                                    ) --}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                @if($tourComponent->inventory->component->session)
                                    <div class="individual-module">
                                        <p>Session</p>
                                        <div class="session-block">
                                            <label class="radio-option">
                                                <input type="radio" checked
                                                       name="session_{{$tourComponent->inventory->starts_at->timestamp}}"
                                                       value="{{ $tourComponent->inventory->component->session?->name }}">
                                                <span class="custom-radio"></span>
                                                <span class="option-title">{{ $tourComponent->inventory->component->session?->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <button type="button" class="include-button {{ $tourComponent->upgrades->isNotEmpty() ? 'active' : '' }}" wire:click="upgradeActivity({{ $tourComponent->id }})">
                                    @if($activeUpgrade->id !== $tourComponent->id)
                                        Upgraded
                                    @else
                                        {{ $tourComponent->upgrades->isNotEmpty() ? 'Upgrade' : $tourComponent->tour_component_type }}
                                    @endif
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
                <p>Want more action? Add tickets now</p>
                <div class="tickets-listing">
                    @foreach($tour->activityInventoryTours()->where('tour_component_type', '=', 'Add-on')->get() as $tourComponent)
                        @continue($tourComponent->inventory->component->activity_category !== ActivityCategory::MAIN)
                        @php
                            $available = $tourComponent->inventory->repository->getAvailableStock();
                            $disabled = $available <= $this->getTravellerCount() ? 'element-disabled' : 'active';
                            $purchasePrice = round($booking->repository->convertBookingCurrency($tourComponent->inventory->purchase_price, $selectedCurrency), 2);
                        @endphp
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
                                        <p>{{ $tourComponent->inventory->component?->field1}}</p>
                                        <div>
                                            @if($activeUpgrade->tour_component_type === 'Included')
                                                <p>Included</p>
                                            @else
                                                <p><span class="upgrade_tour_sales_price">+{{ $this->formatCurrency($activeUpgrade->tour_sales_price) }} </span> per person price</p>
                                                <p><span class="upgrade_tour_sales_price">+{{ $this->formatCurrency($activeUpgrade->tour_sales_price * $this->getTravellerCount()) }} </span>Total</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if (!empty($tourComponent->inventory->component->SeatingMap))
                                        @php $seatingMap = $tourComponent->inventory->component->SeatingMap; @endphp
                                        @if($seatingMap->image_url && !empty($seatingMap->image_url))
                                            <div class="seating-map-wrapper">
                                                <div class="seating-map-wrapper-img-block">
                                                    <img src="/images/Ticket-Icon.svg" alt="ticket-icon" class="seating-map-link">
                                                </div>
                                                <div class="ticket-pop-up-modal" style="display:none;">
                                                    <div class="ticket-contain-module">
                                                        <div class="ticket-block">
                                                            <div class="ticket-image">
                                                                <img src="{{ asset($seatingMap->image_url) }}" alt="{{ $seatingMap->name }}" title="{{ $seatingMap->name }}">
                                                            </div>
                                                            <div class="map-close-button">
                                                                <img src="{{ asset('icons/Close-Button.svg') }}" alt="Close">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <select>
                                    <option value="{{$tourComponent->inventory->id }}">{{ $tourComponent->inventory->starts_at->format('d M Y') }}</option>
                                </select>
                                @if ($tourComponent->inventory->component->seating)
                                    <div class="individual-module">
                                        <p>Category</p>
                                        <select>
                                            <option value="{{$tourComponent->inventory->id }}">{{ $tourComponent->inventory->component->seating->name }}</option>
                                        </select>
                                    </div>
                                @endif
                                @if($tourComponent->inventory->component->session)
                                    <div class="individual-module">
                                        <p>Session</p>
                                        <div class="session-block">
                                            <label class="radio-option">
                                                <input type="radio" checked
                                                       id="{{ $tourComponent->inventory->id}}"
                                                       name="session_{{$tourComponent->inventory->id}}"
                                                       value="{{ $tourComponent->inventory->component->session?->name }}">
                                                <span class="custom-radio"></span>
                                                <span class="option-title">{{ $tourComponent->inventory->component->session?->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <button type="button" class="include-button {{ $disabled }}"
                                        wire:click="toggleActivityAddon({{ $tourComponent->id }})">
                                    {{ $this->hasActivity($tourComponent) ? 'Added' : 'Add'}}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </x-slot:left>
    <script type="text/javascript">

        jQuery(document).on('click', '.ticket-heading-module .seating-map-wrapper img', function () {
           jQuery(this).closest('.seating-map-wrapper').find('.ticket-pop-up-modal').css('display', 'flex')
        })
        jQuery(document).on('click', '.ticket-pop-up-modal .map-close-button', function () {
            jQuery(this).closest('.ticket-heading-module').find('.ticket-pop-up-modal').css('display', 'none')
        })

        $(document).ready(function () {

            $('.include-button').on('click', function () {
                const parent = $(this).closest('.single-block');
                parent.find('.action-controls').slideDown();
            });
            $('.cancel-ticket-btn').on('click', function () {
                $(this).closest('.action-controls').slideUp();
            });
            @if($this->quote?->id !== null)
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
