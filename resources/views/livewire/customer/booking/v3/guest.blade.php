@php
    $location = collect([$tour->city, $tour->country?->name])->filter()->implode(', ');
@endphp

<x-customer.booking.v3.layout :tour="$tour" :booking="$booking" :stage="1" payFull="{{ $payFull }}">
    <x-slot:left>
        <div class="top-form-contain">
            <div class="email-quote"> <!-- customer_profile -->
                <p>
                    <label for="firstname">First Name *</label>
                    <input type="text" id="firstname" wire:model.lazy="lead.first_name">
                    @error('lead.first_name') <span class="text-danger">{{ $message }}</span>@enderror
                    <span class="mdle_nme">Include middle names if applicable.</span>
                </p>
                <p>
                    <label for="lastname">Last Name *</label>
                    <input type="text" id="lastname" wire:model.lazy="lead.last_name">
                    @error('lead.last_name') <span class="text-danger">{{ $message }}</span> @enderror
                </p>
            </div>
            <div class="email-quote">
                <p>
                    <label for="email">Email *</label>
                    <input type="email" wire:model.lazy="lead.email_address" id="email" name="email">
                    @error('lead.email_address') <span class="text-danger">{{ $message }}</span> @enderror
                </p>
                <p>
                    <label for="mobileno">Mobile No</label>
                    <input type="text" id="mobileno" wire:model.lazy="lead.mobile_number">
                </p>
            </div>
        </div>
        <div class="no-of-travellers">
            <h4 class="sub-heading-4">Number of Travellers</h4>
            <div class="quantity">
                <span class="minus" wire:click="removeTraveller()"><img src="{{ asset('icons/Minus.svg') }}" alt="minus"></span>
                <span>|</span>
                <span class="value">{{ $this->getTravellerCount() }}</span>
                <span>|</span>
                <span class="plus" wire:click="addTraveller()"><img src="{{ asset('icons/Plus.svg') }}" alt="plus"></span>
            </div>
        </div>
        <div class="contact-block">
            <p class="description">If you are a concession card holder, or booking with children under 12, get
                in touch for a tailor-made package:</p>
            <p class="phone">Domestic <a href="tel:1300 730 023">+1300 730 023</a></p>
            <p class="phone">International <a href="tel:+61 2 7201 9353"> +61 2 7201 9353</a></p>
            <p class="email">Email <a href="mailto:travel@kpt.com.au">travel@kpt.com.au</a></p>
        </div>
    </x-slot:left>
</x-customer.booking.v3.layout>
