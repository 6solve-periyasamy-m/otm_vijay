@php 
use App\Models\Helper\Enum\ActivityCategory;
@endphp
<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="4" payFull="{{ $payFull }}">
    <x-slot:left>
        <div class="additional-inclusions-module">
            <h2 class="sub-heading-2-p">ADDITIONAL INCLUSIONS</h2>
            <p>Enhance your experience with optional extras. Select from a range of add-ons to customise your package to suit your needs.</p>
            <div class="add-inclusion-block">
                @foreach ($tour->activityInventoryTours()->where('tour_component_type', '=', 'Included')->get() as $tourComponent)
                    @continue($tourComponent->inventory->component->activity_category !== ActivityCategory::NORMAL)
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
                            <p>
                                @if($tourComponent->tour_component_type === 'Included')
                                    Included in package
                                @else
                                    <span class="upgrade_tour_sales_price">+{{ $this->formatCurrency($tourComponent->tour_sales_price) }} </span>
                                @endif
                            </p>
                            @if($tourComponent->upgrades()->count() > 0)
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
                            @endif
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
                @foreach ($tour->merchandise()->where('tour_component_type', '=', 'Included')->get() as $tourComponent)
                    @continue($tourComponent->inventory->component->activity_category !== ActivityCategory::NORMAL)
                    @php $active = !($tourComponent->tour_component_type === 'Included' || $this->hasMerchandise($tourComponent)); @endphp
                    <div class="inclusion-single">
                        @php $imagePath = public_path($tourComponent->inventory->component->image_url ?? ''); @endphp
                        @if(!empty($tourComponent->inventory->component->image_url) && file_exists($imagePath))
                            <div class="inclusion-image">
                                <img src="{{ asset($tourComponent->inventory->component->image_url) }}" alt="{{ $tourComponent->inventory->component->name }}" title="{{ $tourComponent->inventory->component->name }}">
                            </div>
                        @endif
                        <div class="content-block">
                            <h6>{{ $tourComponent->inventory->component->name }}</h6>
                            <p>
                                @if($tourComponent->tour_component_type === 'Included')
                                    Included in package
                                @else
                                    <span class="upgrade_tour_sales_price">+{{ $this->formatCurrency($tourComponent->tour_sales_price) }} </span>
                                @endif
                            </p>
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
                                    @if($this->hasMerchandise($tourComponent))
                                        Remove
                                    @else
                                        +{{ $this->formatCurrency($tourComponent->tour_sales_price) }}
                                    @endif
                                @endif
                            </button>
                        </div>
                    </div>
                @endforeach
                @foreach($tour->activityInventoryTours()->where('tour_component_type', '=', 'Add-on')->get() as $tourComponent)
                    @continue($tourComponent->inventory->component->activity_category !== ActivityCategory::NORMAL)
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
                            <p>Number of guests</p>
                            <p style="color: var(--primary-color) !important;">{{ $tourComponent->tour_component_type === 'Included' ? 'Included in package' : '+' . $this->formatCurrency($tourComponent->tour_sales_price) . ' / Guest' }}</p>
                            <div class="quantity">
                                <span class="minus" wire:click="decreaseAddonCount('{{$tourComponent->repository->getComponentType()}}', {{$tourComponent->id}})"><img src="{{ asset('icons/Minus.svg') }}" alt="minus"></span>
                                <span>|</span>
                                <span class="value">{{ $this->getAddonCount($tourComponent->repository->getComponentType(), $tourComponent->id) }}</span>
                                <span>|</span>
                                <span class="plus" wire:click="increaseAddonCount('{{$tourComponent->repository->getComponentType()}}', {{$tourComponent->id}})"><img src="{{ asset('icons/Plus.svg') }}" alt="plus"></span>
                            </div>
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
                            <button type="button" class="include-button active" wire:click="toggleActivityAddon({{$tourComponent->id}})">
                                @if($tourComponent->tour_component_type === 'Included')
                                    Select
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
                @foreach ($tour->merchandise()->where('tour_component_type', '=', 'Add-on')->get() as $tourComponent)
                    @php $active = !($tourComponent->tour_component_type === 'Included' || $this->hasMerchandise($tourComponent)); @endphp
                    <div class="inclusion-single">
                        @php $imagePath = public_path($tourComponent->inventory->component->image_url ?? ''); @endphp
                        @if(!empty($tourComponent->inventory->component->image_url) && file_exists($imagePath))
                            <div class="inclusion-image">
                                <img src="{{ asset($tourComponent->inventory->component->image_url) }}" alt="{{ $tourComponent->inventory->component->name }}" title="{{ $tourComponent->inventory->component->name }}">
                            </div>
                        @endif
                        <div class="content-block">
                            <h6>{{ $tourComponent->inventory->component->name }}</h6>
                            <p>Number of guests</p>
                            <p style="color: var(--primary-color) !important;">{{ $tourComponent->tour_component_type === 'Included' ? 'Included in package' : '+' . $this->formatCurrency($tourComponent->tour_sales_price) . ' / Guest' }}</p>
                            <div class="quantity">
                                <span class="minus" wire:click="decreaseAddonCount('{{$tourComponent->repository->getComponentType()}}', {{$tourComponent->id}})"><img src="{{ asset('icons/Minus.svg') }}" alt="minus"></span>
                                <span>|</span>
                                <span class="value">{{ $this->getAddonCount($tourComponent->repository->getComponentType(), $tourComponent->id) }}</span>
                                <span>|</span>
                                <span class="plus" wire:click="increaseAddonCount('{{$tourComponent->repository->getComponentType()}}', {{$tourComponent->id}})"><img src="{{ asset('icons/Plus.svg') }}" alt="plus"></span>
                            </div>
                            @if (!empty($tourComponent->inventory->component?->description))
                                <a>More information</a>
                                <div class="additional-inclusion-popup">
                                    <div class="additional-contain">
                                        <div class="additional-block">
                                            <h4>{{ $tourComponent->inventory->component->name }}</h4>
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
                            <button type="button" class="include-button active" wire:click="toggleActivityAddon({{$tourComponent->id}})">
                                @if($tourComponent->tour_component_type === 'Included')
                                    Select
                                @else
                                    @if($this->hasMerchandise($tourComponent))
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
    </x-slot:left>
    <script>
        jQuery(document).on('click', '.inclusion-single .content-block a', function () {
            jQuery(this).closest('.content-block').find('.additional-inclusion-popup').css('display', 'flex')
        })
        jQuery(document).on('click', '.additional-inclusion-popup .add-close-button,.additional-inclusion-popup .cancel', function () {
            jQuery(this).closest('.content-block').find('.additional-inclusion-popup').css('display', 'none')
        })
    </script>
</x-customer.booking.v3.layout>
