<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="1">
    <section class="package-container first">
        <div class="container">
            <div class="column left">
                <h2 class="sub-heading-2">QUARTER FINALS PACKAGE</h2>
                <h3 class="sub-heading-3">Australian Open</h3>
                <div class="location-dollar-value">
                    <p class="location">Melbourne, Australia</p>
                    <span></span>
                    <p class="dollar">From A$2,995 / person twin share</p>
                </div>
                <div class="no-of-travellers">
                    <h4 class="sub-heading-4">Number of Travellers</h4>
                    <div class="quantity">
                        <span class="minus"><img src="{{ asset('icons/Minus.svg') }}" alt="minus"></span>
                        <span>|</span>
                        <span class="value">5</span>
                        <span>|</span>
                        <span class="plus"><img src="{{ asset('icons/Plus.svg') }}" alt="plus"></span>
                    </div>
                </div>
                <div class="contact-block">
                    <p class="description">If you are a concession card holder, or booking with children under 12, get
                        in touch for a tailor-made package:</p>
                    <p class="phone">Domestic <a href="tel:1300 730 023">+1300 730 023</a></p>
                    <p class="phone">International <a href="tel:+61 2 7201 9353"> +61 2 7201 9353</a></p>
                    <p class="email">Email <a href="mailto:travel@kpt.com.au">travel@kpt.com.au</a></p>
                </div>
            </div>
            <div class="column right">
                <div class="package-details">
                    <div class="contain">
                        <div class="top-module">
                            <h4 class="sub-heading-4">Package details</h4>
                            <div class="hide-package-detail">Hide package details</div>
                        </div>
                        <div class="image-block">
                            <img src="{{ asset('images/sportEvent.png') }}" alt="package-details">
                        </div>
                        <div class="base-package">
                            <h6 class="sub-heading-6">BASE PACKAGE</h6>
                            <h2>QUARTER FINALS PACKAGE</h2>
                            <ul>
                                <li>21 Jan 25 - 24 Jan 25</li>
                                <li>Mens Semi Final Ticket</li>
                                <li>3 Nights, 5-Star Accommodation</li>
                                <li>Exclusive function & more</li>
                            </ul>
                        </div>
                        <div class="additional-inclusions">
                            <h6 class="sub-heading-6  display-none">ADDITIONAL INCLUSIONS</h6>
                            <div class="select-currency">
                                <div class="single">
                                    <p>Select-currency</p>
                                    <select>
                                        <option>AUD</option>
                                        <option>USD</option>
                                        <option>GBP</option>
                                    </select>
                                </div>
                                <div class="single">
                                    <p>Package price</p>
                                    <p>A$2,995</p>
                                </div>
                                <div class="single display-none">
                                    <p>Number of packages - 5</p>
                                    <p>A$14,975</p>
                                </div>
                            </div>
                            <div class="added-nights display-none">
                                <h5>Added nights</h5>
                                <div class="single">
                                    <p>
                                        <span>2x Additional nights</span>
                                        <span>20 Jan - 25 Jan 2025</span>
                                    </p>
                                    <p>A$1,500</p>
                                </div>
                            </div>
                            <div class="room-upgrades display-none">
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
                            <div class="Hotel display-none">
                                <h5>Hotel</h5>
                                <div class="single">
                                    <p>Pan Pacific, Melbourne</p>
                                    <p>Price included</p>
                                </div>
                            </div>
                            <div class="ticket-upgrades display-none">
                                <h5>Ticket upgrades</h5>
                                <div class="single">
                                    <p>Ticket alterations</p>
                                    <p>A$500</p>
                                </div>
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
                                <div class="single display-none">
                                    <p>Starting package price</p>
                                    <p>A$17,125</p>
                                </div>
                                <div class="single display-none">
                                    <p>Customisation cost</p>
                                    <p>A$17,125</p>
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
                                        The remaining balance of A$8,563 will be automatically charged to the same
                                        payment method on 24 June 2024
                                    </div>
                                </div>
                                <div class="price">A$8,563</div>
                            </div>

                            <div class="card-block display-none">
                                <div class="card-type active">
                                    <img src="{{ asset('icons/card.svg') }}" alt="Debit card">
                                    <p>Credit / Debit card</p>
                                </div>
                                <div class="card-type">
                                    <img src="{{ asset('icons/document-text.svg') }}" alt="Direct Debit">
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
                                <form style="display:none;">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email">
                                </form>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="next-button">
                      <span>
                        <span>NEXT</span>
                        <img src="{{ asset('icons/Right-arrow-mod.svg') }}" alt="right-arrow">
                      </span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</x-customer.booking.v3.layout>