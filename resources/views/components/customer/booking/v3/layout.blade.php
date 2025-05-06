@php
    $location = collect([$tour->city, $tour->country?->name])->filter()->implode(', ');
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
                            <p class="dollar">From {{ $this->formatCurrency($booking->repository->getBasePrice()) }} / person twin share</p>
                        </div>
                    </div>
                    {{ $left }}
                </div>
                <div class="column right">
                    {{ $sidebar }}
                </div>
            </div>
        </section>
    </main>
    {{ $slot }}
    @php
        $selectedCurrency = $this->booking->currency?->code ?? setting('system.currency');
    @endphp
    <footer>
        <div class="container">
            <div class="Go-back" wire:click="back">
                BACK
            </div>
            <div class="value">
                <div>
                    <h6>{{ $this->formatCurrency($tour->base_price_per_person) }}</h6>
                    <p>Per person, twin share</p>
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