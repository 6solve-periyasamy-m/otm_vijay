<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="5" payFull="{{ $payFull }}">
    <x-slot:left>
        <div class="details-book">
            <h2 class="sub-heading-2-p">Details</h2>
            <p>Tell us a little more about yourself. </p>
            <div class="details-form-module">
                <h6>Purchaser</h6>
                {{--<p>Your quote will be sent to the email address provided for Guest 1</p>--}}
                <div class="single-details-module">
                    <div>
                        <label for="payer-firstname">First name*</label>
                        <input type="text" id="payer-firstname" wire:model.lazy="payer.first_name"
                            value="{{ $payer->first_name }}">
                        <small>Include middle names if applicable.</small>
                        @error('payer.first_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="payer-lastname">Last name*</label>
                        <input type="text" id="payer-lastname" wire:model.lazy="payer.last_name"
                            value="{{ $payer->last_name }}">
                        @error('payer.last_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="payer-email">Email*</label>
                        <input type="email" wire:model.lazy="payer.email_address" id="payer-email"
                            value="{{ $payer->email_address }}">
                        @error('payer.email_address') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="payer-mobile_number">Phone number</label>
                        <input type="text" id="payer-mobile_number" wire:model.lazy="payer.mobile_number"
                            value="{{ $payer->mobile_number }}">
                        @error('payer.mobile_number') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="payer-country">Country</label>
                        <select id="payer-country" wire:model.lazy="payerAddress.country_id" required>
                            <option value="">Select</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country['id'] }}"
                                    {{ $payerAddress->country_id == $country['id'] ? 'selected' : '' }}>
                                    {{ $country['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('payerAddress.country_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="payer-dob">Date of birth</label>
                        <input type="text"  id="payer-dob" wire:model="payer_date_of_birth_formatted"  placeholder="DD-MM-YYYY"  class="form-control" />
                        @error('payer_date_of_birth_formatted') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="full-width">
                        <label>Is purchaser the same person as lead traveller</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" wire:click="setLeadPaying(false)" name="lead" value="day" @if($this->leadIsTravelling) checked="" @endif>
                                <span class="custom-radio"></span>
                                <span class="option-title">Yes</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="lead" wire:click="setLeadPaying(true)" value="night" @if(!$this->leadIsTravelling) checked="" @endif>
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
                            <label for="lead-country">Country</label>
                            <select id="lead-country" wire:model.lazy="leadAddress.country_id" required>
                                <option value="">Select</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                                @endforeach
                            </select>
                            @error('leadAddress.country_id') <span class="text-danger">{{ $message }}</span> @enderror     
                        </div>
                        <div>
                            <label for="lead-dob">Date of birth</label>
                            <input type="text"  id="lead-dob" wire:model="lead_date_of_birth_formatted"  placeholder="DD-MM-YYYY"  class="form-control" />
                            @error('lead_date_of_birth_formatted') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="full-width">
            <label for="summernote">Special requests</label>
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

        @php $estimateSingleOccupancy = 0; @endphp
        @if($this->booking->groups()->count() <= 0)
            @php $singleCount = $this->booking->travellers()?->count() % 2; @endphp
            @php $estimateSingleOccupancy = $this->tour->single_occupancy_surcharge * $singleCount; @endphp
        @endif
        <!-- Payment Section -->
        <div class="payment-method ">
            <h6 class="sub-heading-6">PAYMENT METHOD</h6>
            <div class="option-wrapper">
                <label class="radio-option" wire:click="payFull()">
                    <input type="radio" name="payment" @if($payFull) checked @endif>
                    <span class="custom-radio"></span>
                    <span class="option-title">Pay in full</span>
                </label>
                <div class="price">{{ $this->formatCurrency($this->booking->repository->getTotalCost() + $estimateSingleOccupancy) }}</div>
            </div>
            <div class="option-wrapper">
                <div>
                    <label class="radio-option" wire:click="payDueToday()">
                        <input type="radio" name="payment" @if(!$payFull) checked @endif>
                        <span class="custom-radio"></span>
                        <span class="option-title">Pay a {{ $booking->tour?->deposit_percentage }}% deposit now, and the rest later</span>
                    </label>
                    <div class="option-subtext">
                        <p>You will receive a reminder to pay the remaining balance of {{ $this->formatCurrency(($this->booking->repository->getTotalCost() + $estimateSingleOccupancy) - $this->booking->repository->getDueTodayAmount()) }} before {{ $tour->final_payment->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="price">{{ $this->formatCurrency($this->booking->repository->getDueTodayAmount()) }}</div>
            </div>
            {{--
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
            --}}
            <div class="acc-tp-cond">
                <input type="checkbox" wire:model="terms">
                <label class="contain-v"><span class="fnal-txt">I accept the <a href="https://www.kpt.com.au/terms-and-conditions/" target="_blank">Terms & Conditions</a></span>
                    <span class="checkmark"></span>
                </label>
            </div>
            <button type="submit" class="next-button" wire:click="advance">
                <span>
                    <span>CHECKOUT</span>
                    <img src="{{ asset('icons/Right-arrow-mod.svg') }}" alt="right-arrow">
                </span>
            </button>
            @error('common')
            <div style="padding-top: 1rem; color: red;">
                {{ $message }}
            </div>
            @enderror

            <div style="padding-top: 1rem;">
                <div id="stripe-hidden" style="visibility: hidden">
                    <div id="stripe-container"></div>
                    @if($this->getSurchargeAmount() !== null || $this->getSurchargeAmount() > 0 || $this->getSurchargeAmount() < 0)
                        <div id="surcharge-warning">
                            A card surcharge of {{ $this->getSurchargePercentage() }}% ({{ fr_currency($this->getSurchargeAmount(), $this->booking->currency) }}) will be added to card transactions
                        </div>
                    @endif
                    <button type="submit" class="next-button" id="pay-button">
                        <span>
                            <span>PAY</span>
                        </span>
                    </button>
                    <div id="confirm-errors"></div>
                </div>
                <div id="airwallex-container" class="airwallex-content"></div>
            </div>

        </div>
        <!-- End of Payment Section -->
    </x-slot:left>
    <script>
        /*
        const pikadayInstances = {};

        function initPikadayDatePicker(visibleId, hiddenId) {
            const visibleInput = document.getElementById(visibleId);
            const hiddenInput = document.getElementById(hiddenId);

            if (!visibleInput || !hiddenInput) return;

            if (pikadayInstances[visibleId]) {
                pikadayInstances[visibleId].destroy();
            }

            const picker = new Pikaday({
                field: visibleInput,
                format: 'DD-MM-YYYY',
                toString(date) {
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    return `${day}-${month}-${year}`;
                },
                parse(dateString) {
                    const [day, month, year] = dateString.split('-');
                    return new Date(year, month - 1, day);
                },
                onSelect(date) {
                    const yyyy = date.getFullYear();
                    const mm = String(date.getMonth() + 1).padStart(2, '0');
                    const dd = String(date.getDate()).padStart(2, '0');

                    visibleInput.value = `${dd}-${mm}-${yyyy}`;
                    hiddenInput.value = `${yyyy}-${mm}-${dd}`;
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                },
                maxDate: new Date()
            });

            pikadayInstances[visibleId] = picker;

            // Fix: Set visible input correctly on page load
            if (hiddenInput.value) {
                const [y, m, d] = hiddenInput.value.split('-');
                const date = new Date(y, m - 1, d);
                picker.setDate(date, true); // true => prevent onSelect firing
                visibleInput.value = `${d.padStart(2, '0')}-${m.padStart(2, '0')}-${y}`;
            }
        }

        function setupAllPickers() {
            initPikadayDatePicker('payer-dob-visible', 'payer-dob');
            initPikadayDatePicker('lead-dob-visible', 'lead-dob');
        }

        document.addEventListener("DOMContentLoaded", () => {
            setupAllPickers();
        });

        document.addEventListener("livewire:load", () => {
            // Safe hook with cleanup
            Livewire.hook('message.processed', () => {
                setupAllPickers();
            });
        });

        jQuery(document).on('click','.dob-input img',function(){
            jQuery(this).closest('.dob-input').find('input').click();
        }) */
</script>

</x-customer.booking.v3.layout>