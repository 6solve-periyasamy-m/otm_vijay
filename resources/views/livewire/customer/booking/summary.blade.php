<div>
    <x-admin.section.card>
        <x-slot:header>
            Edit Travellers
            <a href="{{ route('customer-booking.index', ['bookingUrl' => $this->booking->tour->booking_form_url, 'token' => $this->booking->token]) }}" class="btn btn-warning text-dark float-end">
                Back to Start
            </a>
        </x-slot:header>
        If you return to the start, any changes made to the itinerary will be reset.
        <br/>
        If you want to edit a specific customers itineraries, please click on their details below.
    </x-admin.section.card>
    <div class="mb-4">
        <x-customer.expander id="travellers" nobg>
            <x-slot:header>
                <h2 class="mb-0" style="width: 100%; text-align: center;">All Travellers</h2>
            </x-slot:header>
            @foreach($this->booking->travellers as $traveller)
                @include('partials.customer.booking.customer', ['traveller' => $traveller])
            @endforeach
            <div class="customer-card" wire:click="$emit('openModal', 'customer.booking.form.additional-traveller', {{ json_encode(['booking' => $this->booking->id,]) }})">
                <div class="flex-centered fs-30">
                    <i class="icon-plus"></i>
                </div>
            </div>
        </x-customer.expander>
    </div>

    <x-admin.section.card>
        <x-slot:header>
            Rooming Manager
            <a href="{{ route('customer-booking.rooming', ['bookingUrl' => $this->booking->tour->booking_form_url, 'token' => $this->booking->token]) }}" class="btn btn-success float-end">
                Edit Rooming
            </a>
        </x-slot:header>
        Please make sure all travellers are added before editing rooming, as adding additional travellers will reset room assignments. Deleting/editing travellers will <span class="fw-bold">not</span> reset the rooming.
    </x-admin.section.card>

    <livewire:customer.booking.traveller-components key="{{ now() }}" :traveller="$this->active"/>

    <livewire:customer.booking.schedule key="{{ now() }}" :booking="$this->booking"/>

    <livewire:customer.booking.cost-breakdown key="{{ now() }}" :booking="$this->booking"/>

    <livewire:customer.booking.voucher key="{{ now() }}" :traveller="$this->active" />

    <livewire:customer.booking.payment key="{{ $this->booking->due_today }}" :booking="$this->booking" />

    <x-wire-loader />
</div>
