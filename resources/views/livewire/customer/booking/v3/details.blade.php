<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="5" payFull="{{ $payFull }}">
    <x-slot:left>
        <div class="details-book">
            <h2 class="sub-heading-2-p">Details</h2>
            <p>Tell us a little more about yourself. </p>
            <div class="details-form-module">
                <h6>Purchaser</h6>
                <p>Your quote will be sent to the email address provided for Guest 1</p>
                <div class="single-details-module">
                    <div>
                        <label for="first-name">First name*</label>
                        <input type="text" wire:model="lead.first_name" id="first-name" value="" placeholder="Enter your first name" required>
                        @error('lead.first_name') <span class="text-danger">{{ $message }}</span>@enderror
                        <small>Include middle names if applicable.</small>
                    </div>
                    <div>
                        <label for="last-name">Last name*</label>
                        <input type="text" wire:model="lead.last_name" id="last-name" value="" placeholder="Enter your last name" required>
                        @error('lead.last_name') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="email">Email*</label>
                        <input type="email" id="email" wire:model="lead.email_address" value="" placeholder="Enter your email address" required>
                        @error('lead.email_address') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="phone">Phone number*</label>
                        <input type="tel" id="phone" value="" placeholder="Enter your phone number" required>
                        @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="country">Country</label>
                        <select id="country" wire:model="leadAddress.country_id">
                            @foreach(\App\Models\Location\Country::orderBy('priority','asc')->orderBy('name', 'asc')->get() as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('leadAddress.country_id') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="dob-input">
                        <label for="dob">Date of birth</label>
                        <input type="text" wire:model="lead.date_of_birth" id="custom-input-dob-1" class="calendar hasDatepicker" data-picker
                               name="upload-release" placeholder="Enter your date of birth">
                        <img src="{{ asset('icons/checkin.svg') }}" alt="calendar">
                        @error('lead.date_of_birth')<span class="text-danger">{{ $message }}</span>@enderror
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
                    </div>
                </div>
            @endif
        </div>
    </x-slot:left>
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

            jQuery('#summernote').summernote({
                placeholder: 'Type here',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['font', ['bold', 'italic', 'underline']],
                    ['para', ['paragraph', 'ol']],
                    ['insert', ['link', 'picture', 'emoji']],
                ],
            });
        });
    </script>
</x-customer.booking.v3.layout>