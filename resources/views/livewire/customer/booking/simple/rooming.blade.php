<div class="row">
    <div class="left-col">
        <div class="contain">
            <h3>Your trip details</h3>

            <p class="psg-det">PASSENGER DETAILS</p>

            <div class="top-form-contain">
                <div class="form-field">
                    <input type="text" wire:model="lead.first_name" placeholder="First Name" required>
                </div>
                <div class="form-field">
                    <input type="text" wire:model="lead.last_name" placeholder="Last Name">
                </div>
                <div class="form-field">
                    <input type="email" wire:model="lead.email_address" placeholder="Email" required>
                </div>
                <div class="form-field mobile_field">
                    <input type="tel" id="mobile_number" wire:ignore name="mobile_number" placeholder="Mobile number" required>
                </div>
            </div>
            <div class="second-block tra-det">
                <p>TRAVELLERS</p>
                <div class="inner-block">
                    <div class="left-col">
                        <h6>Adults</h6>
                        <p>Ages 13 or above</p>
                    </div>
                    <div class="right">
                        <div class="inn">
                            <span class="Min"><img src="{{ asset('css/booking/icon/minus.svg') }}" alt="minus"></span>
                            <span class="No"><span>|</span> <span class="text">1</span> <span>|</span></span>
                            <span class="Max"><img src="{{ asset('css/booking/icon/Plus.svg') }}" alt="minus"></span>
                        </div>
                    </div>
                </div>
                <div class="contain">
                    <p>Travelling with children?<a href="https://www.kpt.com.au/contact-us/" target="_blank">Get in touch</a> for a custom package.</p>
                </div>
            </div>
            <hr>
            <div class="second-block rme-det">
                <h3>Your room details</h3>
                <p>ROOMS</p>
                @foreach($tour->repository->getHotels() as $hotel)
                <div class="mkvk-wh-bl">
                    <h6>{{ $hotel->name }}</h6>
                    <div class="marl"><img src="{{ asset('css/booking/icon/Icon.svg') }}" alt="tip-img"></div>
                    <div class="ov-block-on-cl">
                        <div class="contain">
                            <div class="first-block">
                                <h6>Hotel details</h6>
                                <div class="full">
                                    <div class="left-col">
                                        <img src="{{ asset($hotel->image_url) }}" alt="featured-img">
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
                            <span class="Min"><img src="{{ asset('css/booking/icon/minus.svg') }}" alt="minus"></span>
                            <span class="No"><span>|</span> <span class="text">1</span> <span>|</span></span>
                            <span class="Max"><img src="{{ asset('css/booking/icon/Plus.svg') }}" alt="minus"></span>
                        </div>
                    </div>
                </div>
                <div class="contain">
                    <p>Can't find what you're looking for?<a href="https://www.kpt.com.au/contact-us/" target="_blank">Get in touch</a> for a custom package.</p>
                </div>
            </div>
            <div class="third-block">
                <div class="first-bl">
                    <div class="inn">
                        <h6>Room 1 </h6>
                        <!--
                        <div class="marl"><img src="assets/images/Icon.svg" alt="tip-img"></div>
                        <div class="ov-block-on-cl">
                            <div class="contain">
                                <div class="first-block">
                                    <h6>Hotel details</h6>
                                    <div class="full">
                                        <div class="left-col">
                                            <img src="assets/images/Hotel details.png" alt="featured-img">
                                        </div>
                                        <div class="right-col">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Vestibulum morbi blandit cursus risus at ultrices. Nec nam aliquam sem et tortor.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="snd-blo">
                                    <h6>Bedding configuration</h6>
                                    <p>Tellus orci ac auctor augue mauris augue neque gravida in. Nunc non blandit massa enim nec dui nunc mattis enim. Ullamcorper morbi tincidunt ornare massa. Ultrices eros in.</p>
                                </div>
                            </div>
                        </div>
                        -->
                    </div>
                    <div class="form-field">
                        <select id="pax_number" name="pax_number">
                            <option value="">Number of Travellers</option>
                            <option value="1">1 Traveller</option>
                            <option value="2">2 Travellers</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <select id="bedding_configuration" name="bedding_configuration">
                            <option value="">Hotel</option>
                            <option value="1">Parkroyd </option>
                            <option value="2">Mariot</option>
                            <option value="1">Holiday Inn</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <select id="bedding_configuration" name="bedding_configuration">
                            <option value="">Bedding Configuration</option>
                            <option value="1">Parkroyd - Deluxe - Double Room</option>
                            <option value="2">Parkroyd - Deluxe - Twin Room</option>
                            <option value="1">Parkroyd - Superior - Double Room (+£250)</option>
                            <option value="2">Parkroyd - Superior - Twin Room (+£250)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right-col">
        <div class="contain">
            <x-customer.booking.simple.package-details :booking="$booking" :tour="$tour">
                <div class="submit-btn-cls">
                    <div class="inner">
                        <input class="submit-btn" type="submit" value="Proceed">
                    </div>
                </div>
            </x-customer.booking.simple.package-details>
        </div>
    </div>
</div>
