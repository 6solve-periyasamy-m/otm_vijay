<div class="row">
    <div class="left-col">
        <div class="contain">
            <div class="lmnvkp">
                <h3>Enter purchaser information</h3>
                <div class="top-check-top-cls">
                    <div class="txt-cls-mod-fs">
                        <label class="contain-vv"><span class="fnal-txt">Use lead traveller details</span>
                            <input type="checkbox" wire:click="toggleLeadPaying" @if($payer->id === $booking->lead_traveller_id) checked @endif>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="top-form-contain">
                <div class="form-field">
                    <input type="text" wire:model="payer.first_name" placeholder="First Name" required>
                </div>
                <div class="form-field">
                    <input type="text" wire:model="payer.last_name" placeholder="Last Name">
                </div>
                <div class="form-field">
                    <input type="email" wire:model="payer.email_address" placeholder="Email" required>
                </div>
                <div wire:ignore class="form-field mobile_field">
                    <input type="tel" id="mobile_number" name="mobile_number" placeholder="Mobile number" required>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            let input = document.querySelector('#mobile_number');
                            let iti = window.setupPhoneField(input);

                            jQuery(input).on('change', function (event) {
                                @this.set('lead.mobile_number', iti.getNumber());
                            });
                        });
                    </script>
                </div>
                <div class="form-field">
                    <input type="text" id="postal-code" name="postal-code" pattern="[A-Za-z0-9]{3,10}" title="Enter a valid postal code"  placeholder="Postal code*" required>
                </div>
                <div class="form-field rap-las-cls">
                    <input id="custom-input-date" class="calendar" type="text" name="upload-release" placeholder="DATE OF BIRTH*">
                </div>
            </div>
            <h3>Special requests</h3>
            <div class="form-field-full-width" wire:ignore>
                <div id="summernote"></div>
                <!-- <textarea id="Message" name="Message" placeholder="Message"></textarea> -->
            </div>
        </div>
    </div>
    <div class="right-col">
        <div class="contain">
            <x-customer.booking.simple.package-details :booking="$booking" :tour="$this->booking->tour">
                <div class="additional-block" id="">
                    <h6>Payment method</h6>
                    <div class="form-field-checkbox">
                        <div class="left-ass">
                            <label class="containr"><span class="txt">Pay in full</span>
                                <input type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="right-assets">
                            <p>{{ f_currency($booking->repository->getTotalCost()) }}</p>
                        </div>
                    </div>
                    <div class="form-field-checkbox">
                        <div class="left-ass">
                            <label class="containr">
                                <span class="txt">
                                    Pay a 50% deposit now, and the rest later
                                    <span class="inn-txt-cls">
                                        The remaining balance of {{ f_currency($booking->repository->getTotalCost() - $booking->repository->getDueTodayAmount()) }} will be automatically charged to the same payment method on 24 June 2024
                                    </span>
                                </span>
                                <input type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="right-assets">
                            <p>{{ f_currency($booking->repository->getDueTodayAmount()) }}</p>
                        </div>
                    </div>

                    <div class="card-field-box">
                        <div class="first-in active">
                            {{ Icon::regular('credit-card') }}
                            <p>Credit / Debit card</p>
                        </div>
                        <div class="first-in">
                            {{ Icon::solid('file-invoice') }}
                            <p>Invoice – Direct Debit</p>
                        </div>
                    </div>
                </div>
                <div class="wh-las-cls-con">
                    <div class="acc-tp-cond">
                        <label class="contain-v"><span class="fnal-txt">I accept the <a href="https://www.kpt.com.au/terms-and-conditions/" target="_blank">Terms & Conditions</a></span>
                            <input type="checkbox">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="submit-btn-cls">
                        <div class="inner">
                            <input class="submit-btn" type="submit" value="Checkout">
                        </div>
                    </div>
                </div>
            </x-customer.booking.simple.package-details>
        </div>
    </div>
</div>
