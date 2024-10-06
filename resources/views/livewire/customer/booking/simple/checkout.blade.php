@php
    $is_different_traveller = $is_different_traveller === 'true' ? true : false;
@endphp
<div class="row">
    <div class="second-form">
        <div class="left-col">
            <div class="contain">
                <h3>Enter your details</h3>
                @yield('check_out_event_name')
                <!-- <h3 class="event-name">{{ $booking->event?->name }}</h3> -->
                <!-- The event name mobile update -->
                <div class="purchase-info-block">
                    <h3>Enter purchaser information</h3>
                    <!-- <div class="top-check-top-cls">
                        <div class="txt-cls-mod-fs">
                            <label class="contain-vv"><span class="fnal-txt">Use lead traveller details</span>
                                <input type="checkbox" wire:click="toggleLeadPaying" @if($payer->id === $booking->lead_traveller_id) checked @endif>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div> -->
                </div>
                <div class="top-form-contain">
                    <div class="form-field">
                        <input type="text" wire:model="payer.first_name" placeholder="First Name*" required>
                        @error('payer.first_name') <label class="error-label">{{ $message }}</label> @enderror
                    </div>
                    <div class="form-field">
                        <input type="text" wire:model="payer.last_name" placeholder="Last Name*">
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
                        <input type="text" id="postal-code" name="postal-code" wire:model="payerAddress.postcode" placeholder="Postal code*" required>
                        @error('payerAddress.postcode') <label class="error-label">{{ $message }}</label> @enderror
                    </div>
                    <div class="form-field rap-las-cls">
                        <div wire:ignore>
                            <input type="text" id="custom-input-date" class="calendar hasDatepicker" data-picker name="upload-release" placeholder="DATE OF BIRTH">
                            <script type="text/javascript">
                                document.addEventListener('DOMContentLoaded', function() {
                                    const initialDOB = @this.payer?.date_of_birth ? new Date(@this.payer.date_of_birth) : null;
                                    
                                    const datePickerElement = document.querySelector('[data-picker]');
                                    if (datePickerElement) {
                                        const picker = new Pikaday({
                                            field: datePickerElement,
                                            format: 'DD/MM/YYYY', 
                                            minDate: new Date(1900, 0, 1), 
                                            maxDate: new Date(),
                                            yearRange: [1900, new Date().getFullYear()],
                                            onSelect: function(date) {
                                                const formattedDate = formatDate(date);
                                                datePickerElement.value = formattedDate;
                                                @this.set('payer.date_of_birth', formattedDate);
                                            }
                                        });

                                        // Format date as Y-m-d'
                                        function formatDate(date) {
                                            const day = ('0' + date.getDate()).slice(-2);
                                            const month = ('0' + (date.getMonth() + 1)).slice(-2);
                                            const year = date.getFullYear();
                                            return `${day}-${month}-${year}`;
                                        }

                                        if (initialDOB) {
                                        picker.setDate(initialDOB);
                                        datePickerElement.value = formatDate(initialDOB);
                                    }
                                    }
                                });
                            </script>
                        </div>
                        <!--<input id="custom-input-date" class="calendar hasDatepicker" type="text" name="upload-release" placeholder="DATE OF BIRTH*">-->
                        @error('payer.date_of_birth') <label class="error-label">{{ $message }}</label> @enderror
                    </div>
                    <div class="lead-purchase-traveller-block">
                        <p>Is purchaser the same person as lead traveller</p>
                        <!-- <div class="cus-block-mod-ren">
                            <label class="contain-vv"><span class="fnal-txt">Yes</span>
                                <input type="checkbox" name="Yes" value="Yes" checked>                           
                                <span class="checkmark"></span>
                            </label> 
                            <label class="contain-vv"><span class="fnal-txt">No</span>
                                <input type="checkbox" name="No" value="No">
                                <span class="checkmark"></span>
                            </label> 
                        </div> -->
                        <div class="cus-block-mod-ren">
                            <label class="contain-vv"><span class="fnal-txt">Yes</span>
                                <input type="radio" wire:model="is_different_traveller" value="false" @checked($is_different_traveller == false)>                           
                                <span class="checkmark"></span>
                            </label> 
                            <label class="contain-vv"><span class="fnal-txt">No</span>
                                <input type="radio" wire:model="is_different_traveller" value="true" @checked($is_different_traveller == true)>
                                <span class="checkmark"></span>
                            </label> 
                        </div>   
                    </div>

                </div>

                <!-- <div style="display:none;" class="purchase-info-block">
                  <h3>lead passenger details</h3>
                </div> -->

                <!-- <div class="top-form-contain lead-passenger">
                    <div class="form-field">
                        <input type="text" wire:model="payer.first_name_lead" placeholder="First Name*" required>
                    </div>
                    <div class="form-field">
                        <input type="text" wire:model="payer.last_name_lead" placeholder="Last Name*">
                    </div>
                    <div class="form-field">
                        <input type="email" wire:model="payer.email_address_lead" placeholder="Email*" required>
                    </div>
                    <div class="form-field mobile_field">
                        <div wire:ignore>
                            <input type="tel" id="mobile_number_lead" name="mobile_number_lead" placeholder="Mobile number*" required>
                            <script type="text/javascript">
                                jQuery(document).ready(function () {
                                    let input = document.querySelector('#mobile_number_lead');
                                    let iti = window.setupPhoneField(input);
                                });
                            </script>
                        </div>
                    </div>
                </div> -->

                @if ($is_different_traveller)
                <!-- <style>
                    .purchase-info-block{
                        display: flex!important;
                    }
                </style> -->
                    <div class="whol-lead-contain">
                        <div class="purchase-info-block">
                            <h3>lead passenger details</h3>
                        </div>
                        <div class="top-form-contain lead-passenger">
                            <div class="form-field">
                                <input type="text"  {{-- wire:model="payer.first_name1" --}} placeholder="First Name*" required>
                                @error('payer.first_name1') <label class="error-label">{{ $message }}</label> @enderror
                            </div>
                            <div class="form-field">
                                <input type="text"  {{--wire:model="payer.last_name1"--}} placeholder="Last Name*">
                                @error('payer.last_name1') <label class="error-label">{{ $message }}</label> @enderror
                            </div>
                            <div class="form-field">
                                <input type="email"  {{-- wire:model="payer.email_address1"--}} placeholder="Email*" required>
                                @error('payer.email_address1') <label class="error-label">{{ $message }}</label> @enderror
                            </div>
                            <div class="form-field mobile_field">
                                <input type="tel"  {{-- wire:model="payer.mobile_number1"--}} placeholder="Mobile number*" required>
                                @error('payer.mobile_number1') <label class="error-label">{{ $message }}</label> @enderror
                                <script type="text/javascript">
                                    jQuery(document).ready(function () {
                                        let input = document.querySelector('#mobile_number1');
                                        let iti = window.setupPhoneField(input);
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                @endif
              
                <h3>Special requests</h3>
                <div data-chk="{{$this->booking->notes}}" class="form-field-full-width" wire:ignore>
                    <div id="summernote">{!! $this->booking->notes !!}</div>
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
                            @php
                                $totalCost = $booking->repository->getTotalCost();
                                $depositPercentage = $booking->tour?->deposit_percentage ?? 0;
                                $depositAmount = ($totalCost * $depositPercentage) / 100;
                                $remainingAmount = $totalCost - $depositAmount;
                            @endphp
                        <div class="form-field-checkbox" wire:click="setPayFull(0)">
                            <div class="left-ass">
                                <label class="containr"><span class="txt">Pay a {{ $booking->tour?->deposit_percentage }}% deposit now, and the rest later<span class="inn-txt-cls">The remaining balance of {{ f_currency($remainingAmount) }} will be automatically charged to the same payment method on {{ f_date($booking->tour?->final_payment) }}</span></span>
                                    <input type="checkbox" @if(!$payFull) checked @endif>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="right-assets">
                                <p>{{ f_currency($booking->repository->getDueTodayAmount()) }}</p>
                            </div>
                        </div>
                        <!-- <div class="form-field-checkbox" wire:click="setPayFull(0)">
                            <div class="left-ass">
                                <label class="containr">
                                <span class="txt">
                                    Pay a {{ $booking->tour?->deposit_percentage }}% deposit now, and the rest later
                                    <span class="inn-txt-cls">
                                        You will receive a reminder to pay the balance amount before {{ f_date($booking->tour?->final_payment) }}
                                    </span>
                                </span>
                                    <input type="checkbox" @if(!$payFull) checked @endif>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="right-assets">
                                <p>{{ f_currency($booking->repository->getDueTodayAmount()) }}</p>
                            </div>
                        </div> -->
                        @endif
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js" defer></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endpush

