<div>
    <header>
        <div class="container">
            <div class="column">
                <img src="{{ asset($brand->alt_logo ?? $brand->logo) }}" alt="logo">
            </div>
            <div class="column right">
                <p>Require assistance?</p>
                <a href="tel:{{$brand->phone}}"><span><img src="{{ asset('icons/Call-Icon.svg') }}"
                                                           alt="logo"></span><span>{{$brand->phone}}</span></a>
            </div>
        </div>
    </header>

    <main>
        <section class="secure-booking">
            <div class="container">
                <div class="heading">
                    <div class="breadcrumbs"><span><img src="{{ asset('icons/Arrow-left.svg') }}"
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

    <footer>
        <div class="container">
            <div class="Go-back" wire:click="back">
                BACK
            </div>
            <div class="value">
                <div>
                    <h6>A$2,995</h6>
                    <p>Per person, twin share</p>
                </div>
                <span></span>
                <div>
                    <h6>A$2,995</h6>
                    <p>Per person, twin share</p>
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