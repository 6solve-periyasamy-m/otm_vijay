<div class="row">
    <div class="left-col">
        <div class="contain">
           <!-- The event name mobile update -->
            <div class="evnt-name">
                <h4 class="head-evnt">{{ $tour->event?->name }}</h4>
            </div>
            <!-- The event name mobile update -->
            <h3>Your trip details</h3>

            <p class="psg-det">PASSENGER DETAILS</p>

            <div class="top-form-contain">
                <div class="form-field">
                    <input type="text" wire:model="lead.first_name" placeholder="First Name*" required>
                    @error('lead.first_name') <label class="error-label">{{ $message }}</label> @enderror
                </div>
                <div class="form-field">
                    <input type="text" wire:model="lead.last_name" placeholder="Last Name*">
                    @error('lead.last_name') <label class="error-label">{{ $message }}</label> @enderror
                </div>
                <div class="form-field">
                    <input type="email" wire:model="lead.email_address" placeholder="Email*" required>
                    @error('lead.email_address') <label class="error-label">{{ $message }}</label> @enderror
                </div>
                <div class="form-field mobile_field">
                    <div wire:ignore>
                        <input type="tel" id="mobile_number" name="mobile_number" value="{{ $this->lead->mobile_number }}" placeholder="Mobile number*" required>
                        <script type="text/javascript">
                            jQuery(document).ready(function () {
                                let input = document.querySelector('#mobile_number');
                                let iti = window.setupPhoneField(input);

                                jQuery(input).on('change', function (event) {
                                    @this.
                                    set('lead.mobile_number', iti.getNumber());
                                });
                                document.addEventListener('updateValue', function (event) {
                                    if (event.detail.key === 'lead.mobile_number') {
                                        input.value = event.detail.value;
                                    }
                                });
                            });
                        </script>
                    </div>
                    @error('lead.mobile_number') <label class="error-label">{{ $message }}</label> @enderror
                </div>
            </div>
            <!-- <- Additional Travellers -->
            <div class="second-block tra-det">
                <p>TRAVELLERS</p>
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
            <hr>
            <!-- <- Rooming -->
            <div class="second-block rme-det">
                <h3>Your room details</h3>
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
        </div>
    </div>

    <div class="right-col">
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
