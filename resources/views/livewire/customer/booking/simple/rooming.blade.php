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
                <p>TRAVELLER/S</p>
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
            @error('common')
                <div class="submit-btn-cls add-on">
                    <div class="inner">
                        <span style="color: red">{{ $message }}</span>
                    </div>
                </div>
            @enderror
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
<!-- <div class="mob-trip-summary-block">
  <div class="block-container">
      <div class="Inner-container">
        <div class="mob-static-tip-sum">
         <h4>Trip Summary</h4>
         <div class="mob-static-see-more"><p>SEE MORE<p></div>     
        </div>
        <div class="static-mobile-description">
              <h3>{{ $tour->event?->name }}</h3>
              <p class="date">{{ $tour->date_from?->format('d M Y') }} - {{ $tour->date_to?->format('d M Y') }}</p>
              @foreach($tour->repository->getInclusions(4) as $inclusion)
                <p class="points">{{ $inclusion }}</p>
              @endforeach
        </div>
        <div class="mob-no.of-passengers-list">
           <p>{{ $this->getTravellerCount() }} Passengers</p>
        </div>
        <div class="price-details-block">
            <ul>
                <li>
                <p class="txt">Package Price</p>
                <p class="price">{{ f_currency($booking->repository->getBasePrice()) }}</p>
                </li>
                <li>
                @php $singleOccupancy = $booking->repository->getSingleOccupancyAmount(); @endphp
                <p class="txt">Single Supplement</p>
                <p class="price">{{ f_currency($singleOccupancy) }}</p>
                </li>
                <li>
                @if($booking->repository->getTaxes() !== null)
                <p class="txt">{{ $tour->taxBracket()->name }}</p>
                <p class="price">{{ f_currency($booking->repository->getTaxes()) }}</p>
                </li>
            </ul>
        </div>
        <div class="total-block">
            <ul>
                <li>
                <p class="txt">Total</p>
                <p class="price">{{ f_currency($booking->repository->getTotalCost()) }}</p>
                </li>
            </ul>
        </div>
        <div class="submit-btn-cls">
            <div class="inner">
                <input class="submit-btn" wire:click="checkout" type="submit" value="Checkout">
            </div>
        </div>
      </div>
  </div>
</div> -->
