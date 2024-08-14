<div class="row">
    <div class="second-form">
        <div class="left-col">
            <div class="contain">
                <div class="purchase-info-block">
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
                        <input type="text" wire:model="payer.first_name" placeholder="First Name*" required>
                        @error('payer.first_name') <label class="error-label">{{ $message }}</label> @enderror
                    </div>
                    <div class="form-field">
                        <input type="text" wire:model="payer.last_name" placeholder="Last Name">
                        @error('payer.last_name') <label class="error-label">{{ $message }}</label> @enderror
                    </div>
                    <div class="form-field">
                        <input type="email" wire:model="payer.email_address" placeholder="Email*" required>
                        @error('payer.email_address') <label class="error-label">{{ $message }}</label> @enderror
                    </div>
                    <div class="form-field mobile_field">
                        <div wire:ignore>
                            <input type="tel" id="mobile_number" name="mobile_number" value="{{ $this->payer->mobile_number }}" placeholder="Mobile number*" required>
                            <script type="text/javascript">
                                jQuery(document).ready(function () {
                                    let input = document.querySelector('#mobile_number');
                                    let iti = window.setupPhoneField(input);

                                    jQuery(input).on('change', function (event) {
                                        @this.set('payer.mobile_number', iti.getNumber());
                                    });
                                    document.addEventListener('updateValue', function (event) {
                                        if (event.detail.key === 'payer.mobile_number') {
                                            input.value = event.detail.value;
                                        }
                                    });
                                });
                            </script>
                        </div>
                        @error('lead.mobile_number') <label class="error-label">{{ $message }}</label> @enderror
                    </div>
                    <div class="form-field">
                        <input type="text" id="postal-code" name="postal-code" wire:model="payerAddress.postcode" placeholder="Postal code" required>
                        @error('payerAddress.postcode') <label class="error-label">{{ $message }}</label> @enderror
                    </div>
                    <div class="form-field rap-las-cls" wire:ignore>
                        <input type="text" wire:model="date" data-picker placeholder="Select date">

                       <input id="custom-input-date" class="calendar hasDatepicker" type="text" name="upload-release" placeholder="DATE OF BIRTH*">
                        @error('payer.date_of_birth') <label class="error-label">{{ $message }}</label> @enderror
                    </div>


                </div>
                <h3>Special requests</h3>
                <div class="form-field-full-width" wire:ignore>
                    <div id="summernote"></div>
                    <script type="text/javascript">
                        $('#summernote').summernote({
                            placeholder: 'Message',
                            tabsize: 2,
                            height: 120,
                            toolbar: [
                                ['font', ['bold', 'italic', 'underline']],
                                ['para', ['paragraph', 'ol']],
                                ['insert', ['link', 'picture', 'emoji']],
                            ],
                            callbacks: {
                                onChange: function (content, $editable) {
                                    @this.set('booking.notes', content)
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

        <div class="right-col">
            <div class="contain">
                <x-customer.booking.simple.package-details :booking="$booking" :tour="$this->booking->tour">
                    <div class="additional-block" id="">
                        <h6>Payment method</h6>
                        <div class="form-field-checkbox" wire:click="setPayFull(1)">
                            <div class="left-ass">
                                <label class="containr"><span class="txt">Pay in full</span>
                                    <input type="checkbox" @if($payFull) checked @endif>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="right-assets">
                                <p>{{ f_currency($booking->repository->getTotalCost()) }}</p>
                            </div>
                        </div>
                        @if(!$this->mustPayAll())
                        <div class="form-field-checkbox" wire:click="setPayFull(0)">
                            <div class="left-ass">
                                <label class="containr">
                                <span class="txt">
                                    Pay a {{ $booking->tour->deposit_percentage }}% deposit now, and the rest later
                                    <span class="inn-txt-cls">
                                        You will receive a reminder to pay the balance amount before {{ f_date($booking->tour->final_payment) }}
                                    </span>
                                </span>
                                    <input type="checkbox" @if(!$payFull) checked @endif>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="right-assets">
                                <p>{{ f_currency($booking->repository->getDueTodayAmount()) }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="card-field-box">
                            <div class="first-in active">
                                {{ Icon::regular('credit-card') }}
                                <p>Credit / Debit card</p>
                            </div>
                            {{--
                            <div class="first-in">
                                {{ Icon::solid('file-invoice') }}
                                <p>Invoice – Direct Debit</p>
                            </div>
                            --}}
                        </div>
                    </div>
                    <div class="wh-las-cls-con">
                        <div class="acc-tp-cond">
                            <label class="contain-v"><span class="fnal-txt">I accept the <a href="https://www.kpt.com.au/terms-and-conditions/" target="_blank">Terms & Conditions</a></span>
                                <input type="checkbox" wire:model="terms">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="submit-btn-cls">
                            <div class="inner">
                                <input class="submit-btn" wire:click="checkout" type="submit" value="Checkout">
                            </div>
                        </div>
                    </div>
                </x-customer.booking.simple.package-details>
            </div>
        </div>
    </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js" defer></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endassets

@script
<script>
    $(document).ready(function () {
        const $dateInput = $("#custom-input-date");
    
        if (!$dateInput.hasClass('ui-datepicker-input')) {
            $dateInput.datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                yearRange: '1970:c',
                minDate: new Date(1970, 0, 1),
            }).on("click", function () {
                console.log('datepick click');
                $(this).datepicker("show");
            });
        }
    });
</script>


<script>
    new Pikaday({ 
        field: $wire.$el.querySelector('[data-picker]'), 
        onSelect: function() {
            @this.set('date', this.getDate());
        } 
    });
</script>
@endscript

