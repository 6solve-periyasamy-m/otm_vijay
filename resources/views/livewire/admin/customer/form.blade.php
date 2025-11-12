<div>
    <x-admin.section.card cardClass="card-blue">
        <div class="d-flex float-end">
            <button class="btn btn-success" wire:click="save">{{ Icon::save() }}Save Customer</button>
        </div>
    </x-admin.section.card>
    <div class="form-scroll-body"/>
        <x-admin.section.card>
            <x-slot:title>Profile Details</x-slot:title>
            <div class="row">
                <x-livewire.input wire:model="customer.title" label="Title" width="1" />
                <x-livewire.input wire:model="customer.first_name" label="First Name" required width="3" />
                <x-livewire.input wire:model="customer.middle_names" label="Middle Names" width="2" />
                <x-livewire.input wire:model="customer.last_name" label="Last Name" required width="3" />
                <x-livewire.input wire:model="customer.gender" width="1" label="Gender" />
                <x-livewire.input wire:model="customer.date_of_birth" type="date" label="Date of Birth" width="2" />
                <x-livewire.input.select.organization width="4" name="customer.organization_id" value="{{$customer->organization_id}}" clear label="Organization" />
                <x-livewire.input.telephone name="customer.mobile_number" value="{{$customer->mobile_number}}" width="2" label="Primary Phone" />
                <x-livewire.input.telephone name="customer.other_phone_number" value="{{$customer->other_phone_number}}" width="2" label="Secondary Phone" />
                <x-livewire.input.select.user name="customer.consultant_id" value="{{ $customer->consultant_id }}" label="Inhouse Consultant" width="3" nullable="true" clear="true"/>
                <x-livewire.input wire:model="customer.email_address" width="3" label="Email ID" />
                <x-livewire.input wire:model="password" type="password" width="3" label="Password" />
            </div>
        </x-admin.section.card>
        <x-admin.section.card>
            <x-slot:title>Emergency Contact Details</x-slot:title>
            <div class="row">                
                <x-livewire.input wire:model="customer.emergency_contact_name" width="3" label="Emergency Contact Name" />
                <x-livewire.input wire:model="customer.emergency_contact_relationship" width="3" label="Emergency Contact Relation" />
                <x-livewire.input.telephone name="customer.emergency_contact_telephone" value="{{$customer->emergency_contact_telephone}}" width="3" label="Emergency Contact Phone" />
            </div>
        </x-admin.section.card>
        <x-admin.section.card>
            <div class="row">
                <div class="col-5 row">
                    <span class="col-12"><h4 class="fw-bold">Home Address</h4></span>
                    <x-livewire.input wire:model="home.address_line_1" width="6" label="Address Line 1" />
                    <x-livewire.input wire:model="home.address_line_2" width="6" label="Address Line 2" />
                    <x-livewire.input wire:model="home.town" width="6" label="Town" />
                    <x-livewire.input wire:model="home.region" width="6" label="Region" />
                    <x-livewire.input.select.country name="home.country_id" value="{{$home->country_id}}" width="6" label="Country" />
                    <x-livewire.input wire:model="home.postcode" width="6" label="Postcode" />
                </div>
                <div class="col-2" style="display: grid; align-content: center; justify-content: center">
                    <button class="btn btn-primary mb-2" wire:click="homeToBilling">
                        {{ Icon::right() }} Copy home to billing
                    </button>
                    <button class="btn btn-primary" wire:click="billingToHome">
                        {{ Icon::left() }} Copy billing to home
                    </button>
                </div>
                <div class="col-5 row">
                    <span class="col-12"><h4 class="fw-bold">Billing Address</h4></span>
                    <x-livewire.input wire:model="billing.address_line_1" width="6" label="Address Line 1" />
                    <x-livewire.input wire:model="billing.address_line_2" width="6" label="Address Line 2" />
                    <x-livewire.input wire:model="billing.town" width="6" label="Town" />
                    <x-livewire.input wire:model="billing.region" width="6" label="Region" />
                    <x-livewire.input.select.country name="billing.country_id" value="{{$billing->country_id}}" width="6" label="Country" />
                    <x-livewire.input wire:model="billing.postcode" width="6" label="Postcode" />
                </div>
            </div>
        </x-admin.section.card>
        <x-admin.section.card>
            <x-slot:title>Passport Details</x-slot:title>
            <div class="row">
                <x-livewire.input wire:model="customer.passport_first_name" label="First Name" width="3" />
                <x-livewire.input wire:model="customer.passport_middle_name" label="Middle Name" width="3" />
                <x-livewire.input wire:model="customer.passport_last_name" label="Last Name" width="3" />
                <x-livewire.input wire:model="customer.nationality" width="3" label="Nationality" />
                <x-livewire.input wire:model="customer.passport_number" label="Passport Number" width="3" />
                <x-livewire.input wire:model="customer.passport_country_of_issue" label="Issuing Country" width="3" />
                <x-livewire.input wire:model="customer.passport_issue_date" type="date" label="Issue Date" width="3" />
                <x-livewire.input wire:model="customer.passport_expiry_date" type="date" label="Expiry Date" width="3" />
            </div>
        </x-admin.section.card>
        <x-admin.section.card>
            <x-slot:title>
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 title-bold" style="font-weight:700">Loyalty / Memberships</h4>
                    <button wire:click="addLoyaltyNumber" class="btn btn-primary">
                        {{ Icon::plus() }} Add Loyalty Number 
                    </button>
                </div>
            </x-slot:title>
            <div class="row">
                @foreach($this->loyalty as $key => $loyalty)
                <div class="col-l-4 col-md-6 col-12">
                    <x-admin.section.card>
                        <div class="row">
                            <x-livewire.input.select.customer.loyalty-number-type name="loyalty.{{$key}}.type" value="{{$loyalty['type']}}" label="Type" width="4" />
                            <x-livewire.input wire:model="loyalty.{{$key}}.notes" label="Details" width="3" />
                            <x-livewire.input wire:model="loyalty.{{$key}}.name" label="Loyalty Number" width="3" />
                            <div class="col-2" style="display: flex;align-content: center;justify-content: center;margin: 1rem 0;">
                                <button wire:click="removeLoyaltyNumber({{$key}})" class="btn btn-outline-danger mb-0">{{Icon::delete()}}</button>
                            </div>
                        </div>
                    </x-admin.section.card>
                </div>
                @endforeach
            </div>
        </x-admin.section.card>
        <x-admin.section.card>
            <x-slot:title>
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 title-bold" style="font-weight:700">Merchandise / Others</h4>
                    <button wire:click="addMerchandise" class="btn btn-primary">
                        {{ Icon::plus() }} Add Merchandise Category
                    </button>
                </div>
            </x-slot:title>
            <div class="row">
                @foreach($this->merchandise as $key => $item)
                <div class="col-l-4 col-md-6 col-12">
                    <x-admin.section.card>
                        <div class="row">
                            <x-livewire.input.select.customer.merchandise-category name="merchandise.{{$key}}.category" value="{{$item['category']}}" label="Category" width="4" />
                            <x-livewire.input wire:model="merchandise.{{$key}}.size" label="size" width="3" />
                            <x-livewire.input wire:model="merchandise.{{$key}}.other_details" label="Other Details" width="3" />
                            <div class="col-2" style="display: flex;align-content: center;justify-content: center;margin: 1rem 0;">
                                <button wire:click="removeMerchandise({{$key}})" class="btn btn-outline-danger mb-0">{{Icon::delete()}}</button>
                            </div>
                        </div>
                    </x-admin.section.card>
                </div>
                @endforeach                
            </div>
        </x-admin.section.card>
        <x-admin.section.card>
            <x-slot:title>Notes</x-slot:title>
            <div class="row">
                <x-livewire.input.text-area wire:model="customer.internal_notes" label="Internal Notes" width="3" />
                <x-livewire.input.text-area wire:model="customer.external_notes" label="External Notes" width="3" />
                <x-livewire.input.text-area wire:model="customer.dietary_notes" label="Dietary Notes" width="3" />
                <x-livewire.input.text-area wire:model="customer.mobility_notes" label="Mobility Notes" width="3" />
            </div>
        </x-admin.section.card>
    </div>
</div>