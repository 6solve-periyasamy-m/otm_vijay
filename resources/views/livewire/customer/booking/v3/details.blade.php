<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="5">
    <section class="package-container">
        <div class="container">
            <div class="column left">
                <x:customer.booking.v3.tour-info :tour="$tour" :booking="$booking" :selectedCurrency="$selectedCurrency" />
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
                                        <input type="radio" wire:click="leadIsTravelling()" name="lead" value="day" @if($this->leadIsTravelling) checked="" @endif>
                                        <span class="custom-radio"></span>
                                        <span class="option-title">Yes</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="lead" wire:click="leadIsNotTravelling()" value="night" @if(!$this->leadIsTravelling) checked="" @endif>
                                        <span class="custom-radio"></span>
                                        <span class="option-title">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!$this->leadIsTravelling)
                    <div class="details-form-module">
                        <h6>Lead passenger details</h6>
                        <div class="single-details-module">
                            <div>
                                <label for="lead-first-name">First name*</label>
                                <input type="text" id="lead-first-name" wire:model.lazy="lead.first_name">
                                <small>Include middle names if applicable.</small>
                                @error('lead.first_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="lead-last-name">Last name*</label>
                                <input type="text" id="lead-last-name" wire:model.lazy="lead.last_name">
                                @error('lead.last_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="lead-email">Email*</label>
                                <input type="email" wire:model.lazy="lead.email_address" id="lead-email" name="email" placeholder="Enter your email address">
                                @error('lead.email_address') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="lead-mobile_number">Phone number*</label>
                                <input type="text" id="lead-mobile_number" wire:model.lazy="lead.mobile_number">
                                @error('lead.mobile_number') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="lead-country">Country*</label>
                                <select id="lead-country" wire:model.lazy="leadAddress.country_id" required>
                                    <option value="">Select</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('lead.country_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="dob-input">
                                <label for="lead-dob">Date of birth</label>
                                <input type="text" id="lead-dob" wire:model.lazy="lead.date_of_birth" class="calendar hasDatepicker" data-picker
                                       name="upload-release" placeholder="Enter your date of birth">
                                <img src="{{ asset('icons/checkin.svg') }}" alt="calendar">
                            </div>
                            <div class="full-width" data-chk="{{$this->booking->notes}}" wire:ignore>
                                <label for="summernote">Special requests</label>
                                <div id="summernote">{{$this->booking->notes}}</div>
                            </div>
                        </div>
                    </div>
                    @endif
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
                        </div>      <div class="price">A$17,125</div>
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
                    <div style="padding-top: 1rem;">
                        <div id="stripe-hidden" style="visibility: hidden">
                            <div id="stripe-container"></div>
                            <button id="pay-button">Pay</button>
                            <div id="confirm-errors"></div>
                        </div>
                        <div id="airwallex-container" class="airwallex-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        jQuery(document).ready(function () {
            const initialDOB = null;
            const buyerDatePicker = document.querySelector('#buyer-dob[data-picker]');
            const leadDatePicker = document.querySelector('#lead-dob[data-picker]');

            function formatDate(date) {
                const day = ('0' + date.getDate()).slice(-2);
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                const month = monthNames[date.getMonth()];
                const year = date.getFullYear();
                return `${day} ${month} ${year}`;
            }

            if (buyerDatePicker) {
                const buyerDOB = new Pikaday({
                field: buyerDatePicker,
                format: 'DD/MM/YYYY',
                minDate: new Date(1900, 0, 1),
                maxDate: new Date(),
                yearRange: [1900, new Date().getFullYear()],
                onSelect: function (date) {
                    const formattedDate = formatDate(date);
                    buyerDatePicker.value = formattedDate;
                }
                });
            }

            if (leadDatePicker) {
                const leadDOB = new Pikaday({
                    field: leadDatePicker,
                    format: 'DD/MM/YYYY',
                    minDate: new Date(1900, 0, 1),
                    maxDate: new Date(),
                    yearRange: [1900, new Date().getFullYear()],
                    onSelect: function (date) {
                        const formattedDate = formatDate(date);
                        leadDatePicker.value = formattedDate;
                    }
                });
            }
        });
        $(document).on('click','.dob-input img',function(){
            console.log(">>>>>>>>>>>>>>");
            $(this).closest('.dob-input').find('input').click();
        })
    </script>
</x-customer.booking.v3.layout>