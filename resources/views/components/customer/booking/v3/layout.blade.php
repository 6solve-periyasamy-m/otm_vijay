<div>
    <header>
        <div class="container">
            <div class="column">
                @if(isset($this->brand))
                    <img src="{{ asset($this->brand->alt_logo ?? $this->brand->logo) }}" alt="logo" title="{{ $this->brand->name }}">
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
        {{ $slot }}
    </main>

    @php
        $selectedCurrency = $this->booking->booking_currency ?? setting('system.currency');
    @endphp
    <footer>
        <div class="container">
            <div class="Go-back" wire:click="back">
                BACK
            </div>
            <div class="value">
                <div>
                    <h6>{{ f_currency($booking->repository->convertedBasePrice($selectedCurrency), $selectedCurrency) }}</h6>
                    <p>Per person, twin share</p>
                </div>
                <span></span>
                <div>
                    <h6>{{ f_currency($booking->repository->convertedTotalCost($selectedCurrency), $selectedCurrency) }}</h6>
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