<div class="inner_content">
    <x-customer.overview-top-bar title="Your Details" :search="false" />
    <div class="your_details">
        <div class="your_details_row">
            <div class="your_details_colm_1">
                <ul>
                    <li class="active"><a href="#personal_details">Personal Details</a></li>
                    <li><a href="#address_details">Address</a></li>
                    <li><a href="#frequent_details">Frequent Flyer Details</a></li>
                    <li><a href="#other_details">Other Details</a></li>
                </ul>
            </div>
            <div class="your_details_colm_2">
            <!-- Personal Details Section -->
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="personal_details" id="personal_details">
                    <h3>Personal details</h3>
                    <p>Manage your personal details</p>
                    <div class="personal_details_form">
                        <h5>User Details</h5>
                        <div class="two_input_field">
                            <div class="two_label_field title_label"> <x-livewire.input wire:model="customer.title" label="Title" width="12" /></div>
                            <div class="two_label_field"><x-livewire.input wire:model="customer.first_name" label="First Name" required /></div>
                            <div class="two_label_field"><x-livewire.input wire:model="customer.middle_names" label="Middle Names" /></div>
                            <div class="two_label_field"><x-livewire.input wire:model="customer.last_name" label="Last Name" required /></div>
                        </div>
                        <div class="two_input_field">
                            <div class="two_label_field"><x-livewire.input wire:model="customer.date_of_birth" type="date" label="Date of Birth" required /> </div>
                            <div class="two_label_field"><x-livewire.input wire:model="customer.email_address" label="Email Address" /> </div>
                            {{-- <div class="two_label_field"><x-livewire.input wire:model="customer.gender" label="Gender" width="4" /></div> --}}
                        </div>

                        <div class="two_input_field mobile_number">
                            <div class="two_label_field"><x-livewire.input.telephone name="customer.mobile_number" value="{{ $customer->mobile_number }}" label="Mobile Number" required /></div>
                            <div class="two_label_field"><x-livewire.input.telephone name="customer.other_phone_number" value="{{ $customer->other_phone_number }}" label="Alternate Mobile Number" /></div>
                            {{-- <div class="two_label_field"><x-livewire.input wire:model="password" type="password" label="Password" width="4" /></div> --}}
                        </div>                                                 
                    </div>
                </div>
                <hr/>
            <!-- EOD Personal Details Section -->
            <!-- Address Section -->
                <div class="personal_details address_details" id="address_details">
                    <h3>Address</h3>
                    <p>Manage your address</p>
                    <div class="address_details_form">
                        <div class="address_detail_colm_1" id="home-address-section">
                            <h5>Home Address</h5>
                            <div class="one_input_field"><x-livewire.input wire:model="home.address_line_1" label="Address Line 1" required /></div>
                            <div class="one_input_field"><x-livewire.input wire:model="home.address_line_2" label="Address Line 2" /></div>
                            <div class="one_input_field"><x-livewire.input wire:model="home.town" label="Town" required /></div>
                            <div class="one_input_field"><x-livewire.input wire:model="home.region" label="Region" required /></div>
                            <div class="one_input_field addr_country"><x-livewire.input.select.country name="home.country_id" value="{{ $home->country_id }}" label="Country" required /></div>
                            <div class="one_input_field"><x-livewire.input wire:model="home.postcode" label="Postcode" required /></div>
                        </div>
                        <div class="address_detail_colm_2">
                            <div class="home_addr"><h5>Billing Address</h5><span class="same_as">
                                <label for="home_is_billing" class="hs-form-checkbox-display">
                                <input class="form-check-input" type="checkbox" wire:model="homeIsBilling" id="home_is_billing">
                                <span>Same as Home</span>
                                </label>
                            </span></div>
                            <div class="one_input_field"><x-livewire.input wire:model="billing.address_line_1" label="Address Line 1" required /></div>
                            <div class="one_input_field"><x-livewire.input wire:model="billing.address_line_2" label="Address Line 2" /></div>
                            <div class="one_input_field"><x-livewire.input wire:model="billing.town" label="Town" required /></div>
                            <div class="one_input_field"><x-livewire.input wire:model="billing.region" label="Region" required /></div>
                            <div class="one_input_field addr_country"><x-livewire.input.select.country name="billing.country_id" value="{{ $billing->country_id }}" label="Country" required /></div>
                            <div class="one_input_field"><x-livewire.input wire:model="billing.postcode" label="Postcode" required /></div>
                            <div class="one_input_field"></div>
                        </div>
                    </div>
                </div>
                <hr/>
            <!-- EOD Address Section -->
            <!-- Emergency Contact Section -->
                 <div class="personal_details contact_details" id="contact_details">
                    <h3>Emergency Contact Details</h3>
                    <div class="emergency_details_form">
                        <div class="two_input_field full_width_field"><div class="one_input_field"><x-livewire.input wire:model="customer.emergency_contact_name" label="Contact Name" /></div></div>
                        <div class="two_input_field mobile_number"><div class="one_input_field">
                            <x-livewire.input wire:model="customer.emergency_contact_relationship" label="Relationship" /></div>
                            <div class="one_input_field"><x-livewire.input.telephone name="customer.emergency_contact_telephone" value="{{ $customer->emergency_contact_telephone }}" label="Mobile Number" /> </div>
                        </div>
                    </div>
                </div>
                <hr/>
            <!-- EOD Emergency Contact Section -->             
            <!-- Other Details Section -->
                <div class="personal_details other_details" id="other_details">
                    <h3>Other Details</h3>
                    <div class="other_details_form">
                        <div class="one_input_field"><div class="two_input_field full_width_field"><x-livewire.input.text-area wire:model="customer.external_notes" label="Other Notes" /></div></div>
                        <div class="one_input_field"><div class="two_input_field full_width_field"><x-livewire.input.text-area wire:model="customer.dietary_notes" label="Dietary Requirements"  /></div></div>
                        <div class="one_input_field"><div class="two_input_field full_width_field"><x-livewire.input.text-area wire:model="customer.mobility_notes" label="Mobility Requirements" /></div></div>
                    </div>
                </div>
                <hr/>
            <!-- EOD Other Details Section -->

            <!-- Loyalty Numbers Section -->
                <div class="mt-5 personal_details loyalty_details" id="loyalty_details">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Loyalty / Memberships</h3>
                        <div wire:click="addLoyaltyNumber" class="cursor-pointer d-flex align-items-center justify-content-center rounded p-1 icon-border" title="Add Loyalty Item" role="button" tabindex="0" aria-label="Add Loyalty Item">
                            <img src="{{ asset('images/customer/images/add_btn_color.svg') }}"  class="reservation_wht_icn plus-icon-size"  alt="Add" />
                        </div>
                    </div>
                    
                    <div class="row">
                        @foreach($loyalty as $key => $loyaltyItem)
                        <div class="col-lg-6 col-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <x-livewire.input.select.customer.loyalty-number-type 
                                            name="loyalty.{{ $key }}.type" 
                                            value="{{ $loyaltyItem['type'] }}" 
                                            label="Type" 
                                            width="6" />
                                        <x-livewire.input wire:model="loyalty.{{ $key }}.name" label="Loyalty Number" width="5" />
                                        <div class="col-1 d-flex justify-content-end">
                                            <div wire:click="removeLoyaltyNumber({{ $key }})" class="cursor-pointer d-flex align-items-center justify-content-center rounded p-1 icon-no-border" title="Remove Loyalty Item" role="button" tabindex="0" aria-label="Remove Loyalty Item">
                                                <img src="{{ asset('images/customer/images/remove.png') }}"  class="reservation_wht_icn plus-icon-size"  alt="Remove" />
                                            </div>
                                        </div>
                                        <x-livewire.input.text-area wire:model="loyalty.{{ $key }}.notes" label="Notes" width="12" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <hr/>
            <!-- EOD Loyalty Numbers Section -->
            <!-- Merchandise Section -->
                <div class="mt-5 personal_details loyalty_details" id="frequent_details">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Merchandise / Others</h3>
                        <div wire:click="addMerchandise" class="cursor-pointer d-flex align-items-center justify-content-center rounded p-1 icon-border" title="Add Merchandise Item" role="button" tabindex="0" aria-label="Add Merchandise Item">
                            <img src="{{ asset('images/customer/images/add_btn_color.svg') }}"  class="reservation_wht_icn plus-icon-size"  alt="Add Merchandise" />
                        </div>
                    </div>                    
                    <div class="row">
                        @foreach($merchandise as $key => $item)
                        <div class="col-lg-6 col-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <x-livewire.input.select.customer.merchandise-category 
                                            name="merchandise.{{ $key }}.category" 
                                            value="{{ $item['category'] }}" 
                                            label="Category" 
                                            width="6" />
                                        <x-livewire.input wire:model="merchandise.{{ $key }}.size" label="Size" width="5" />
                                        <div class="col-1 d-flex justify-content-end">
                                            <div wire:click="removeMerchandise({{ $key }})" class="cursor-pointer d-flex align-items-center justify-content-center rounded p-1 icon-no-border" title="Remove Merchandise Item" role="button" tabindex="0" aria-label="Remove Merchandise Item">
                                                <img src="{{ asset('images/customer/images/remove.png') }}"  class="reservation_wht_icn plus-icon-size"  alt="Remove" />
                                            </div>    
                                        </div>
                                        <x-livewire.input.text-area wire:model="merchandise.{{ $key }}.other_details" label="Other Details" width="12" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <hr/>
            <!-- EOD Merchandise Section -->

            <!-- Save Button -->
                <div class="common_btn mt-4">
                    <button wire:click="save" wire:loading.attr="disabled" class="save_changes">
                        <span wire:loading.remove>Save Changes</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
            <!-- EOD Save Button --> 
            </div>
        </div>
    </div>
</div>
