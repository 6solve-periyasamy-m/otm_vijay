<div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Edit Travellers
                <a href="{{ route('customer-booking.index', ['bookingUrl' => $this->booking->tour->booking_form_url, 'token' => $this->booking->token]) }}" class="btn btn-warning text-dark float-end">
                    Back to Start
                </a>
            </div>
            If you return to the start, any changes made to the itinerary will be reset.
            <br/>
            If you want to edit a specific customers itineraries, please click on their details below.
        </div>
    </div>
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

    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Rooming Manager
                <a href="{{ route('customer-booking.rooming', ['bookingUrl' => $this->booking->tour->booking_form_url, 'token' => $this->booking->token]) }}" class="btn btn-success float-end">
                    Edit Rooming
                </a>
            </div>
            Please make sure all travellers are added before editing rooming, as adding additional travellers will reset room assignments. Deleting/editing travellers will <span class="fw-bold">not</span> reset the rooming.
        </div>
    </div>

    <livewire:customer.booking.traveller-components key="{{ now() }}" :traveller="$this->active"/>

    @include('partials.customer.booking.summary.schedule', ['booking' => $this->booking,])

    <livewire:customer.booking.payment key="{{ $this->booking->due_today }}" :booking="$this->booking" />
    <x-wire-loader />
</div>
