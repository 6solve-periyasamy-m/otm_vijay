<div class="row">
    <div class="left-col">
        <div class="contain">
           <!-- The event name mobile update -->
            <!-- <div class="evnt-name">
            </div> -->
            <!-- The event name mobile update -->
            <h3>Your trip details</h3>
            <h3 class="head-evnt">{{ $tour->event?->name }}</h3>
            <p class="psg-det">PASSENGER DETAILS</p>

            <div class="top-form-contain">
                <div class="form-field">
                    <input type="email" wire:model="lead.email_address" placeholder="Email*" required>
                    @error('lead.email_address') <label class="error-label">{{ $message }}</label> @enderror
                </div>
            </div>
            <!-- <- Additional Travellers -->
            <div class="second-block tra-det">
                <p>TRAVELLER/s</p>
                <div class="inner-block">
                    <div class="left-col">
                        <h6>Adults</h6>
                        <p>Ages 13 or above</p>
                    </div>
                    <div class="right">
                        <div class="inn">
                            <span class="Min" wire:click="removeTraveller()"><img src="{{ asset('css/booking/icon/minus.svg') }}" alt="minus" /></span>
                            <span class="No"><span>|</span> <span class="text">{{ $this->getTravellerCount() }}</span> <span>|</span></span>
                            <span class="Max" wire:click="addTraveller()"><img src="{{ asset('css/booking/icon/Plus.svg') }}" alt="minus" /></span>
                        </div>
                    </div>
                </div>
                <div class="contain">
                    <p>Travelling with children?<a href="https://www.kpt.com.au/contact-us/" target="_blank">Get in
                                                                                                             touch</a>
                       for a custom package.</p>
                </div>
            </div>

            <!-- <- Rooming -->
            <div class="second-block rme-det">
                
                <p>ROOMS</p>
                @foreach($tour->repository->getHotels() as $hotel)
                    <div wire:ignore class="hotel-details">
                        <h6>{{ $hotel->name }}</h6>
                        <div class="information-hover" data-action="hover" data-target="accommodation-{{$hotel->id}}">
                            <img src="{{ asset('css/booking/icon/Icon.svg') }}" alt="tip-img">
                        </div>
                        <div class="accommodation-details-hover accommodation-{{$hotel->id}}">
                            <div class="contain">
                                <div class="first-block">
                                    <h6>Hotel details</h6>
                                    <div class="full">
                                        <div class="left-col">
                                            <img class="package-image" src="{{ asset($hotel->image_url) }}" alt="featured-img">
                                        </div>
                                        <div class="right-col">
                                            <p>{!! $hotel->description !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="inner-block">
                    <div class="left-col">
                        <h6>Number of rooms</h6>
                    </div>
                    <div class="right">
                        <div class="inn">
                            <span class="Min" wire:click="removeRoom"><img src="{{ asset('css/booking/icon/minus.svg') }}" alt="minus"></span>
                            <span class="No"><span>|</span> <span class="text">{{ count($this->rooms) }}</span> <span>|</span></span>
                            <span class="Max" wire:click="addRoom"><img src="{{ asset('css/booking/icon/Plus.svg') }}" alt="minus"></span>
                        </div>
                    </div>
                </div>
                <div class="contain">
                    <p>
                       Can't find what you're looking for?
                       <a href="https://www.kpt.com.au/contact-us/" target="_blank">Get in touch</a>
                       for a custom package.
                    </p>
                </div>
            </div>
            <div class="third-block">
                @for($x = 0, $xMax = count($rooms); $x < $xMax; $x++)
                    <div class="first-bl" wire:key="{{Str::random()}}">
                        <div class="inn">
                            <h6>Room {{ $x + 1 }}</h6>
                            <div class="information-hover" data-action="hover" data-target="accommodation-{{$hotel->id}}">
                                <img src="{{ asset('css/booking/icon/Icon.svg') }}" alt="tip-img">
                            </div>
                            <div class="accommodation-details-hover accommodation-{{$hotel->id}}">
                                <div class="contain">
                                    <div class="first-block">
                                        <h6>Hotel details</h6>
                                        <div class="full">
                                            <div class="left-col">
                                                <img class="package-image" src="{{ asset($hotel->image_url) }}" alt="featured-img">
                                            </div>
                                            <div class="right-col">
                                                <p>{!! $hotel->description !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-field">
                            <select wire:model="rooms.{{$x}}.room" name="bedding_configuration">
                                @foreach($this->tour->repository->getRooms() as $id => $name)
                                    <option value="{{$id}}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error("rooms.$x.room") <label class="error-label">{{ $message }}</label> @enderror
                        </div>
                        <div class="form-field">
                            <select class="travellers-select" wire:model.live="rooms.{{$x}}.travellers" name="pax_number">
                                <option value="1">1 Traveller</option>
                                <option value="2">2 Travellers</option>
                            </select>
                            @error("rooms.$x.travellers") <label class="error-label">{{ $message }}</label> @enderror
                        </div>
                    </div>
                @endfor
            </div>
            <div class="submit-btn-cls">
                <div class="inner">
                    <input type="submit" class="submit-btn" wire:click="proceed" value="Proceed">
                </div>
            </div>
        </div>
    </div>

    <div class="right-col">
    <h3>Your trip details</h3>
    <h3 class="event-name">{{ $tour->event?->name }}</h3>
        <div class="contain">
            <x-customer.booking.simple.package-details :booking="$booking" :tour="$tour">
                <div class="submit-btn-cls">
                    <div class="inner">
                        <input type="submit" class="submit-btn" wire:click="proceed" value="Proceed">
                    </div>
                </div>
            </x-customer.booking.simple.package-details>
        </div>
    </div>
</div>
<div class="mob-trip-summary-block">
  <div class="block-container">
      <div class="Inner-container">
        <div class="mob-static-tip-sum">
         <h4>Trip Summary</h4>
         <div class="mob-static-see-more"><p>SEE MORE<p><div>     
        </div>
        <div class="static-mobile-description">
              <h3>British & Irish Lions Tour 2025 Single Game Package - Brisbane</h3>
              <p class="date">18 July, 2025 - 20 July, 2025</p>
              <p class="points">Capri by Fraser Brisbane - 2 nights</p>
              <p class="points">Category 3 Tickets — Test 1 - Wallabies v Lions</p>
              <p class="points">Capri by Fraser Brisbane - 2 nights</p>
              <p class="points">Category 3 Tickets — Test 1 - Wallabies v Lions</p>
        </div>
        <div class="mob-no-of-passengers-list">
           <p>2 Passengers</p>
        </div>
        <div class="price-details-block">
            <ul>
                <li>
                <p class="txt">Package Price</p>
                <p class="price">A$2,000</p>
                </li>
                <li>
                <p class="txt">Single Supplement</p>
                <p class="price">A$0</p>
                </li>
                <li>
                <p class="txt">GST</p>
                <p class="price">A$200</p>
                </li>
            </ul>
        </div>
        <div class="total-block">
            <ul>
                <li>
                <p class="txt">Total</p>
                <p class="price">A$2000</p>
                </li>
            </ul>
        </div>
        <div class="mob-trip-static-button">
          <p>
           <input type="submit" class="submit-btn" wire:click="proceed" value="Proceed">
          <p>
        </div>
      </div>
  </div>
</div>
