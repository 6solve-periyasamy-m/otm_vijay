<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="5">
    <section class="package-container">
        <div class="container">
            <div class="column left">
                <h2 class="sub-heading-2">QUARTER FINALS PACKAGE</h2>
                <h3 class="sub-heading-3">Australian Open</h3>
                <div class="location-dollar-value">
                    <p class="location">Melbourne, Australia</p>
                    <span></span>
                    <p class="dollar">From A$2,995 / person twin share</p>
                </div>
                <div class="details-book">
                    <h2 class="sub-heading-2-p">Details</h2>
                    <p>Tell us a little more about yourself. </p>

                    <div class="details-form-module">
                        <h6>Purchaser</h6>
                        <p>Your quote will be sent to the email address provided for Guest 1</p>
                        <div class="single-details-module">
                            <div>
                                <label for="first-name">First name*</label>
                                <input type="text" id="first-name" value="" placeholder="Enter your first name" required>
                                <small>Include middle names if applicable.</small>
                            </div>
                            <div>
                                <label for="last-name">Last name*</label>
                                <input type="text" id="last-name" value="" placeholder="Enter your last name" required>
                            </div>
                            <div>
                                <label for="email">Email*</label>
                                <input type="email" id="email" value="" placeholder="Enter your email address" required>
                            </div>
                            <div>
                                <label for="phone">Phone number*</label>
                                <input type="tel" id="phone" value="" placeholder="Enter your phone number" required>
                            </div>
                            <div>
                                <label for="country">Country*</label>
                                <select id="country" required>
                                    <option>Select</option>
                                    <option>Australia</option>
                                    <option>Australia</option>
                                </select>
                            </div>
                            <div class="dob-input">
                                <label for="dob">Date of birth</label>
                                <input type="text" id="custom-input-dob-1" class="calendar hasDatepicker" data-picker
                                       name="upload-release" placeholder="Enter your date of birth">
                                <img src="{{ asset('icons/checkin.svg') }}" alt="calendar">
                            </div>
                            <div class="full-width">
                                <label>Is purchaser the same person as lead traveller</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="lead" value="day">
                                        <span class="custom-radio"></span>
                                        <span class="option-title">Yes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="lead" value="night" checked="">
                                        <span class="custom-radio"></span>
                                        <span class="option-title">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="details-form-module">
                        <h6>Lead passenger details</h6>
                        <div class="single-details-module">
                            <div>
                                <label for="first-name">First name*</label>
                                <input type="text" id="first-name" value="" placeholder="Enter guest’s first name" required>
                                <small>Include middle names if applicable.</small>
                            </div>
                            <div>
                                <label for="last-name">Last name*</label>
                                <input type="text" id="last-name" value="" placeholder="Enter guest’s last name" required>
                            </div>
                            <div>
                                <label for="email">Email*</label>
                                <input type="email" id="email" value="" placeholder="Enter email address" required>
                            </div>
                            <div>
                                <label for="phone">Phone number*</label>
                                <input type="tel" id="phone" value="" placeholder="Enter phone number" required>
                            </div>
                            <div>
                                <label for="country">Country*</label>
                                <select id="country" required>
                                    <option>Select</option>
                                    <option>Australia</option>
                                    <option>Australia</option>
                                </select>
                            </div>
                            <div class="dob-input">
                                <label for="dob">Date of birth</label>
                                <input type="text" id="custom-input-dob-2" class="calendar hasDatepicker" data-picker
                                       name="upload-release" placeholder="Enter your date of birth">
                                <img src="{{ asset('icons/checkin.svg') }}" alt="calendar">
                            </div>
                            <div class="full-width">
                                <label for="summernote">Special requests</label>
                                <div id="summernote"></div>
                            </div>
                        </div>
                    </div>


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
                            <h6 class="sub-heading-6">ADDITIONAL INCLUSIONS</h6>
                            <div class="select-currency">
                                <div class="single">
                                    <p>Select-currency</p>
                                    <select>
                                        <option>AUD</option>
                                        <option>AUD</option>
                                        <option>AUD</option>
                                    </select>
                                </div>
                                <div class="single">
                                    <p>Package price</p>
                                    <p>A$2,995</p>
                                </div>
                                <div class="single">
                                    <p>Number of packages - 5</p>
                                    <p>A$14,975</p>
                                </div>
                            </div>
                            <div class="added-nights">
                                <h5>Added nights</h5>
                                <div class="single">
                                    <p>
                                        <span>2 x Additional nights</span>
                                        <span>20 Jan - 25 Jan 2025</span>
                                    </p>
                                    <p>A$1,500</p>
                                </div>
                            </div>
                            <div class="room-upgrades">
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
                            <div class="Hotel">
                                <h5>Hotel</h5>
                                <div class="single">
                                    <p>Pan Pacific, Melbourne</p>
                                    <p>Price included</p>
                                </div>
                            </div>
                            <div class="ticket-upgrades">
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
                            <div class="additional-upgrades">
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
                                <div class="single">
                                    <p>Starting package price</p>
                                    <p>$14,975</p>
                                </div>
                                <div class="single">
                                    <p>Customisation cost</p>
                                    <p>$500</p>
                                </div>
                            </div>
                        </div>
                        <div class="payment-method ">
                            <h6 class="sub-heading-6">PAYMENT METHOD</h6>
                            <div class="option-wrapper">
                                <label class="radio-option">
                                    <input type="radio" name="payment" checked>
                                    <span class="custom-radio"></span>
                                    <span class="option-title">Pay in full</span>
                                </label>
                                <div class="price">A$17,125</div>
                            </div>
                            <div class="option-wrapper">
                                <div>
                                    <label class="radio-option">
                                        <input type="radio" name="payment">
                                        <span class="custom-radio"></span>
                                        <span class="option-title">Pay a 50% deposit now, and the rest later</span>
                                    </label>
                                    <div class="option-subtext">
                                        The remaining balance of A$8,563 will be automatically charged to the same payment method on 24
                                        June 2024
                                    </div>
                                </div>
                                <div class="price">A$8,563</div>
                            </div>
                            <div class="single-details-module payment">
                                <div>
                                    <label for="email">Email</label>
                                    <input type="email" id="email" value="" placeholder="Enter email address">
                                </div>
                                <div>
                                    <label for="email">Email</label>
                                    <input type="email" id="email" value="" placeholder="Enter email address">
                                </div>
                                <div>
                                    <label for="email">Email</label>
                                    <input type="email" id="email" value="" placeholder="Enter email address">
                                </div>
                                <div>
                                    <label for="email">Email</label>
                                    <input type="email" id="email" value="" placeholder="Enter email address">
                                </div>
                            </div>
                            <div class="card-block">
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
                                <!-- <form style="display:none;">
                                  <label for="email">Email</label>
                                  <input type="email" id="email" name="email">
                                </form> -->
                            </div>
                        </div>
                    </div>
                    <button type="button" class="next-button">
              <span>
                <span>NEXT</span>
                <img src="{{ asset('icons/Right-arrow-mod.svg') }}" alt="right-arrow">
              </span>
                    </button>
                    <!-- <span class="accomodation-travel-date-error">
                  <span>
                    <img src="{{ asset('icons/Noti-Icon.svg') }}" alt="icon">
                  </span>
                  <span>
                    Total number of travellers vs. the number of guests you have selected for rooms does not match -
                    please
                    update your room selection to proceed
                  </span>
                </span>
              </div> -->
                </div>
            </div>
    </section>
</x-customer.booking.v3.layout>